<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class VehicleAssignment extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
    'vehicle_id',
    'driver_id',
    'assigned_at',
    'planned_return_at',
    'returned_at',
    'destination',
    'purpose',
    'notes',
    'status',
];


protected $casts = [
    'assigned_at' => 'datetime',
    'planned_return_at' => 'datetime',
    'returned_at' => 'datetime',
];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}