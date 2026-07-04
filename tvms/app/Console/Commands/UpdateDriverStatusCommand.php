<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateDriverStatusCommand extends Command
{
    protected $signature   = 'app:update-driver-status';
    protected $description = 'Update driver status to "tersedia" when their booking tanggal_selesai has passed.';

    public function handle(): int
    {
        $today = Carbon::today();

        // Find all finalized bookings whose end date is in the past
        $bookings = Booking::with('driver')
            ->where('status_pembokingan', 'disetujui_final')
            ->where('tanggal_selesai', '<', $today)
            ->get();

        $count = 0;

        foreach ($bookings as $booking) {
            if ($booking->driver && $booking->driver->status === 'bertugas') {
                $booking->driver->update(['status' => 'tersedia']);
                $count++;
                $this->line("Driver [{$booking->driver->nama_driver}] (Booking #{$booking->id}) → tersedia");
            }
        }

        $this->info("Done. {$count} driver(s) updated to tersedia.");

        return Command::SUCCESS;
    }
}
