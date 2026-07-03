<?php

namespace App\Services;

use App\Models\Approval;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * Check if a vehicle has a conflicting booking in the given date range.
     * Conflict: existing booking with status 'disetujui_level_1' or 'disetujui_final'
     * that overlaps with the requested range.
     * Overlap: new_start < existing_end AND new_end > existing_start
     * (back-to-back dates are NOT considered a conflict)
     */
    public function checkVehicleConflict(
        int $vehicleId,
        Carbon $start,
        Carbon $end,
        ?int $excludeBookingId = null
    ): ?Booking {
        $query = Booking::where('vehicle_id', $vehicleId)
            ->whereIn('status_pembokingan', ['disetujui_level_1', 'disetujui_final'])
            ->where('tanggal_mulai', '<', $end)
            ->where('tanggal_selesai', '>', $start);

        if ($excludeBookingId !== null) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->first();
    }

    /**
     * Check if a driver has a conflicting booking in the given date range.
     * Conflict: existing booking with status 'disetujui_level_1' or 'disetujui_final'
     * that overlaps with the requested range.
     * Overlap: new_start < existing_end AND new_end > existing_start
     * (back-to-back dates are NOT considered a conflict)
     */
    public function checkDriverConflict(
        int $driverId,
        Carbon $start,
        Carbon $end,
        ?int $excludeBookingId = null
    ): ?Booking {
        $query = Booking::where('driver_id', $driverId)
            ->whereIn('status_pembokingan', ['disetujui_level_1', 'disetujui_final'])
            ->where('tanggal_mulai', '<', $end)
            ->where('tanggal_selesai', '>', $start);

        if ($excludeBookingId !== null) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->first();
    }

    /**
     * Create a new booking along with its two approval records atomically.
     *
     * Wraps Booking creation + two Approval records in a DB transaction so
     * that a failure at any point rolls back all changes.
     *
     * @param  array<string, mixed>  $data
     */
    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            /** @var Booking $booking */
            $booking = Booking::create([
                'vehicle_id'          => $data['vehicle_id'],
                'driver_id'           => $data['driver_id'],
                'approver_level_1_id' => $data['approver_level_1_id'],
                'approver_level_2_id' => $data['approver_level_2_id'],
                'tanggal_mulai'       => $data['tanggal_mulai'],
                'tanggal_selesai'     => $data['tanggal_selesai'],
                'keperluan'           => $data['keperluan'],
                'konsumsi_bbm'        => $data['konsumsi_bbm'] ?? null,
                'status_pembokingan'  => 'pending',
            ]);

            // Create Approval Level 1
            Approval::create([
                'booking_id'  => $booking->id,
                'approver_id' => $data['approver_level_1_id'],
                'level'       => 1,
                'status'      => 'pending',
            ]);

            // Create Approval Level 2
            Approval::create([
                'booking_id'  => $booking->id,
                'approver_id' => $data['approver_level_2_id'],
                'level'       => 2,
                'status'      => 'pending',
            ]);

            return $booking;
        });
    }

    /**
     * Cancel a booking by setting its status to 'dibatalkan'.
     *
     * @throws \InvalidArgumentException When the booking has already been finally approved.
     */
    public function cancelBooking(Booking $booking): void
    {
        if ($booking->status_pembokingan === 'disetujui_final') {
            throw new \InvalidArgumentException(
                'Booking yang sudah disetujui final tidak dapat dibatalkan.'
            );
        }

        $booking->update(['status_pembokingan' => 'dibatalkan']);
    }
}
