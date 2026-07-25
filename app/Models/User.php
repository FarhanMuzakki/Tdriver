<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\DailyLog;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'phone',
    'license_number',
    'license_expiry',
    'address',
    'driver_status',
    'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'license_expiry' => 'date',
        ];
        }
        protected function profilePhotoUrl(): Attribute
{
    return Attribute::get(function () {
        if (! $this->profile_photo_path) {
            return null;
        }

        return Storage::url($this->profile_photo_path);
    });
}
public function assignments()
{
    return $this->hasMany(VehicleAssignment::class, 'driver_id');
}
public function dailyLogs()
{
    return $this->hasMany(DailyLog::class, 'driver_id');
}
public function activeAssignment()
{
    return $this->hasOne(VehicleAssignment::class, 'driver_id')
        ->where('status', 'active')
        ->orderByDesc('assigned_at');
}
public function logs()
{
    return $this->hasMany(DailyLog::class, 'driver_id');
}
public function maintenanceRequests(): HasMany
{
    return $this->hasMany(
        MaintenanceRequest::class,
        'driver_id'
    );
}
}
