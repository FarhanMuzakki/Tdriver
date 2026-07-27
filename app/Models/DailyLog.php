<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use App\Models\ExpenseReceipt;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class DailyLog extends Model
{
    use HasUuids;

    protected $fillable = [
        'driver_id',
        'vehicle_id',
        'log_date',
        'start_time',
        'end_time',
        'destination',
        'purpose',
        'start_odometer',
        'end_odometer',
        'fuel_cost',
        'toll_cost',
        'parking_cost',
        'notes',
    ];

    protected $casts = [
        'log_date' => 'date',
        'start_odometer' => 'integer',
        'end_odometer' => 'integer',
        'fuel_cost' => 'decimal:2',
        'toll_cost' => 'decimal:2',
        'parking_cost' => 'decimal:2',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function getDistanceAttribute(): ?int
    {
        if (
            $this->start_odometer === null ||
            $this->end_odometer === null
        ) {
            return null;
        }

        return $this->end_odometer - $this->start_odometer;
    }

    public function getTotalCostAttribute(): float
    {
        return (float) $this->fuel_cost
            + (float) $this->toll_cost
            + (float) $this->parking_cost;
    }
   public function receipts()
{
    return $this->hasMany(
        ExpenseReceipt::class,
        'daily_log_id'
    );
}
public function getReceiptTotalAttribute(): float
{
    return (float) $this->receipts->sum('amount');
}

}