<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard main page — statistics cards + recent activity.
     */
    public function index(): View
    {
        // Active bookings (pending + disetujui_level_1)
        $activeBookings = Booking::whereIn('status_pembokingan', ['pending', 'disetujui_level_1'])->count();

        // Total vehicles
        $totalVehicles = Vehicle::count();

        // Available drivers
        $availableDrivers = Driver::where('status', 'tersedia')->count();

        // Recent activity logs (latest 10)
        $recentLogs = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact('activeBookings', 'totalVehicles', 'availableDrivers', 'recentLogs'));
    }

    /**
     * JSON endpoint for Chart.js — vehicle usage frequency.
     * Returns bookings with status='disetujui_final' grouped by plat_nomor.
     */
    public function chartData(): JsonResponse
    {
        $data = Booking::select('vehicles.plat_nomor', DB::raw('COUNT(*) as total'))
            ->join('vehicles', 'bookings.vehicle_id', '=', 'vehicles.id')
            ->where('bookings.status_pembokingan', 'disetujui_final')
            ->groupBy('vehicles.plat_nomor')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json([
            'labels' => $data->pluck('plat_nomor')->toArray(),
            'data'   => $data->pluck('total')->toArray(),
        ]);
    }
}
