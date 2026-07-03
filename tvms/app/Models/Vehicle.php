<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'plat_nomor',
        'jenis',
        'status_kepemilikan',
    ];

    /**
     * Bookings that use this vehicle.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Service records for this vehicle.
     */
    public function vehicleServices(): HasMany
    {
        return $this->hasMany(VehicleService::class);
    }
}
