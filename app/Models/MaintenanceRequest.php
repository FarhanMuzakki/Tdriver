<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
class MaintenanceRequest extends Model
{
    use HasUuids;

    protected $fillable = [
        'driver_id',
        'vehicle_id',
        'issue_type',
        'description',
        'priority',
        'status',
        'requested_at',
        'admin_notes',
        'approved_at',
        'rejected_at',
        'completed_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'driver_id'
        );
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
    public const PRIORITY_LOW = 'low';
public const PRIORITY_MEDIUM = 'medium';
public const PRIORITY_HIGH = 'high';
public const PRIORITY_URGENT = 'urgent';

public const STATUS_PENDING = 'pending';
public const STATUS_APPROVED = 'approved';
public const STATUS_REJECTED = 'rejected';
public const STATUS_COMPLETED = 'completed';
public function maintenanceLog(): HasOne
{
    return $this->hasOne(
        MaintenanceLog::class,
        'maintenance_request_id'
    );
}
}