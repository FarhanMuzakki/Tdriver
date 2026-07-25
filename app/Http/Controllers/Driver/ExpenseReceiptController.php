<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use App\Models\ExpenseReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExpenseReceiptController extends Controller
{
    public function store(
        Request $request,
        DailyLog $dailyLog
    ) {
        $this->authorizeOwner($dailyLog);

        $validated = $request->validate([
            'type' => [
                'required',
                'in:fuel,toll,parking,other',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'receipt' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
        ]);

        $path = $request
            ->file('receipt')
            ->store('receipts', 'public');

        try {
            DB::transaction(function () use (
                $dailyLog,
                $validated,
                $path
            ) {
                ExpenseReceipt::create([
                    'daily_log_id' => $dailyLog->id,
                    'type' => $validated['type'],
                    'amount' => $validated['amount'],
                    'file_path' => $path,
                ]);

                $this->syncDailyLogCosts($dailyLog);
            });
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($path);

            throw $e;
        }

        return redirect()
            ->route('driver.logs.show', $dailyLog)
            ->with(
                'success',
                'Struk berhasil diunggah dan nominal diperbarui.'
            );
    }

    public function destroy(ExpenseReceipt $receipt)
    {
        $receipt->load('dailyLog');

        $dailyLog = $receipt->dailyLog;

        $this->authorizeOwner($dailyLog);

        $path = $receipt->file_path;

        DB::transaction(function () use (
            $receipt,
            $dailyLog
        ) {
            $receipt->delete();

            $this->syncDailyLogCosts($dailyLog);
        });

        if ($path) {
            Storage::disk('public')->delete($path);
        }

        return redirect()
    ->route('driver.logs.edit', $dailyLog)
    ->withFragment('receipts')
    ->with(
        'success',
        'Struk berhasil dihapus dan nominal diperbarui.'
    );
    }

    private function syncDailyLogCosts(
        DailyLog $dailyLog
    ): void {
        $dailyLog->update([
            'fuel_cost' => $dailyLog
                ->receipts()
                ->where('type', 'fuel')
                ->sum('amount'),

            'toll_cost' => $dailyLog
                ->receipts()
                ->where('type', 'toll')
                ->sum('amount'),

            'parking_cost' => $dailyLog
                ->receipts()
                ->where('type', 'parking')
                ->sum('amount'),
        ]);
    }

    private function authorizeOwner(
        DailyLog $dailyLog
    ): void {
        abort_unless(
            (string) $dailyLog->driver_id
                === (string) Auth::id(),
            403,
            'Anda tidak memiliki akses ke log ini.'
        );
    }
}