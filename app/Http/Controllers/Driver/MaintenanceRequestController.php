<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequest;
use App\Models\VehicleAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MaintenanceRequestController extends Controller
{
    public function index()
    {
        $maintenanceRequests = MaintenanceRequest::query()
            ->with('vehicle')
            ->where('driver_id', Auth::id())
            ->latest('requested_at')
            ->latest('created_at')
            ->paginate(10);

        return view(
            'driver.maintenance-requests.index',
            compact('maintenanceRequests')
        );
    }

    public function create()
    {
        $assignments = VehicleAssignment::query()
            ->with('vehicle')
            ->where('driver_id', Auth::id())
            ->where('status', 'active')
            ->latest('assigned_at')
            ->get();

        return view(
            'driver.maintenance-requests.create',
            compact('assignments')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => [
                'required',
                'uuid',
                'exists:vehicles,id',
            ],

            'issue_type' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'required',
                'string',
                'max:3000',
            ],

            'priority' => [
                'required',
                Rule::in([
                    MaintenanceRequest::PRIORITY_LOW,
                    MaintenanceRequest::PRIORITY_MEDIUM,
                    MaintenanceRequest::PRIORITY_HIGH,
                    MaintenanceRequest::PRIORITY_URGENT,
                ]),
            ],
        ], [
            'vehicle_id.required' => 'Kendaraan wajib dipilih.',
            'issue_type.required' => 'Jenis masalah wajib diisi.',
            'description.required' => 'Deskripsi masalah wajib diisi.',
            'priority.required' => 'Prioritas wajib dipilih.',
            'priority.in' => 'Prioritas tidak valid.',
        ]);

        $hasActiveAssignment = VehicleAssignment::query()
            ->where('driver_id', Auth::id())
            ->where('vehicle_id', $validated['vehicle_id'])
            ->where('status', 'active')
            ->exists();

        if (!$hasActiveAssignment) {
            throw ValidationException::withMessages([
                'vehicle_id' => 'Kendaraan ini tidak sedang ditugaskan kepada Anda.',
            ]);
        }

        MaintenanceRequest::create([
            'driver_id' => Auth::id(),
            'vehicle_id' => $validated['vehicle_id'],
            'issue_type' => $validated['issue_type'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => MaintenanceRequest::STATUS_PENDING,
            'requested_at' => now(),
        ]);

        return redirect()
            ->route('driver.maintenance-requests.index')
            ->with('success', 'Pengajuan maintenance berhasil dikirim.');
    }

    public function edit(MaintenanceRequest $maintenanceRequest)
    {
        $this->authorizeOwner($maintenanceRequest);
        $this->ensurePending($maintenanceRequest);

        $assignments = VehicleAssignment::query()
            ->with('vehicle')
            ->where('driver_id', Auth::id())
            ->where('status', 'active')
            ->latest('assigned_at')
            ->get();

        return view(
            'driver.maintenance-requests.edit',
            compact(
                'maintenanceRequest',
                'assignments'
            )
        );
    }

    public function update(
        Request $request,
        MaintenanceRequest $maintenanceRequest
    ) {
        $this->authorizeOwner($maintenanceRequest);
        $this->ensurePending($maintenanceRequest);

        $validated = $request->validate([
            'vehicle_id' => [
                'required',
                'uuid',
                'exists:vehicles,id',
            ],

            'issue_type' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'required',
                'string',
                'max:3000',
            ],

            'priority' => [
                'required',
                Rule::in([
                    MaintenanceRequest::PRIORITY_LOW,
                    MaintenanceRequest::PRIORITY_MEDIUM,
                    MaintenanceRequest::PRIORITY_HIGH,
                    MaintenanceRequest::PRIORITY_URGENT,
                ]),
            ],
        ]);

        $hasActiveAssignment = VehicleAssignment::query()
            ->where('driver_id', Auth::id())
            ->where('vehicle_id', $validated['vehicle_id'])
            ->where('status', 'active')
            ->exists();

        if (!$hasActiveAssignment) {
            throw ValidationException::withMessages([
                'vehicle_id' => 'Kendaraan ini tidak sedang ditugaskan kepada Anda.',
            ]);
        }

        $maintenanceRequest->update([
            'vehicle_id' => $validated['vehicle_id'],
            'issue_type' => $validated['issue_type'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
        ]);

        return redirect()
            ->route('driver.maintenance-requests.index')
            ->with('success', 'Pengajuan maintenance berhasil diperbarui.');
    }

    public function destroy(MaintenanceRequest $maintenanceRequest)
    {
        $this->authorizeOwner($maintenanceRequest);
        $this->ensurePending($maintenanceRequest);

        $maintenanceRequest->delete();

        return redirect()
            ->route('driver.maintenance-requests.index')
            ->with('success', 'Pengajuan maintenance berhasil dihapus.');
    }

    private function authorizeOwner(
        MaintenanceRequest $maintenanceRequest
    ): void {
        abort_unless(
            $maintenanceRequest->driver_id === Auth::id(),
            403,
            'Anda tidak memiliki akses ke pengajuan ini.'
        );
    }

    private function ensurePending(
        MaintenanceRequest $maintenanceRequest
    ): void {
        abort_unless(
            $maintenanceRequest->status === MaintenanceRequest::STATUS_PENDING,
            403,
            'Pengajuan yang sudah diproses tidak dapat diubah.'
        );
    }
}