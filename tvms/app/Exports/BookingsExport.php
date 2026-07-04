<?php

namespace App\Exports;

use App\Models\Booking;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BookingsExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(
        private Carbon $from,
        private Carbon $to
    ) {}

    public function query()
    {
        return Booking::with(['vehicle', 'driver'])
            ->whereBetween('tanggal_mulai', [$this->from, $this->to])
            ->orderBy('tanggal_mulai');
    }

    public function headings(): array
    {
        return [
            'ID Booking',
            'Plat Nomor Kendaraan',
            'Nama Pengemudi',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Keperluan',
            'Konsumsi BBM',
            'Status Pembokingan',
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->id,
            $booking->vehicle?->plat_nomor ?? '—',
            $booking->driver?->nama_driver ?? '—',
            $booking->tanggal_mulai?->format('d/m/Y'),
            $booking->tanggal_selesai?->format('d/m/Y'),
            $booking->keperluan,
            $booking->konsumsi_bbm ?? '—',
            $booking->status_pembokingan,
        ];
    }
}
