<?php

namespace Database\Seeders;

use App\Models\Approval;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin      = User::where('email', 'admin@tvms.com')->first();
        $approver1  = User::where('email', 'approver1@tvms.com')->first();
        $approver2  = User::where('email', 'approver2@tvms.com')->first();

        // ------------------------------------------------------------------
        // Vehicles (6 kendaraan)
        // ------------------------------------------------------------------
        $vehicleData = [
            ['plat_nomor' => 'KB 1234 AB', 'jenis' => 'angkutan_orang',  'status_kepemilikan' => 'milik_sendiri'],
            ['plat_nomor' => 'KB 5678 CD', 'jenis' => 'angkutan_barang', 'status_kepemilikan' => 'milik_sendiri'],
            ['plat_nomor' => 'KB 9012 EF', 'jenis' => 'angkutan_barang', 'status_kepemilikan' => 'sewa'],
            ['plat_nomor' => 'KT 1111 AA', 'jenis' => 'angkutan_orang',  'status_kepemilikan' => 'sewa'],
            ['plat_nomor' => 'KT 2222 BB', 'jenis' => 'angkutan_barang', 'status_kepemilikan' => 'milik_sendiri'],
            ['plat_nomor' => 'KT 3333 CC', 'jenis' => 'angkutan_orang',  'status_kepemilikan' => 'milik_sendiri'],
        ];

        $vehicles = [];
        foreach ($vehicleData as $v) {
            $vehicles[] = Vehicle::updateOrCreate(['plat_nomor' => $v['plat_nomor']], $v);
        }

        // ------------------------------------------------------------------
        // Drivers (4 pengemudi)
        // ------------------------------------------------------------------
        $driverData = [
            ['nama_driver' => 'Budi Santoso',   'status' => 'tersedia'],
            ['nama_driver' => 'Agus Prasetyo',  'status' => 'tersedia'],
            ['nama_driver' => 'Hendra Wijaya',  'status' => 'tersedia'],
            ['nama_driver' => 'Slamet Raharjo', 'status' => 'tersedia'],
        ];

        $drivers = [];
        foreach ($driverData as $d) {
            $drivers[] = Driver::updateOrCreate(['nama_driver' => $d['nama_driver']], $d);
        }

        // ------------------------------------------------------------------
        // Bookings — mix of past (disetujui_final) for the chart,
        // plus some pending/in-progress for the stats cards
        // ------------------------------------------------------------------

        // Past finalized bookings — these drive the chart on the dashboard
        // KB 1234 AB used the most (5x), KB 5678 CD (3x), others varied
        $finalizedBookings = [
            // KB 1234 AB — 5 trips
            ['vehicle' => 0, 'driver' => 0, 'start' => '-90 days', 'end' => '-87 days', 'purpose' => 'Antar tim ke site Blok A'],
            ['vehicle' => 0, 'driver' => 1, 'start' => '-75 days', 'end' => '-73 days', 'purpose' => 'Inspeksi lapangan Blok B'],
            ['vehicle' => 0, 'driver' => 0, 'start' => '-60 days', 'end' => '-58 days', 'purpose' => 'Rapat koordinasi site manager'],
            ['vehicle' => 0, 'driver' => 2, 'start' => '-40 days', 'end' => '-38 days', 'purpose' => 'Pengiriman peralatan K3'],
            ['vehicle' => 0, 'driver' => 1, 'start' => '-20 days', 'end' => '-18 days', 'purpose' => 'Kunjungan tim HSE'],

            // KB 5678 CD — 3 trips
            ['vehicle' => 1, 'driver' => 2, 'start' => '-80 days', 'end' => '-78 days', 'purpose' => 'Angkut batu bara Blok C'],
            ['vehicle' => 1, 'driver' => 3, 'start' => '-50 days', 'end' => '-47 days', 'purpose' => 'Pengiriman material tambang'],
            ['vehicle' => 1, 'driver' => 2, 'start' => '-25 days', 'end' => '-23 days', 'purpose' => 'Angkut limbah B3 ke TPS'],

            // KB 9012 EF — 2 trips
            ['vehicle' => 2, 'driver' => 3, 'start' => '-70 days', 'end' => '-68 days', 'purpose' => 'Angkut suku cadang alat berat'],
            ['vehicle' => 2, 'driver' => 0, 'start' => '-30 days', 'end' => '-28 days', 'purpose' => 'Kirim logistik ke gudang 2'],

            // KT 1111 AA — 4 trips
            ['vehicle' => 3, 'driver' => 1, 'start' => '-85 days', 'end' => '-83 days', 'purpose' => 'Antar staf ke kantor pusat'],
            ['vehicle' => 3, 'driver' => 0, 'start' => '-65 days', 'end' => '-63 days', 'purpose' => 'Survey lokasi tambang baru'],
            ['vehicle' => 3, 'driver' => 3, 'start' => '-45 days', 'end' => '-43 days', 'purpose' => 'Perjalanan dinas Kadiv'],
            ['vehicle' => 3, 'driver' => 2, 'start' => '-15 days', 'end' => '-13 days', 'purpose' => 'Antar tamu dari KLH'],

            // KT 2222 BB — 2 trips
            ['vehicle' => 4, 'driver' => 3, 'start' => '-55 days', 'end' => '-53 days', 'purpose' => 'Angkut tanah overburden'],
            ['vehicle' => 4, 'driver' => 1, 'start' => '-10 days', 'end' => '-8 days',  'purpose' => 'Pengiriman drum solar ke site'],

            // KT 3333 CC — 1 trip
            ['vehicle' => 5, 'driver' => 0, 'start' => '-35 days', 'end' => '-33 days', 'purpose' => 'Antar direksi ke lapangan'],
        ];

        foreach ($finalizedBookings as $b) {
            $start = Carbon::now()->modify($b['start'])->startOfDay();
            $end   = Carbon::now()->modify($b['end'])->startOfDay();

            // Skip if a booking with same vehicle/driver/date already exists
            $exists = Booking::where('vehicle_id', $vehicles[$b['vehicle']]->id)
                ->where('tanggal_mulai', $start)
                ->exists();
            if ($exists) continue;

            $booking = Booking::create([
                'vehicle_id'          => $vehicles[$b['vehicle']]->id,
                'driver_id'           => $drivers[$b['driver']]->id,
                'approver_level_1_id' => $approver1->id,
                'approver_level_2_id' => $approver2->id,
                'tanggal_mulai'       => $start,
                'tanggal_selesai'     => $end,
                'keperluan'           => $b['purpose'],
                'konsumsi_bbm'        => rand(15, 80) + (rand(0, 9) / 10),
                'status_pembokingan'  => 'disetujui_final',
            ]);

            // Create approval records (both approved)
            Approval::updateOrCreate(
                ['booking_id' => $booking->id, 'level' => 1],
                ['approver_id' => $approver1->id, 'status' => 'disetujui']
            );
            Approval::updateOrCreate(
                ['booking_id' => $booking->id, 'level' => 2],
                ['approver_id' => $approver2->id, 'status' => 'disetujui']
            );
        }

        // ------------------------------------------------------------------
        // A few active (pending) bookings — for the stats card
        // ------------------------------------------------------------------
        $pendingBookings = [
            ['vehicle' => 1, 'driver' => 0, 'start' => '+3 days', 'end' => '+5 days',   'purpose' => 'Angkut material proyek D'],
            ['vehicle' => 3, 'driver' => 2, 'start' => '+7 days', 'end' => '+9 days',   'purpose' => 'Kunjungan tim audit internal'],
            ['vehicle' => 5, 'driver' => 3, 'start' => '+10 days', 'end' => '+12 days', 'purpose' => 'Perjalanan dinas ke Samarinda'],
        ];

        foreach ($pendingBookings as $b) {
            $start = Carbon::now()->modify($b['start'])->startOfDay();
            $end   = Carbon::now()->modify($b['end'])->startOfDay();

            $exists = Booking::where('vehicle_id', $vehicles[$b['vehicle']]->id)
                ->where('tanggal_mulai', $start)
                ->exists();
            if ($exists) continue;

            $booking = Booking::create([
                'vehicle_id'          => $vehicles[$b['vehicle']]->id,
                'driver_id'           => $drivers[$b['driver']]->id,
                'approver_level_1_id' => $approver1->id,
                'approver_level_2_id' => $approver2->id,
                'tanggal_mulai'       => $start,
                'tanggal_selesai'     => $end,
                'keperluan'           => $b['purpose'],
                'konsumsi_bbm'        => null,
                'status_pembokingan'  => 'pending',
            ]);

            Approval::updateOrCreate(
                ['booking_id' => $booking->id, 'level' => 1],
                ['approver_id' => $approver1->id, 'status' => 'pending']
            );
            Approval::updateOrCreate(
                ['booking_id' => $booking->id, 'level' => 2],
                ['approver_id' => $approver2->id, 'status' => 'pending']
            );
        }

        $this->command->info('Dummy data seeded: 6 vehicles, 4 drivers, 17 finalized bookings (chart data), 3 pending bookings.');
    }
}
