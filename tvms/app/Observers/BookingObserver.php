<?php
namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingObserver
{
    public function created(Booking $booking): void
    {
        try {
            $user = Auth::user();
            ActivityLog::create([
                'user_id'       => $user?->id,
                'aksi'          => 'booking.created',
                'deskripsi'     => 'Admin ' . ($user?->name ?? 'Sistem') . ' menginput pemesanan ID #' . $booking->id,
                'loggable_type' => Booking::class,
                'loggable_id'   => $booking->id,
                'data_lama'     => null,
                'data_baru'     => json_encode(['status_pembokingan' => $booking->status_pembokingan]),
            ]);
        } catch (\Throwable $e) {
            Log::error('BookingObserver::created failed', [
                'booking_id' => $booking->id,
                'error'      => $e->getMessage(),
            ]);
        }
    }

    public function updated(Booking $booking): void
    {
        if (!$booking->isDirty('status_pembokingan')) {
            return;
        }

        try {
            $user = Auth::user();
            ActivityLog::create([
                'user_id'       => $user?->id,
                'aksi'          => 'booking.status_changed',
                'deskripsi'     => 'Status pemesanan ID #' . $booking->id . ' berubah dari ' .
                                   $booking->getOriginal('status_pembokingan') . ' ke ' .
                                   $booking->status_pembokingan,
                'loggable_type' => Booking::class,
                'loggable_id'   => $booking->id,
                'data_lama'     => json_encode(['status_pembokingan' => $booking->getOriginal('status_pembokingan')]),
                'data_baru'     => json_encode(['status_pembokingan' => $booking->status_pembokingan]),
            ]);
        } catch (\Throwable $e) {
            Log::error('BookingObserver::updated failed', [
                'booking_id' => $booking->id,
                'error'      => $e->getMessage(),
            ]);
        }
    }
}
