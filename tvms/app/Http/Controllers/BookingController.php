<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    /**
     * Daftar semua booking (admin) — paginate 15, eager load relasi.
     */
    public function index(): View
    {
        $bookings = Booking::with(['vehicle', 'driver', 'approverLevel1', 'approverLevel2'])
            ->latest()
            ->paginate(15);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Form buat booking baru — populate dropdown.
     */
    public function create(): View
    {
        $vehicles  = Vehicle::orderBy('plat_nomor')->get();
        $drivers   = Driver::where('status', 'tersedia')->orderBy('nama_driver')->get();
        $approvers = User::where('role', 'approver')->orderBy('name')->get();

        return view('bookings.create', compact('vehicles', 'drivers', 'approvers'));
    }

    /**
     * Simpan booking baru via BookingService.
     */
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $booking = $this->bookingService->createBooking($request->validated());

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking berhasil dibuat.');
    }

    /**
     * Detail booking beserta status approval.
     */
    public function show(Booking $booking): View
    {
        $booking->load(['vehicle', 'driver', 'approverLevel1', 'approverLevel2', 'approvals.approver']);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Form edit booking — hanya untuk status pending.
     */
    public function edit(Booking $booking): View|RedirectResponse
    {
        if ($booking->status_pembokingan !== 'pending') {
            return redirect()->route('bookings.index')
                ->with('error', 'Hanya booking berstatus pending yang dapat diedit.');
        }

        $vehicles  = Vehicle::orderBy('plat_nomor')->get();
        $drivers   = Driver::where('status', 'tersedia')->orderBy('nama_driver')->get();
        $approvers = User::where('role', 'approver')->orderBy('name')->get();

        return view('bookings.edit', compact('booking', 'vehicles', 'drivers', 'approvers'));
    }

    /**
     * Update booking — hanya untuk status pending.
     */
    public function update(UpdateBookingRequest $request, Booking $booking): RedirectResponse
    {
        if ($booking->status_pembokingan !== 'pending') {
            return redirect()->route('bookings.index')
                ->with('error', 'Hanya booking berstatus pending yang dapat diedit.');
        }

        $booking->update($request->validated());

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking berhasil diperbarui.');
    }

    /**
     * Batalkan booking via BookingService.
     * Hanya untuk status pending atau disetujui_level_1.
     */
    public function cancel(Booking $booking): RedirectResponse
    {
        try {
            $this->bookingService->cancelBooking($booking);

            return redirect()->route('bookings.index')
                ->with('success', 'Booking berhasil dibatalkan.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
