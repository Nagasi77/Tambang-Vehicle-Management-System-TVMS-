<?php

namespace App\Exports;

use App\Models\Booking;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function __construct(
        private Carbon $from,
        private Carbon $to
    ) {}

    public function query()
    {
        return Booking::with(['vehicle', 'driver', 'approverLevel1', 'approverLevel2', 'approvals'])
            ->whereBetween('tanggal_mulai', [$this->from, $this->to])
            ->orderBy('tanggal_mulai');
    }

    public function title(): string
    {
        return 'Laporan Pemesanan';
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
            'Konsumsi BBM (Liter)',
            'Approver Level 1',
            'Status Approval L1',
            'Catatan Penolakan L1',
            'Approver Level 2',
            'Status Approval L2',
            'Catatan Penolakan L2',
            'Status Pembokingan',
            'Tanggal Dibuat',
        ];
    }

    public function map($booking): array
    {
        $approval1 = $booking->approvals->firstWhere('level', 1);
        $approval2 = $booking->approvals->firstWhere('level', 2);

        $statusLabel = [
            'pending'           => 'Pending',
            'disetujui_level_1' => 'Disetujui Level 1',
            'disetujui_final'   => 'Disetujui Final',
            'ditolak'           => 'Ditolak',
            'dibatalkan'        => 'Dibatalkan',
        ];

        $approvalStatusLabel = [
            'pending'   => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
        ];

        return [
            $booking->id,
            $booking->vehicle?->plat_nomor ?? '—',
            $booking->driver?->nama_driver ?? '—',
            $booking->tanggal_mulai?->format('d/m/Y') ?? '—',
            $booking->tanggal_selesai?->format('d/m/Y') ?? '—',
            $booking->keperluan,
            $booking->konsumsi_bbm !== null ? number_format($booking->konsumsi_bbm, 2) : '—',
            $booking->approverLevel1?->name ?? '—',
            $approvalStatusLabel[$approval1?->status ?? ''] ?? ($approval1?->status ?? '—'),
            $approval1?->catatan ?? '—',
            $booking->approverLevel2?->name ?? '—',
            $approvalStatusLabel[$approval2?->status ?? ''] ?? ($approval2?->status ?? '—'),
            $approval2?->catatan ?? '—',
            $statusLabel[$booking->status_pembokingan] ?? $booking->status_pembokingan,
            $booking->created_at?->format('d/m/Y H:i') ?? '—',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Bold header row
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F46E5']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
