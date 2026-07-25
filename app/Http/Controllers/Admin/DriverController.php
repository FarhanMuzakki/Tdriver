<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $drivers = User::query()
            ->where('role', 'driver')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'ilike', "%{$search}%")
                        ->orWhere('email', 'ilike', "%{$search}%")
                        ->orWhere('phone', 'ilike', "%{$search}%")
                        ->orWhere('license_number', 'ilike', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('admin.drivers.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateDriver($request);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo_path'] = $request
                ->file('profile_photo')
                ->store('drivers', 'public');
        }

        unset($validated['profile_photo']);

        $validated['role'] = 'driver';
        $validated['password'] = Hash::make(
            $validated['password']
        );

        $driver = User::create($validated);

        return redirect()
            ->route('admin.drivers.show', $driver)
            ->with(
                'success',
                'Driver berhasil ditambahkan.'
            );
    }

    public function show(User $driver)
    {
        abort_unless($driver->role === 'driver', 404);

        $driver->load([
            'activeAssignment.vehicle',
        ]);

        $assignments = $driver->assignments()
            ->with('vehicle')
            ->latest('assigned_at')
            ->paginate(10);

        return view(
            'admin.drivers.show',
            compact('driver', 'assignments')
        );
    }

    public function edit(User $driver)
    {
        abort_unless($driver->role === 'driver', 404);

        return view(
            'admin.drivers.edit',
            compact('driver')
        );
    }

    public function update(
        Request $request,
        User $driver
    ) {
        abort_unless($driver->role === 'driver', 404);

        $validated = $this->validateDriver(
            $request,
            $driver
        );

        /*
        |--------------------------------------------------------------------------
        | Update password
        |--------------------------------------------------------------------------
        */

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make(
                $validated['password']
            );
        } else {
            unset($validated['password']);
        }

        /*
        |--------------------------------------------------------------------------
        | Update foto driver
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('profile_photo')) {
            if (
                $driver->profile_photo_path &&
                Storage::disk('public')->exists(
                    $driver->profile_photo_path
                )
            ) {
                Storage::disk('public')->delete(
                    $driver->profile_photo_path
                );
            }

            $validated['profile_photo_path'] = $request
                ->file('profile_photo')
                ->store('drivers', 'public');
        }

        unset($validated['profile_photo']);

        $validated['role'] = 'driver';

        $driver->update($validated);

        return redirect()
            ->route('admin.drivers.show', $driver)
            ->with(
                'success',
                'Data driver berhasil diperbarui.'
            );
    }

    public function destroy(User $driver)
    {
        abort_unless($driver->role === 'driver', 404);

        $hasActiveAssignment = $driver
            ->assignments()
            ->where('status', 'active')
            ->exists();

        if ($hasActiveAssignment) {
            return back()->with(
                'error',
                'Driver tidak dapat dihapus karena masih memiliki assignment aktif.'
            );
        }

        if (
            $driver->profile_photo_path &&
            Storage::disk('public')->exists(
                $driver->profile_photo_path
            )
        ) {
            Storage::disk('public')->delete(
                $driver->profile_photo_path
            );
        }

        $driver->delete();

        return redirect()
            ->route('admin.drivers.index')
            ->with(
                'success',
                'Driver berhasil dihapus.'
            );
    }

    private function validateDriver(
        Request $request,
        ?User $driver = null
    ): array {
        $passwordRules = $driver
            ? [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ]
            : [
                'required',
                'string',
                'min:8',
                'confirmed',
            ];

        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($driver?->id),
            ],

            'password' => $passwordRules,

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'license_number' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique(
                    'users',
                    'license_number'
                )->ignore($driver?->id),
            ],

            'license_expiry' => [
                'nullable',
                'date',
            ],

            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'driver_status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],

            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ], [
            'profile_photo.image' =>
                'File foto driver harus berupa gambar.',

            'profile_photo.mimes' =>
                'Foto driver harus berformat JPG, JPEG, PNG, atau WebP.',

            'profile_photo.max' =>
                'Ukuran foto driver maksimal 2 MB.',
        ]);
    }
}