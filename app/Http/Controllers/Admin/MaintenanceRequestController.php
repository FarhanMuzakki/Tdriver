<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceLog;
use App\Models\MaintenanceRequest;
use App\Models\VehicleAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class MaintenanceRequestController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = trim((string) $request->input('search'));

        $maintenanceRequests = MaintenanceRequest::query()
            ->with([
                'driver',
                'vehicle',
            ])
            ->when(
                $status,
                fn ($query) => $query->where('status', $status)
            )
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('issue_type', 'ilike', "%{$search}%")
                        ->orWhere('description', 'ilike', "%{$search}%")
                        ->orWhereHas('driver', function ($driverQuery) use ($search) {
                            $driverQuery->where('name', 'ilike', "%{$search}%");
                        })
                        ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                            $vehicleQuery
                                ->where('plate_number', 'ilike', "%{$search}%")
                                ->orWhere('brand', 'ilike', "%{$search}%")
                                ->orWhere('model', 'ilike', "%{$search}%");
                        });
                });
            })
            ->latest('requested_at')
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        $counts = [
            'all' => MaintenanceRequest::count(),

            'pending' => MaintenanceRequest::where(
                'status',
                MaintenanceRequest::STATUS_PENDING
            )->count(),

            'approved' => MaintenanceRequest::where(
                'status',
                MaintenanceRequest::STATUS_APPROVED
            )->count(),

            'rejected' => MaintenanceRequest::where(
                'status',
                MaintenanceRequest::STATUS_REJECTED
            )->count(),

            'completed' => MaintenanceRequest::where(
                'status',
                MaintenanceRequest::STATUS_COMPLETED
            )->count(),
        ];

        return view(
            'admin.maintenance-requests.index',
            compact(
                'maintenanceRequests',
                'counts'
            )
        );
    }

    public function approve(
    Request $request,
    MaintenanceRequest $maintenanceRequest
) {
    abort_unless(
        $maintenanceRequest->status === MaintenanceRequest::STATUS_PENDING,
        422,
        'Pengajuan ini sudah diproses.'
    );

    $validated = $request->validate([
        'admin_notes' => [
            'nullable',
            'string',
            'max:2000',
        ],
    ]);

    DB::transaction(function () use (
        $maintenanceRequest,
        $validated
    ) {
        $issueType = strtolower(
            trim($maintenanceRequest->issue_type)
        );

        /*
        |--------------------------------------------------------------------------
        | Mapping jenis keluhan ke service_type maintenance_logs
        |--------------------------------------------------------------------------
        |
        | Nilai yang diperbolehkan database:
        | oil_change, tire_change, engine, brake,
        | general_service, other
        |
        */

        $serviceType = match (true) {
            str_contains($issueType, 'oli'),
            str_contains($issueType, 'oil') => 'oil_change',

            str_contains($issueType, 'ban'),
            str_contains($issueType, 'tire') => 'tire_change',

            str_contains($issueType, 'mesin'),
            str_contains($issueType, 'engine') => 'engine',

            str_contains($issueType, 'rem'),
            str_contains($issueType, 'brake') => 'brake',

            str_contains($issueType, 'servis'),
            str_contains($issueType, 'service'),
            str_contains($issueType, 'rutin'),
            str_contains($issueType, 'berkala'),
            str_contains($issueType, 'umum'),
            str_contains($issueType, 'general') => 'general_service',

            default => 'other',
        };

        $maintenanceRequest->update([
            'status' => MaintenanceRequest::STATUS_APPROVED,
            'admin_notes' => $validated['admin_notes'] ?? null,
            'approved_at' => now(),
            'rejected_at' => null,
            'completed_at' => null,
        ]);

        $maintenanceRequest->vehicle()->update([
            'status' => 'maintenance',
        ]);

        MaintenanceLog::firstOrCreate(
            [
                'maintenance_request_id' => $maintenanceRequest->id,
            ],
            [
                'vehicle_id' => $maintenanceRequest->vehicle_id,
                'service_type' => $serviceType,
                'service_date' => now()->toDateString(),
                'workshop' => null,
                'cost' => 0,
                'odometer' => null,
                'notes' => implode("\n", [
                    'Keluhan: ' . $maintenanceRequest->issue_type,
                    'Keterangan: ' . $maintenanceRequest->description,
                ]),
                'status' => 'pending',
                'completed_at' => null,
            ]
        );
    });

    return redirect()
        ->route('admin.maintenance-requests.index')
        ->with(
            'success',
            'Pengajuan disetujui dan maintenance log berhasil dibuat.'
        );
}

    public function reject(
        Request $request,
        MaintenanceRequest $maintenanceRequest
    ) {
        abort_unless(
            $maintenanceRequest->status === MaintenanceRequest::STATUS_PENDING,
            422,
            'Pengajuan ini sudah diproses.'
        );

        $validated = $request->validate([
            'admin_notes' => [
                'required',
                'string',
                'max:2000',
            ],
        ], [
            'admin_notes.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $maintenanceRequest->update([
            'status' => MaintenanceRequest::STATUS_REJECTED,
            'admin_notes' => $validated['admin_notes'],
            'approved_at' => null,
            'rejected_at' => now(),
            'completed_at' => null,
        ]);

        return back()->with(
            'success',
            'Pengajuan maintenance berhasil ditolak.'
        );
    }

    public function complete(
    Request $request,
    MaintenanceRequest $maintenanceRequest
) {
    abort_unless(
        $maintenanceRequest->status === MaintenanceRequest::STATUS_APPROVED,
        422,
        'Hanya pengajuan yang disetujui yang dapat diselesaikan.'
    );

    $validated = $request->validate([
        'service_date' => [
            'required',
            'date',
        ],

        'workshop' => [
            'required',
            'string',
            'max:255',
        ],

        'cost' => [
            'required',
            'numeric',
            'min:0',
        ],

        'odometer' => [
            'nullable',
            'integer',
            'min:0',
        ],

        'service_notes' => [
            'nullable',
            'string',
            'max:3000',
        ],

        'admin_notes' => [
            'nullable',
            'string',
            'max:2000',
        ],
    ], [
        'service_date.required' => 'Tanggal servis wajib diisi.',
        'workshop.required' => 'Nama bengkel wajib diisi.',
        'cost.required' => 'Biaya servis wajib diisi.',
        'cost.numeric' => 'Biaya servis harus berupa angka.',
        'odometer.integer' => 'Odometer harus berupa angka bulat.',
    ]);

    DB::transaction(function () use (
        $maintenanceRequest,
        $validated
    ) {
        $maintenanceLog = MaintenanceLog::query()
            ->where(
                'maintenance_request_id',
                $maintenanceRequest->id
            )
            ->firstOrFail();

        $maintenanceLog->update([
            'service_date' => $validated['service_date'],
            'workshop' => $validated['workshop'],
            'cost' => $validated['cost'],
            'odometer' => $validated['odometer'] ?? null,
            'notes' => $validated['service_notes']
                ?? $maintenanceLog->notes,
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $maintenanceRequest->update([
            'status' => MaintenanceRequest::STATUS_COMPLETED,

            'admin_notes' => $validated['admin_notes']
                ?? $maintenanceRequest->admin_notes,

            'completed_at' => now(),
        ]);

        $hasActiveAssignment = VehicleAssignment::query()
            ->where('vehicle_id', $maintenanceRequest->vehicle_id)
            ->where('status', 'active')
            ->exists();

        $maintenanceRequest->vehicle()->update([
    'status' => $hasActiveAssignment
        ? 'in_use'
        : 'available',
]);
    });

    return back()->with(
        'success',
        'Maintenance selesai dan data servis berhasil disimpan.'
    );
}
public function destroy(MaintenanceRequest $maintenanceRequest)
{
    abort_if(
        in_array(
            $maintenanceRequest->status,
            [
                MaintenanceRequest::STATUS_APPROVED,
                MaintenanceRequest::STATUS_COMPLETED,
            ],
            true
        ),
        422,
        'Pengajuan yang sudah disetujui atau selesai tidak dapat dihapus.'
    );

    $maintenanceRequest->delete();

    return redirect()
        ->route('admin.maintenance-requests.index')
        ->with('success', 'Pengajuan maintenance berhasil dihapus.');
}
}