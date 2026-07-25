<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use App\Models\Vehicle;
use App\Models\VehicleAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class DailyLogController extends Controller
{
    /**
     * Riwayat log milik driver login.
     */
    public function index(Request $request)
    {
        $driverId = Auth::id();

        $search = trim((string) $request->input('search'));
        $date = $request->input('date');

        $dailyLogs = DailyLog::query()
            ->with([
                'vehicle',
                'receipts',
            ])
            ->where('driver_id', $driverId)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('destination', 'ilike', "%{$search}%")
                        ->orWhere('purpose', 'ilike', "%{$search}%")
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
            'driver.logs.index',
            compact('dailyLogs')
        );
    }

    /**
     * Form tambah log.
     */
    public function create()
    {
        $driverId = Auth::id();

        $assignments = VehicleAssignment::query()
            ->with('vehicle')
            ->where('driver_id', $driverId)
            ->where('status', 'active')
            ->whereHas('vehicle', function ($query) {
                $query->where('status', '!=', 'maintenance');
            })
            ->latest('assigned_at')
            ->get();

        return view(
            'driver.logs.create',
            compact('assignments')
        );
    }

    /**
     * Simpan log perjalanan.
     */
    public function store(Request $request)
    {
        $driverId = Auth::id();

        $validated = $request->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        $this->validateAssignment(
            $driverId,
            $validated['vehicle_id']
        );

        $vehicle = Vehicle::query()
            ->findOrFail($validated['vehicle_id']);

        if ($vehicle->status === 'maintenance') {
            throw ValidationException::withMessages([
                'vehicle_id' => 'Kendaraan sedang maintenance.',
            ]);
        }

        $dailyLog = DailyLog::create([
            'driver_id' => $driverId,
            'vehicle_id' => $validated['vehicle_id'],
            'log_date' => $validated['log_date'],
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'destination' => $validated['destination'],
            'purpose' => $validated['purpose'],
            'start_odometer' => $validated['start_odometer'],
            'end_odometer' => $validated['end_odometer'],
            'fuel_cost' => $validated['fuel_cost'] ?? 0,
            'toll_cost' => $validated['toll_cost'] ?? 0,
            'parking_cost' => $validated['parking_cost'] ?? 0,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('driver.logs.show', $dailyLog)
            ->with(
                'success',
                'Log perjalanan berhasil disimpan.'
            );
    }

    /**
     * Detail log milik driver login.
     */
    public function show(DailyLog $dailyLog)
    {
        $this->authorizeOwner($dailyLog);

        $dailyLog->load([
            'vehicle',
            'receipts',
        ]);

        return view(
            'driver.logs.show',
            compact('dailyLog')
        );
    }

    /**
     * Edit log milik driver login.
     */
    public function edit(DailyLog $dailyLog)
    {
        $this->authorizeOwner($dailyLog);

        $dailyLog->load([
            'vehicle',
            'receipts',
        ]);

        $driverId = Auth::id();

        $assignments = VehicleAssignment::query()
            ->with('vehicle')
            ->where('driver_id', $driverId)
            ->where('status', 'active')
            ->latest('assigned_at')
            ->get();

        return view(
            'driver.logs.edit',
            compact(
                'dailyLog',
                'assignments'
            )
        );
    }

    /**
     * Update log milik driver login.
     */
    public function update(
        Request $request,
        DailyLog $dailyLog
    ) {
        $this->authorizeOwner($dailyLog);

        $driverId = Auth::id();

        $validated = $request->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        $this->validateAssignment(
            $driverId,
            $validated['vehicle_id']
        );

        $vehicle = Vehicle::query()
            ->findOrFail($validated['vehicle_id']);

        if ($vehicle->status === 'maintenance') {
            throw ValidationException::withMessages([
                'vehicle_id' => 'Kendaraan sedang maintenance.',
            ]);
        }

        $dailyLog->update([
            'vehicle_id' => $validated['vehicle_id'],
            'log_date' => $validated['log_date'],
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'destination' => $validated['destination'],
            'purpose' => $validated['purpose'],
            'start_odometer' => $validated['start_odometer'],
            'end_odometer' => $validated['end_odometer'],
            'fuel_cost' => $validated['fuel_cost'] ?? 0,
            'toll_cost' => $validated['toll_cost'] ?? 0,
            'parking_cost' => $validated['parking_cost'] ?? 0,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('driver.logs.show', $dailyLog)
            ->with(
                'success',
                'Log perjalanan berhasil diperbarui.'
            );
    }

    /**
     * Hapus log milik sendiri.
     */
    public function destroy(DailyLog $dailyLog)
    {
        $this->authorizeOwner($dailyLog);

        $dailyLog->delete();

        return redirect()
            ->route('driver.logs.index')
            ->with(
                'success',
                'Log perjalanan berhasil dihapus.'
            );
    }

    /**
     * Aturan validasi create dan update.
     */
    private function validationRules(): array
    {
        return [
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

    /**
     * Pesan validasi.
     */
    private function validationMessages(): array
    {
        return [
            'vehicle_id.required' =>
                'Kendaraan wajib dipilih.',

            'vehicle_id.uuid' =>
                'ID kendaraan tidak valid.',

            'vehicle_id.exists' =>
                'Kendaraan tidak ditemukan.',

            'log_date.required' =>
                'Tanggal perjalanan wajib diisi.',

            'destination.required' =>
                'Tujuan wajib diisi.',

            'purpose.required' =>
                'Keperluan wajib diisi.',

            'start_odometer.required' =>
                'Kilometer awal wajib diisi.',

            'end_odometer.required' =>
                'Kilometer akhir wajib diisi.',

            'end_odometer.gte' =>
                'Kilometer akhir tidak boleh lebih kecil dari kilometer awal.',

            'end_time.after_or_equal' =>
                'Jam selesai tidak boleh lebih awal dari jam mulai.',
        ];
    }

    /**
     * Pastikan kendaraan ditugaskan ke driver login.
     */
    private function validateAssignment(
        string $driverId,
        string $vehicleId
    ): void {
        $hasActiveAssignment = VehicleAssignment::query()
            ->where('driver_id', $driverId)
            ->where('vehicle_id', $vehicleId)
            ->where('status', 'active')
            ->exists();

        if (! $hasActiveAssignment) {
            throw ValidationException::withMessages([
                'vehicle_id' =>
                    'Kendaraan ini tidak sedang ditugaskan kepada Anda.',
            ]);
        }
    }

    /**
     * Proteksi kepemilikan data.
     */
    private function authorizeOwner(DailyLog $dailyLog): void
    {
        abort_unless(
            (string) $dailyLog->driver_id === (string) Auth::id(),
            403,
            'Anda tidak memiliki akses ke log ini.'
        );
    }
}