<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleServiceController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// -------------------------------------------------------------------------
// Guest-only routes (redirect to dashboard if already authenticated)
// -------------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

    // Login throttle: max 5 attempts per 15 minutes (per IP)
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,15')
        ->name('login.submit');
});

// Logout (authenticated users only)
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// -------------------------------------------------------------------------
// Admin routes — requires authentication and admin role
// -------------------------------------------------------------------------
Route::middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])->name('dashboard.chart-data');

    // Vehicles (CRUD)
    Route::resource('vehicles', VehicleController::class);

    // Vehicle service history (nested resource)
    Route::resource('vehicles.services', VehicleServiceController::class);

    // Drivers (CRUD)
    Route::resource('drivers', DriverController::class);

    // Bookings (CRUD + cancel)
    Route::resource('bookings', BookingController::class);
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Reports (filter form + Excel export)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // Jadwal Servis — ringkasan semua riwayat servis lintas kendaraan
    Route::get('/services', [VehicleServiceController::class, 'allServices'])->name('services.index');
});

// -------------------------------------------------------------------------
// Approver routes — requires authentication and approver role
// -------------------------------------------------------------------------
Route::middleware(['auth', 'role:approver'])->group(function () {

    Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
    Route::get('/approvals/{booking}', [ApprovalController::class, 'show'])->name('approvals.show');
    Route::post('/approvals/{booking}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{booking}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
});
