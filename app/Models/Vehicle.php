<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Vehicle extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'plate_number',
        'brand',
        'model',
        'year',
        'color',
        'type',
        'fuel_type',
        'transmission',
        'status',
        'service_date',
        'image_path',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->image_path) {
                return null;
            }

            return Storage::disk('public')->url(
                $this->image_path
            );
        });
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(
            VehicleAssignment::class,
            'vehicle_id',
            'id'
        );
    }

    public function activeAssignment(): HasOne
    {
        return $this->hasOne(
            VehicleAssignment::class,
            'vehicle_id',
            'id'
        )
            ->where('status', 'active')
            ->orderByDesc('assigned_at');
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function dailyLogs(): HasMany
    {
        return $this->hasMany(DailyLog::class);
    }

}