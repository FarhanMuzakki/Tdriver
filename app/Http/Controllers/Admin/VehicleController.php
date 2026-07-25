<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class VehicleController extends Controller
{
    /**
     * Menampilkan daftar kendaraan.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $status = $request->input('status');

        $vehicles = Vehicle::query()
            ->with('activeAssignment.driver')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('plate_number', 'ilike', "%{$search}%")
                        ->orWhere('brand', 'ilike', "%{$search}%")
                        ->orWhere('model', 'ilike', "%{$search}%")
                        ->orWhere('type', 'ilike', "%{$search}%")
                        ->orWhere('color', 'ilike', "%{$search}%");
                });
            })
            ->when(
                in_array(
                    $status,
                    ['available', 'in_use', 'maintenance'],
                    true
                ),
                fn ($query) => $query->where('status', $status)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.vehicles.index',
            compact('vehicles')
        );
    }

    /**
     * Menampilkan form tambah kendaraan.
     */
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

        return view(
            'admin.vehicles.create',
            compact('drivers')
        );
    }

    /**
     * Menyimpan kendaraan baru dan assignment opsional.
     */
    public function store(Request $request)
    {
        $request->merge([
            'plate_number' => strtoupper(
                trim((string) $request->input('plate_number'))
            ),
        ]);

        $validated = $request->validate([
            'plate_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('vehicles', 'plate_number'),
            ],

            'brand' => [
                'required',
                'string',
                'max:100',
            ],

            'model' => [
                'required',
                'string',
                'max:100',
            ],

            'year' => [
                'required',
                'integer',
                'min:1900',
                'max:' . (date('Y') + 1),
            ],

            'color' => [
                'required',
                'string',
                'max:50',
            ],

            'type' => [
                'required',
                'string',
                'max:100',
            ],

            'fuel_type' => [
                'required',
                Rule::in([
                    'gasoline',
                    'diesel',
                    'electric',
                    'hybrid',
                ]),
            ],

            'transmission' => [
                'required',
                Rule::in([
                    'manual',
                    'automatic',
                ]),
            ],

            'status' => [
                'required',
                Rule::in([
                    'available',
                    'maintenance',
                ]),
            ],

            'service_date' => [
                'nullable',
                'date',
            ],

            'driver_id' => [
                'nullable',
                'uuid',
                'exists:users,id',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ], [
            'image.image' =>
                'File yang diunggah harus berupa gambar.',

            'image.mimes' =>
                'Foto kendaraan harus berformat JPG, JPEG, PNG, atau WebP.',

            'image.max' =>
                'Ukuran foto kendaraan maksimal 2 MB.',
        ]);

        $newImagePath = null;

        try {
            /*
            |--------------------------------------------------------------------------
            | Simpan gambar
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('image')) {
                $newImagePath = $request
                    ->file('image')
                    ->store('vehicles', 'public');

                $validated['image_path'] = $newImagePath;
            }

            unset($validated['image']);

            /*
            |--------------------------------------------------------------------------
            | Simpan kendaraan dan assignment
            |--------------------------------------------------------------------------
            */

            $vehicle = DB::transaction(function () use ($validated) {
                $driverId = $validated['driver_id'] ?? null;

                unset($validated['driver_id']);

                $validated['brand'] = trim($validated['brand']);
                $validated['model'] = trim($validated['model']);
                $validated['color'] = trim($validated['color']);
                $validated['type'] = trim($validated['type']);

                $driver = null;

                if ($driverId) {
                    $driver = User::query()
                        ->lockForUpdate()
                        ->findOrFail($driverId);

                    if ($driver->role !== 'driver') {
                        throw ValidationException::withMessages([
                            'driver_id' =>
                                'User yang dipilih bukan driver.',
                        ]);
                    }

                    if ($driver->driver_status !== 'active') {
                        throw ValidationException::withMessages([
                            'driver_id' =>
                                'Driver yang dipilih sedang nonaktif.',
                        ]);
                    }

                    $driverHasActiveAssignment =
                        VehicleAssignment::query()
                            ->where('driver_id', $driver->id)
                            ->where('status', 'active')
                            ->exists();

                    if ($driverHasActiveAssignment) {
                        throw ValidationException::withMessages([
                            'driver_id' =>
                                'Driver masih memiliki assignment aktif.',
                        ]);
                    }

                    $validated['status'] = 'in_use';
                }

                $vehicle = Vehicle::create($validated);

                if ($driver) {
                    VehicleAssignment::create([
                        'vehicle_id' => $vehicle->id,
                        'driver_id' => $driver->id,
                        'assigned_at' => now(),
                        'planned_return_at' => null,
                        'returned_at' => null,
                        'destination' => null,
                        'purpose' =>
                            'Assignment saat penambahan kendaraan',
                        'notes' => null,
                        'status' => 'active',
                    ]);
                }

                return $vehicle;
            });
        } catch (Throwable $exception) {
            /*
            |--------------------------------------------------------------------------
            | Hapus gambar apabila database gagal menyimpan
            |--------------------------------------------------------------------------
            */

            if (
                $newImagePath &&
                Storage::disk('public')->exists($newImagePath)
            ) {
                Storage::disk('public')->delete($newImagePath);
            }

            throw $exception;
        }

        return redirect()
            ->route('admin.vehicles.show', $vehicle)
            ->with(
                'success',
                $request->filled('driver_id')
                    ? 'Kendaraan berhasil ditambahkan dan driver berhasil di-assign.'
                    : 'Kendaraan berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail kendaraan.
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle->load([
            'activeAssignment.driver',
            'assignments.driver',
            'maintenanceLogs',
            'dailyLogs.driver',
        ]);

        return view(
            'admin.vehicles.show',
            compact('vehicle')
        );
    }

    /**
     * Menampilkan form edit kendaraan.
     */
    public function edit(Vehicle $vehicle)
    {
        return view(
            'admin.vehicles.edit',
            compact('vehicle')
        );
    }

    /**
     * Memperbarui data kendaraan.
     */
    public function update(
        Request $request,
        Vehicle $vehicle
    ) {
        $request->merge([
            'plate_number' => strtoupper(
                trim((string) $request->input('plate_number'))
            ),
        ]);

        $validated = $request->validate([
            'plate_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('vehicles', 'plate_number')
                    ->ignore($vehicle->id),
            ],

            'brand' => [
                'required',
                'string',
                'max:100',
            ],

            'model' => [
                'required',
                'string',
                'max:100',
            ],

            'year' => [
                'required',
                'integer',
                'min:1900',
                'max:' . (date('Y') + 1),
            ],

            'color' => [
                'required',
                'string',
                'max:50',
            ],

            'type' => [
                'required',
                'string',
                'max:100',
            ],

            'fuel_type' => [
                'required',
                Rule::in([
                    'gasoline',
                    'diesel',
                    'electric',
                    'hybrid',
                ]),
            ],

            'transmission' => [
                'required',
                Rule::in([
                    'manual',
                    'automatic',
                ]),
            ],

            'status' => [
                'required',
                Rule::in([
                    'available',
                    'in_use',
                    'maintenance',
                ]),
            ],

            'service_date' => [
                'nullable',
                'date',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ], [
            'image.image' =>
                'File yang diunggah harus berupa gambar.',

            'image.mimes' =>
                'Foto kendaraan harus berformat JPG, JPEG, PNG, atau WebP.',

            'image.max' =>
                'Ukuran foto kendaraan maksimal 2 MB.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validasi assignment aktif
        |--------------------------------------------------------------------------
        */

        $hasActiveAssignment = $vehicle
            ->assignments()
            ->where('status', 'active')
            ->exists();

        if (
            $hasActiveAssignment &&
            $validated['status'] === 'available'
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'status' =>
                        'Kendaraan masih memiliki assignment aktif.',
                ]);
        }

        if ($hasActiveAssignment) {
            $validated['status'] = 'in_use';
        }

        $validated['brand'] = trim($validated['brand']);
        $validated['model'] = trim($validated['model']);
        $validated['color'] = trim($validated['color']);
        $validated['type'] = trim($validated['type']);

        $oldImagePath = $vehicle->image_path;
        $newImagePath = null;

        try {
            /*
            |--------------------------------------------------------------------------
            | Simpan gambar baru
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('image')) {
                $newImagePath = $request
                    ->file('image')
                    ->store('vehicles', 'public');

                $validated['image_path'] = $newImagePath;
            }

            unset($validated['image']);

            DB::transaction(function () use (
                $vehicle,
                $validated
            ) {
                $vehicle->update($validated);
            });
        } catch (Throwable $exception) {
            /*
            |--------------------------------------------------------------------------
            | Hapus gambar baru jika update database gagal
            |--------------------------------------------------------------------------
            */

            if (
                $newImagePath &&
                Storage::disk('public')->exists($newImagePath)
            ) {
                Storage::disk('public')->delete($newImagePath);
            }

            throw $exception;
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus gambar lama setelah update berhasil
        |--------------------------------------------------------------------------
        */

        if (
            $newImagePath &&
            $oldImagePath &&
            $oldImagePath !== $newImagePath &&
            Storage::disk('public')->exists($oldImagePath)
        ) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return redirect()
            ->route('admin.vehicles.show', $vehicle)
            ->with(
                'success',
                'Data kendaraan berhasil diperbarui.'
            );
    }

    /**
     * Menghapus kendaraan.
     */
    public function destroy(Vehicle $vehicle)
    {
        $imagePath = $vehicle->image_path;

        /*
        |--------------------------------------------------------------------------
        | Hapus database dahulu
        |--------------------------------------------------------------------------
        */

        $vehicle->delete();

        /*
        |--------------------------------------------------------------------------
        | Hapus file setelah database berhasil
        |--------------------------------------------------------------------------
        */

        if (
            $imagePath &&
            Storage::disk('public')->exists($imagePath)
        ) {
            Storage::disk('public')->delete($imagePath);
        }

        return redirect()
            ->route('admin.vehicles.index')
            ->with(
                'success',
                'Kendaraan berhasil dihapus.'
            );
    }
}