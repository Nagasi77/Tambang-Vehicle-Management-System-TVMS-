<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'approver_level_1_id',
        'approver_level_2_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'keperluan',
        'konsumsi_bbm',
        'status_pembokingan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_mulai'    => 'date',
        'tanggal_selesai'  => 'date',
        'status_pembokingan' => 'string',
    ];

    /**
     * The vehicle used in this booking.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * The driver assigned to this booking.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * The user assigned as Approver Level 1 for this booking.
     */
    public function approverLevel1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_level_1_id');
    }

    /**
     * The user assigned as Approver Level 2 for this booking.
     */
    public function approverLevel2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_level_2_id');
    }

    /**
     * Approval records for this booking.
     */
    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class);
    }
}
