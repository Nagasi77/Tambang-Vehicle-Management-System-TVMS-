<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportReportRequest;
use App\Services\ReportService;
use Carbon\Carbon;
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
     */
    public function export(ExportReportRequest $request)
    {
        $from = Carbon::parse($request->tanggal_mulai)->startOfDay();
        $to   = Carbon::parse($request->tanggal_selesai)->endOfDay();

        return $this->reportService->export($from, $to);
    }
}
