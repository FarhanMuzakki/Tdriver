<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ExpenseReceipt extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'daily_log_id',
        'type',
        'amount',
        'file_path',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public function dailyLog(): BelongsTo
    {
        return $this->belongsTo(
            DailyLog::class,
            'daily_log_id',
            'id'
        );
    }

    protected function fileUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->file_path) {
                return null;
            }

            return Storage::disk('public')
                ->url($this->file_path);
        });
    }
}