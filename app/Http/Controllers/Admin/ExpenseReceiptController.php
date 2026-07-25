<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use App\Models\ExpenseReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ExpenseReceiptController extends Controller
{
    public function store(Request $request, DailyLog $dailyLog)
    {
        $validated = $request->validate([
            'type' => [
                'required',
                Rule::in([
                    'fuel',
                    'toll',
                    'parking',
                    'other',
                ]),
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
                'max:999999999999',
            ],

            'receipt_file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'type.required' => 'Jenis struk wajib dipilih.',
            'type.in' => 'Jenis struk tidak valid.',

            'amount.required' => 'Nominal wajib diisi.',
            'amount.numeric' => 'Nominal harus berupa angka.',
            'amount.min' => 'Nominal tidak boleh kurang dari nol.',

            'receipt_file.required' => 'File struk wajib diunggah.',
            'receipt_file.mimes' => 'Struk harus berupa JPG, JPEG, PNG, atau PDF.',
            'receipt_file.max' => 'Ukuran struk maksimal 5 MB.',
        ]);

        $file = $request->file('receipt_file');

        $path = $file->store(
            'receipts/' . $dailyLog->id,
            'public'
        );

        ExpenseReceipt::create([
            'daily_log_id' => $dailyLog->id,
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'notes' => $validated['notes'] ?? null,
        ]);

        $this->syncDailyLogCost($dailyLog);

        return back()->with(
            'success',
            'Struk berhasil diunggah.'
        );
    }

    public function destroy(ExpenseReceipt $receipt)
    {
        $dailyLog = $receipt->dailyLog;

        if (
            $receipt->file_path &&
            Storage::disk('public')->exists($receipt->file_path)
        ) {
            Storage::disk('public')->delete($receipt->file_path);
        }

        $receipt->delete();

        if ($dailyLog) {
            $this->syncDailyLogCost($dailyLog);
        }

        return back()->with(
            'success',
            'Struk berhasil dihapus.'
        );
    }

    private function syncDailyLogCost(DailyLog $dailyLog): void
    {
        $dailyLog->update([
            'fuel_cost' => $dailyLog->receipts()
                ->where('type', 'fuel')
                ->sum('amount'),

            'toll_cost' => $dailyLog->receipts()
                ->where('type', 'toll')
                ->sum('amount'),

            'parking_cost' => $dailyLog->receipts()
                ->where('type', 'parking')
                ->sum('amount'),
        ]);
    }
}