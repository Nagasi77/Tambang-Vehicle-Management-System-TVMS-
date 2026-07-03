<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_driver',
        'status',
    ];

    /**
     * Bookings assigned to this driver.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
