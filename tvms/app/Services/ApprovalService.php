<?php

namespace App\Services;

use App\Models\Approval;
use App\Models\User;

class ApprovalService
{
    /**
     * Approve a Level 1 approval.
     * - approval.level must be 1
     * - approval.status must be 'pending'
     * - $approver must be the assigned approver (approver_id match)
     */
    public function approveLevel1(Approval $approval, User $approver): void
    {
        $this->validateApprover($approval, $approver);
        $this->validateStatus($approval, 'pending', 'Level 1');

        if ($approval->level !== 1) {
            throw new \InvalidArgumentException('Approval ini bukan Level 1.');
        }

        $approval->update(['status' => 'disetujui']);
        $approval->booking->update(['status_pembokingan' => 'disetujui_level_1']);
    }

    /**
     * Reject a Level 1 approval.
     */
    public function rejectLevel1(Approval $approval, User $approver, string $notes): void
    {
        $this->validateApprover($approval, $approver);
        $this->validateStatus($approval, 'pending', 'Level 1');

        if ($approval->level !== 1) {
            throw new \InvalidArgumentException('Approval ini bukan Level 1.');
        }

        $approval->update(['status' => 'ditolak', 'catatan' => $notes]);
        $approval->booking->update(['status_pembokingan' => 'ditolak']);
    }

    /**
     * Approve a Level 2 approval.
     * Booking must be in 'disetujui_level_1' status.
     * After approval: driver status → 'bertugas'.
     */
    public function approveLevel2(Approval $approval, User $approver): void
    {
        $this->validateApprover($approval, $approver);
        $this->validateStatus($approval, 'pending', 'Level 2');

        if ($approval->level !== 2) {
            throw new \InvalidArgumentException('Approval ini bukan Level 2.');
        }

        // Eager load booking with driver to avoid N+1 and ensure driver is available
        $approval->load('booking.driver');
        $booking = $approval->booking;

        if ($booking->status_pembokingan !== 'disetujui_level_1') {
            throw new \InvalidArgumentException(
                'Booking harus berstatus disetujui_level_1 sebelum dapat disetujui Level 2. Status saat ini: ' .
                $booking->status_pembokingan
            );
        }

        $approval->update(['status' => 'disetujui']);
        $booking->update(['status_pembokingan' => 'disetujui_final']);

        // Update driver status to 'bertugas'
        $booking->driver->update(['status' => 'bertugas']);
    }

    /**
     * Reject a Level 2 approval.
     */
    public function rejectLevel2(Approval $approval, User $approver, string $notes): void
    {
        $this->validateApprover($approval, $approver);
        $this->validateStatus($approval, 'pending', 'Level 2');

        if ($approval->level !== 2) {
            throw new \InvalidArgumentException('Approval ini bukan Level 2.');
        }

        $approval->update(['status' => 'ditolak', 'catatan' => $notes]);
        $approval->booking->update(['status_pembokingan' => 'ditolak']);
    }

    /**
     * Validate that the given user is the assigned approver.
     * Aborts with 403 if not authorized.
     */
    private function validateApprover(Approval $approval, User $approver): void
    {
        if ($approval->approver_id !== $approver->id) {
            abort(403, 'Anda tidak berwenang untuk memproses approval ini.');
        }
    }

    /**
     * Validate that the approval is in the expected status.
     */
    private function validateStatus(Approval $approval, string $expectedStatus, string $level): void
    {
        if ($approval->status !== $expectedStatus) {
            throw new \InvalidArgumentException(
                "Approval {$level} sudah diproses (status: {$approval->status}). Tidak dapat mengambil tindakan lebih lanjut."
            );
        }
    }
}
