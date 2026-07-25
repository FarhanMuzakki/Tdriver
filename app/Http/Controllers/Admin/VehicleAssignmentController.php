<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VehicleAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $status = $request->input('status');

        $assignments = VehicleAssignment::query()
            ->with(['driver', 'vehicle'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('destination', 'ilike', "%{$search}%")
                        ->orWhere('purpose', 'ilike', "%{$search}%")
                        ->orWhereHas('driver', function ($driverQuery) use ($search) {
                            $driverQuery
                                ->where('name', 'ilike', "%{$search}%")
                                ->orWhere('email', 'ilike', "%{$search}%");
                        })
                        ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                            $vehicleQuery
                                ->where('plate_number', 'ilike', "%{$search}%")
                                ->orWhere('brand', 'ilike', "%{$search}%")
                                ->orWhere('model', 'ilike', "%{$search}%");
                        });
                });
            })
            ->when(
                in_array($status, ['active', 'finished'], true),
                fn ($query) => $query->where('status', $status)
            )
            ->latest('assigned_at')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.assignments.index',
            compact('assignments')
        );
    }

    public function create()
    {
        $drivers = User::query()
            ->where('role', 'driver')
            ->where('driver_status', 'active')
            ->whereDoesntHave('assignments', function ($query) {
                $query->where('status', 'active');
            })
            ->orderBy('name')
            ->get();

        $vehicles = Vehicle::query()
            ->where('status', 'available')
            ->whereDoesntHave('assignments', function ($query) {
                $query->where('status', 'active');
            })
            ->orderBy('plate_number')
            ->get();

        return view(
            'admin.assignments.create',
            compact('drivers', 'vehicles')
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
            'driver_id' => [
                'required',
                'uuid',
                'exists:users,id',
            ],
            'assigned_at' => [
                'required',
                'date',
            ],
            'planned_return_at' => [
                'nullable',
                'date',
                'after_or_equal:assigned_at',
            ],
            'destination' => [
                'required',
                'string',
                'max:255',
            ],
            'purpose' => [
                'required',
                'string',
                'max:255',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $assignment = DB::transaction(function () use ($validated) {
            $driver = User::query()
                ->lockForUpdate()
                ->findOrFail($validated['driver_id']);

            $vehicle = Vehicle::query()
                ->lockForUpdate()
                ->findOrFail($validated['vehicle_id']);

            if ($driver->role !== 'driver') {
                throw ValidationException::withMessages([
                    'driver_id' => 'User yang dipilih bukan driver.',
                ]);
            }

            if ($driver->driver_status !== 'active') {
                throw ValidationException::withMessages([
                    'driver_id' => 'Driver sedang nonaktif.',
                ]);
            }

            $driverHasActiveAssignment = VehicleAssignment::query()
                ->where('driver_id', $driver->id)
                ->where('status', 'active')
                ->exists();

            if ($driverHasActiveAssignment) {
                throw ValidationException::withMessages([
                    'driver_id' => 'Driver masih memiliki assignment aktif.',
                ]);
            }

            $vehicleHasActiveAssignment = VehicleAssignment::query()
                ->where('vehicle_id', $vehicle->id)
                ->where('status', 'active')
                ->exists();

            if ($vehicleHasActiveAssignment) {
                throw ValidationException::withMessages([
                    'vehicle_id' => 'Kendaraan masih memiliki assignment aktif.',
                ]);
            }

            if ($vehicle->status === 'maintenance') {
                throw ValidationException::withMessages([
                    'vehicle_id' => 'Kendaraan sedang maintenance.',
                ]);
            }

            if ($vehicle->status !== 'available') {
                throw ValidationException::withMessages([
                    'vehicle_id' => 'Kendaraan tidak tersedia untuk assignment.',
                ]);
            }

            $assignment = VehicleAssignment::create([
                'vehicle_id' => $vehicle->id,
                'driver_id' => $driver->id,
                'assigned_at' => $validated['assigned_at'],
                'planned_return_at' => $validated['planned_return_at'] ?? null,
                'destination' => $validated['destination'] ?? null,
                'purpose' => $validated['purpose'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'returned_at' => null,
                'status' => 'active',
            ]);

            $vehicle->update([
                'status' => 'in_use',
            ]);

            return $assignment;
        });

        return redirect()
            ->route('admin.assignments.show', $assignment)
            ->with('success', 'Assignment berhasil dibuat.');
    }

    public function show(VehicleAssignment $assignment)
    {
        $assignment->load([
            'vehicle',
            'driver',
        ]);

        return view(
            'admin.assignments.show',
            compact('assignment')
        );
    }

    public function finish(VehicleAssignment $assignment)
    {
        if ($assignment->status !== 'active') {
            return back()->with(
                'error',
                'Assignment ini sudah selesai.'
            );
        }

        DB::transaction(function () use ($assignment) {
            $assignment = VehicleAssignment::query()
                ->lockForUpdate()
                ->findOrFail($assignment->id);

            if ($assignment->status !== 'active') {
                return;
            }

            $assignment->update([
                'status' => 'finished',
                'returned_at' => now(),
            ]);

            $vehicle = Vehicle::query()
                ->lockForUpdate()
                ->find($assignment->vehicle_id);

            if ($vehicle && $vehicle->status !== 'maintenance') {
                $vehicle->update([
                    'status' => 'available',
                ]);
            }
        });

        return redirect()
            ->route('admin.assignments.show', $assignment)
            ->with('success', 'Assignment berhasil diselesaikan.');
    }

    public function destroy(VehicleAssignment $assignment)
    {
        if ($assignment->status === 'active') {
            return back()->with(
                'error',
                'Assignment aktif tidak dapat dihapus. Selesaikan assignment terlebih dahulu.'
            );
        }

        $assignment->delete();

        return redirect()
            ->route('admin.assignments.index')
            ->with('success', 'Riwayat assignment berhasil dihapus.');
    }
}