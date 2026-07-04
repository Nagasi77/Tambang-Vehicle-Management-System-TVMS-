<?php

namespace App\Http\Controllers;

use App\Http\Requests\RejectApprovalRequest;
use App\Models\Approval;
use App\Models\Booking;
use App\Services\ApprovalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ApprovalController extends Controller
{
    public function __construct(private ApprovalService $approvalService) {}

    /**
     * List bookings assigned to the logged-in approver at their appropriate level,
     * ordered by tanggal_mulai ascending.
     *
     * Logic:
     * - Find all Approval records where approver_id = auth()->id()
     * - Level 1 approvers see level=1 approvals with status=pending (booking status=pending)
     * - Level 2 approvers see level=2 approvals with status=pending (booking status=disetujui_level_1)
     * - Eager load booking.vehicle, booking.driver
     */
    public function index(): View
    {
        $userId = auth()->id();

        $approvals = Approval::with([
                'booking.vehicle',
                'booking.driver',
                'booking.approverLevel1',
                'booking.approverLevel2',
            ])
            ->where('approver_id', $userId)
            ->where('status', 'pending')
            ->whereHas('booking', function ($q) {
                $q->whereNotIn('status_pembokingan', ['ditolak', 'dibatalkan']);
            })
            ->join('bookings', 'approvals.booking_id', '=', 'bookings.id')
            ->orderBy('bookings.tanggal_mulai', 'asc')
            ->select('approvals.*')
            ->get();

        return view('approvals.index', compact('approvals'));
    }

    /**
     * Show booking detail with approve/reject form for the assigned approver.
     */
    public function show(Booking $booking): View
    {
        $booking->load(['vehicle', 'driver', 'approverLevel1', 'approverLevel2', 'approvals.approver']);

        $userId     = auth()->id();
        $myApproval = $booking->approvals->first(
            fn($a) => $a->approver_id === $userId && $a->status === 'pending'
        );

        return view('approvals.show', compact('booking', 'myApproval'));
    }

    /**
     * Process an approval action (approve).
     */
    public function approve(Booking $booking): RedirectResponse
    {
        $userId     = auth()->id();
        $myApproval = $booking->approvals()
            ->where('approver_id', $userId)
            ->where('status', 'pending')
            ->first();

        if (! $myApproval) {
            abort(403, 'Anda tidak berwenang atau approval sudah diproses.');
        }

        try {
            if ($myApproval->level === 1) {
                $this->approvalService->approveLevel1($myApproval, auth()->user());
            } else {
                $this->approvalService->approveLevel2($myApproval, auth()->user());
            }

            return redirect()->route('approvals.index')
                ->with('success', 'Pemesanan berhasil disetujui.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Process a rejection (reject with notes).
     */
    public function reject(Booking $booking, RejectApprovalRequest $request): RedirectResponse
    {
        $userId     = auth()->id();
        $myApproval = $booking->approvals()
            ->where('approver_id', $userId)
            ->where('status', 'pending')
            ->first();

        if (! $myApproval) {
            abort(403, 'Anda tidak berwenang atau approval sudah diproses.');
        }

        try {
            if ($myApproval->level === 1) {
                $this->approvalService->rejectLevel1($myApproval, auth()->user(), $request->catatan);
            } else {
                $this->approvalService->rejectLevel2($myApproval, auth()->user(), $request->catatan);
            }

            return redirect()->route('approvals.index')
                ->with('success', 'Pemesanan berhasil ditolak.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
