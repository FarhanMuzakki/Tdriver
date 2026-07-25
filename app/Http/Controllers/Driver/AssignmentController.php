<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use App\Models\ExpenseReceipt;
use App\Models\VehicleAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AssignmentController extends Controller
{
    public function edit(VehicleAssignment $assignment)
    {
        $this->authorizeAssignment($assignment);

        $dailyLog = DailyLog::query()
            ->with('receipts')
            ->where('driver_id', Auth::id())
            ->where('vehicle_id', $assignment->vehicle_id)
            ->whereDate('log_date', today())
            ->first();

        return view(
            'driver.assignments.edit',
            compact('assignment', 'dailyLog')
        );
    }

public function update(Request $request, VehicleAssignment $assignment)
{
    $this->authorizeAssignment($assignment);

    if ($request->input('action') === 'upload_receipt') {
        $validated = $request->validate([
            'receipt_type' => ['required', 'in:fuel,toll,parking,other'],
            'receipt_amount' => ['required', 'numeric', 'min:0'],
            'receipt' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        DB::transaction(function () use ($request, $assignment, $validated) {
            $dailyLog = DailyLog::firstOrCreate(
                [
                    'driver_id' => Auth::id(),
                    'vehicle_id' => $assignment->vehicle_id,
                    'log_date' => today()->toDateString(),
                ],
                [
                    'start_time' => null,
                    'end_time' => null,
                    'destination' => $assignment->destination ?? '-',
                    'purpose' => $assignment->purpose ?? '-',
                    'start_odometer' => 0,
                    'end_odometer' => 0,
                    'fuel_cost' => 0,
                    'toll_cost' => 0,
                    'parking_cost' => 0,
                    'notes' => null,
                ]
            );

            $path = $request
                ->file('receipt')
                ->store('receipts', 'public');

            ExpenseReceipt::create([
                'daily_log_id' => $dailyLog->id,
                'type' => $validated['receipt_type'],
                'amount' => $validated['receipt_amount'],
                'file_path' => $path,
            ]);

            $this->syncDailyLogCosts($dailyLog);
        });

        return back()->with('success', 'Struk berhasil diunggah.');
    }

    $validated = $request->validate([
        'start_time' => ['nullable', 'date_format:H:i'],
        'end_time' => ['nullable', 'date_format:H:i'],
        'start_odometer' => ['required', 'integer', 'min:0'],
        'end_odometer' => ['required', 'integer', 'gte:start_odometer'],
        'notes' => ['nullable', 'string', 'max:2000'],
    ]);

    $dailyLog = DailyLog::updateOrCreate(
        [
            'driver_id' => Auth::id(),
            'vehicle_id' => $assignment->vehicle_id,
            'log_date' => today()->toDateString(),
        ],
        [
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'destination' => $assignment->destination ?? '-',
            'purpose' => $assignment->purpose ?? '-',
            'start_odometer' => $validated['start_odometer'],
            'end_odometer' => $validated['end_odometer'],
            'notes' => $validated['notes'] ?? null,
        ]
    );

    $this->syncDailyLogCosts($dailyLog);

    return redirect()
        ->route('driver.dashboard')
        ->with('success', 'Assignment berhasil diperbarui.');
}

    private function syncDailyLogCosts(DailyLog $dailyLog): void
    {
        $dailyLog->load('receipts');

        $dailyLog->update([
            'fuel_cost' => $dailyLog->receipts
                ->where('type', 'fuel')
                ->sum('amount'),

            'toll_cost' => $dailyLog->receipts
                ->where('type', 'toll')
                ->sum('amount'),

            'parking_cost' => $dailyLog->receipts
                ->where('type', 'parking')
                ->sum('amount'),
        ]);
    }

    private function authorizeAssignment(VehicleAssignment $assignment): void
    {
        if (
            $assignment->driver_id !== Auth::id() ||
            $assignment->status !== 'active'
        ) {
            throw ValidationException::withMessages([
                'assignment' => 'Assignment tidak dapat diakses.',
            ]);
        }
    }
}