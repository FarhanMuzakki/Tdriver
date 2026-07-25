<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceLog;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MaintenanceLogController extends Controller
{
    /**
     * Menampilkan daftar maintenance.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $status = $request->input('status');

        $maintenanceLogs = MaintenanceLog::query()
            ->with('vehicle')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('service_type', 'ilike', "%{$search}%")
                        ->orWhere('workshop', 'ilike', "%{$search}%")
                        ->orWhere('notes', 'ilike', "%{$search}%")
                        ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                            $vehicleQuery
                                ->where('plate_number', 'ilike', "%{$search}%")
                                ->orWhere('brand', 'ilike', "%{$search}%")
                                ->orWhere('model', 'ilike', "%{$search}%")
                                ->orWhere('type', 'ilike', "%{$search}%");
                        });
                });
            })
            ->when(
                in_array(
                    $status,
                    ['scheduled', 'in_progress', 'completed'],
                    true
                ),
                fn ($query) => $query->where('status', $status)
            )
            ->latest('service_date')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.maintenance.index',
            compact('maintenanceLogs')
        );
    }

    /**
     * Menampilkan form tambah maintenance.
     */
    public function create()
    {
        $vehicles = Vehicle::query()
            ->orderBy('plate_number')
            ->get();

        return view(
            'admin.maintenance.create',
            compact('vehicles')
        );
    }

    /**
     * Menyimpan maintenance.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        DB::transaction(function () use ($validated) {
            $vehicle = Vehicle::query()
                ->lockForUpdate()
                ->findOrFail($validated['vehicle_id']);

            if (
                $validated['status'] === 'in_progress'
                && $vehicle->status === 'in_use'
            ) {
                throw ValidationException::withMessages([
                    'vehicle_id' => 'Kendaraan sedang digunakan dan belum bisa masuk maintenance.',
                ]);
            }

            $validated['completed_at'] =
                $validated['status'] === 'completed'
                    ? now()
                    : null;

            MaintenanceLog::create($validated);

            if ($validated['status'] === 'in_progress') {
                $vehicle->update([
                    'status' => 'maintenance',
                ]);
            }

            if ($validated['status'] === 'completed') {
                $this->restoreVehicleStatus($vehicle);
            }
        });

        return redirect()
            ->route('admin.maintenance.index')
            ->with(
                'success',
                'Data maintenance berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan form edit maintenance.
     */
    public function edit(MaintenanceLog $maintenanceLog)
    {
        $vehicles = Vehicle::query()
            ->orderBy('plate_number')
            ->get();

        return view(
            'admin.maintenance.edit',
            compact('maintenanceLog', 'vehicles')
        );
    }

    /**
     * Memperbarui maintenance.
     */
    public function update(
        Request $request,
        MaintenanceLog $maintenanceLog
    ) {
        $validated = $request->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        DB::transaction(function () use (
            $validated,
            $maintenanceLog
        ) {
            $maintenanceLog = MaintenanceLog::query()
                ->lockForUpdate()
                ->findOrFail($maintenanceLog->id);

            $oldVehicle = Vehicle::query()
                ->lockForUpdate()
                ->find($maintenanceLog->vehicle_id);

            $newVehicle = Vehicle::query()
                ->lockForUpdate()
                ->findOrFail($validated['vehicle_id']);

            $oldStatus = $maintenanceLog->status;
            $newStatus = $validated['status'];

            if (
                $newStatus === 'in_progress'
                && $newVehicle->status === 'in_use'
                && $newVehicle->id !== $oldVehicle?->id
            ) {
                throw ValidationException::withMessages([
                    'vehicle_id' => 'Kendaraan sedang digunakan dan belum bisa masuk maintenance.',
                ]);
            }

            if (
                $newStatus === 'completed'
                && !$maintenanceLog->completed_at
            ) {
                $validated['completed_at'] = now();
            }

            if ($newStatus !== 'completed') {
                $validated['completed_at'] = null;
            }

            $maintenanceLog->update($validated);

            /*
            |--------------------------------------------------------------------------
            | Pulihkan kendaraan lama jika kendaraan diganti
            |--------------------------------------------------------------------------
            */

            if (
                $oldVehicle
                && $oldVehicle->id !== $newVehicle->id
                && $oldStatus === 'in_progress'
            ) {
                $this->restoreVehicleStatus($oldVehicle);
            }

            /*
            |--------------------------------------------------------------------------
            | Update status kendaraan baru
            |--------------------------------------------------------------------------
            */

            if ($newStatus === 'in_progress') {
                $newVehicle->update([
                    'status' => 'maintenance',
                ]);
            }

            if ($newStatus === 'completed') {
                $this->restoreVehicleStatus($newVehicle);
            }

            /*
            |--------------------------------------------------------------------------
            | Maintenance dibatalkan kembali menjadi scheduled
            |--------------------------------------------------------------------------
            */

            if (
                $oldStatus === 'in_progress'
                && $newStatus === 'scheduled'
            ) {
                $this->restoreVehicleStatus($newVehicle);
            }
        });

        return redirect()
            ->route('admin.maintenance.index')
            ->with(
                'success',
                'Data maintenance berhasil diperbarui.'
            );
    }

    /**
     * Menghapus maintenance.
     */
    public function destroy(MaintenanceLog $maintenanceLog)
    {
        DB::transaction(function () use ($maintenanceLog) {
            $maintenanceLog = MaintenanceLog::query()
                ->lockForUpdate()
                ->findOrFail($maintenanceLog->id);

            $vehicle = Vehicle::query()
                ->lockForUpdate()
                ->find($maintenanceLog->vehicle_id);

            $wasInProgress =
                $maintenanceLog->status === 'in_progress';

            $maintenanceLog->delete();

            if ($wasInProgress && $vehicle) {
                $this->restoreVehicleStatus($vehicle);
            }
        });

        return redirect()
            ->route('admin.maintenance.index')
            ->with(
                'success',
                'Data maintenance berhasil dihapus.'
            );
    }

    /**
     * Rules validasi maintenance.
     */
    private function validationRules(): array
    {
        return [
            'vehicle_id' => [
                'required',
                'uuid',
                'exists:vehicles,id',
            ],

            'service_type' => [
                'required',
                'string',
                'max:100',
            ],

            'service_date' => [
                'required',
                'date',
            ],

            'workshop' => [
                'nullable',
                'string',
                'max:150',
            ],

            'cost' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999999999',
            ],

            'odometer' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'status' => [
                'required',
                Rule::in([
                    'scheduled',
                    'in_progress',
                    'completed',
                ]),
            ],
        ];
    }

    /**
     * Pesan validasi.
     */
    private function validationMessages(): array
    {
        return [
            'vehicle_id.required' => 'Kendaraan wajib dipilih.',
            'vehicle_id.exists' => 'Kendaraan tidak ditemukan.',

            'service_type.required' => 'Jenis service wajib diisi.',

            'service_date.required' => 'Tanggal service wajib diisi.',
            'service_date.date' => 'Tanggal service tidak valid.',

            'cost.numeric' => 'Biaya harus berupa angka.',
            'cost.min' => 'Biaya tidak boleh kurang dari nol.',

            'odometer.integer' => 'Odometer harus berupa angka.',
            'odometer.min' => 'Odometer tidak boleh kurang dari nol.',

            'status.required' => 'Status maintenance wajib dipilih.',
            'status.in' => 'Status maintenance tidak valid.',
        ];
    }

    /**
     * Mengembalikan status kendaraan setelah maintenance selesai.
     */
    private function restoreVehicleStatus(Vehicle $vehicle): void
    {
        $hasActiveAssignment = $vehicle
            ->assignments()
            ->where('status', 'active')
            ->exists();

        $vehicle->update([
            'status' => $hasActiveAssignment
                ? 'in_use'
                : 'available',
        ]);
    }
}
