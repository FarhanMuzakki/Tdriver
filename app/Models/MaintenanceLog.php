<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class MaintenanceLog extends Model
{
    use HasUuids;

    protected $fillable = [
        'vehicle_id',
    'maintenance_request_id',
    'service_type',
    'service_date',
    'workshop',
    'cost',
    'odometer',
    'notes',
    'status',
    'completed_at',
    ];

    protected $casts = [
        'service_date' => 'date',
        'completed_at' => 'datetime',
        'cost' => 'decimal:2',
        'odometer' => 'integer',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
    public function maintenanceRequest(): BelongsTo
{
    return $this->belongsTo(MaintenanceRequest::class);
}
}
