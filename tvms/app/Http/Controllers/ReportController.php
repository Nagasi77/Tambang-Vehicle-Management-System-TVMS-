<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportReportRequest;
use App\Models\ActivityLog;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(private ReportService $reportService) {}

    /**
     * Show the report filter form.
     */
    public function index(): View
    {
        return view('reports.index');
    }

    /**
     * Export booking data to Excel based on date range filter.
     * Logs the export action to the activity log.
     */
    public function export(ExportReportRequest $request)
    {
        $from = Carbon::parse($request->tanggal_mulai)->startOfDay();
        $to   = Carbon::parse($request->tanggal_selesai)->endOfDay();

        // Catat aktivitas export laporan
        try {
            ActivityLog::create([
                'user_id'       => Auth::id(),
                'aksi'          => 'report.exported',
                'deskripsi'     => 'Admin ' . (Auth::user()?->name ?? 'Sistem') .
                                   ' mengekspor laporan pemesanan periode ' .
                                   $from->format('d/m/Y') . ' s.d. ' . $to->format('d/m/Y'),
                'loggable_type' => null,
                'loggable_id'   => null,
                'data_lama'     => null,
                'data_baru'     => json_encode([
                    'dari'     => $from->toDateString(),
                    'sampai'   => $to->toDateString(),
                ]),
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('ReportController::export log failed', [
                'error' => $e->getMessage(),
            ]);
        }

        return $this->reportService->export($from, $to);
    }
}
