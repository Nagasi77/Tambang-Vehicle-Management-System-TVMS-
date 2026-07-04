<?php

namespace App\Services;

use App\Exports\BookingsExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportService
{
    public function export(Carbon $from, Carbon $to): BinaryFileResponse
    {
        $filename = 'laporan-booking-' . $from->format('Ymd') . '-' . $to->format('Ymd') . '.xlsx';

        // Check if there's any data in the given date range
        $hasData = \App\Models\Booking::whereBetween('tanggal_mulai', [$from, $to])->exists();

        if (!$hasData) {
            // Generate Excel with header + "no data" row
            $export = new class($from, $to) implements WithHeadings, FromArray {
                public function __construct(private Carbon $from, private Carbon $to) {}

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

                public function array(): array
                {
                    return [
                        ['Tidak ada data ditemukan untuk rentang tanggal ini', '', '', '', '', '', '', ''],
                    ];
                }
            };

            return Excel::download($export, $filename);
        }

        return Excel::download(new BookingsExport($from, $to), $filename);
    }
}
