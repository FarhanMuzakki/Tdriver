<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DailyLogController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $date = $request->input('date');

        $dailyLogs = DailyLog::query()
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
                $date,
                fn ($query) => $query->whereDate('log_date', $date)
            )
            ->latest('log_date')
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.logs.index',
            compact('dailyLogs')
        );
    }

    public function create()
    {
        $drivers = User::query()
            ->where('role', 'driver')
            ->where('driver_status', 'active')
            ->orderBy('name')
            ->get();

        $vehicles = Vehicle::query()
            ->whereNot('status', 'maintenance')
            ->orderBy('plate_number')
            ->get();

        return view(
            'admin.logs.create',
            compact('drivers', 'vehicles')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->rules(),
            $this->messages()
        );

        $this->validateDriverVehicleRelation($validated);

        DailyLog::create($validated);

        return redirect()
            ->route('admin.logs.index')
            ->with('success', 'Log perjalanan berhasil ditambahkan.');
    }

    public function edit(DailyLog $dailyLog)
{
    $dailyLog->load('receipts');

    $drivers = User::query()
        ->where('role', 'driver')
        ->orderBy('name')
        ->get();

    $vehicles = Vehicle::query()
        ->orderBy('plate_number')
        ->get();

    return view(
        'admin.logs.edit',
        compact(
            'dailyLog',
            'drivers',
            'vehicles'
        )
    );
}

    public function update(
        Request $request,
        DailyLog $dailyLog
    ) {
        $validated = $request->validate(
            $this->rules(),
            $this->messages()
        );

        $this->validateDriverVehicleRelation($validated);

        $dailyLog->update($validated);

        return redirect()
            ->route('admin.logs.index')
            ->with('success', 'Log perjalanan berhasil diperbarui.');
    }

    public function destroy(DailyLog $dailyLog)
    {
        $dailyLog->delete();

        return redirect()
            ->route('admin.logs.index')
            ->with('success', 'Log perjalanan berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'driver_id' => [
                'required',
                'uuid',
                Rule::exists('users', 'id')
                    ->where(fn ($query) => $query->where('role', 'driver')),
            ],

            'vehicle_id' => [
                'required',
                'uuid',
                'exists:vehicles,id',
            ],

            'log_date' => [
                'required',
                'date',
            ],

            'start_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'end_time' => [
                'nullable',
                'date_format:H:i',
                'after_or_equal:start_time',
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

            'start_odometer' => [
                'required',
                'integer',
                'min:0',
            ],

            'end_odometer' => [
                'required',
                'integer',
                'gte:start_odometer',
            ],

            'fuel_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'toll_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'parking_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    private function messages(): array
    {
        return [
            'driver_id.required' => 'Driver wajib dipilih.',
            'vehicle_id.required' => 'Kendaraan wajib dipilih.',

            'log_date.required' => 'Tanggal perjalanan wajib diisi.',

            'destination.required' => 'Tujuan wajib diisi.',
            'purpose.required' => 'Keperluan wajib diisi.',

            'start_odometer.required' => 'Kilometer awal wajib diisi.',
            'end_odometer.required' => 'Kilometer akhir wajib diisi.',
            'end_odometer.gte' => 'Kilometer akhir tidak boleh lebih kecil dari kilometer awal.',

            'end_time.after_or_equal' => 'Jam selesai tidak boleh lebih awal dari jam mulai.',
        ];
    }

    private function validateDriverVehicleRelation(array $validated): void
    {
        $driver = User::query()
            ->where('id', $validated['driver_id'])
            ->where('role', 'driver')
            ->first();

        if (!$driver) {
            throw ValidationException::withMessages([
                'driver_id' => 'Driver tidak ditemukan.',
            ]);
        }

        $vehicle = Vehicle::query()
            ->find($validated['vehicle_id']);

        if (!$vehicle) {
            throw ValidationException::withMessages([
                'vehicle_id' => 'Kendaraan tidak ditemukan.',
            ]);
        }

        if ($vehicle->status === 'maintenance') {
            throw ValidationException::withMessages([
                'vehicle_id' => 'Kendaraan sedang maintenance.',
            ]);
        }

        /*
         * Aktifkan validasi ini setelah kolom
         * responsible_driver_id sudah dibuat.
         */
        if (
            $vehicle->responsible_driver_id &&
            $vehicle->responsible_driver_id !== $driver->id
        ) {
            throw ValidationException::withMessages([
                'vehicle_id' => 'Kendaraan bukan tanggung jawab driver yang dipilih.',
            ]);
        }
    }
}