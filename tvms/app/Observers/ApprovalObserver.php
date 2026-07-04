<?php
namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Approval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApprovalObserver
{
    public function created(Approval $approval): void
    {
        try {
            ActivityLog::create([
                'user_id'       => $approval->approver_id,
                'aksi'          => 'approval.created',
                'deskripsi'     => 'Approval Level ' . $approval->level . ' dibuat untuk Pemesanan ID #' . $approval->booking_id . ' dengan status ' . $approval->status,
                'loggable_type' => Approval::class,
                'loggable_id'   => $approval->id,
                'data_lama'     => null,
                'data_baru'     => json_encode(['level' => $approval->level, 'status' => $approval->status]),
            ]);
        } catch (\Throwable $e) {
            Log::error('ApprovalObserver::created failed', [
                'approval_id' => $approval->id,
                'error'       => $e->getMessage(),
            ]);
        }
    }

    public function updated(Approval $approval): void
    {
        if (!$approval->isDirty('status')) {
            return;
        }

        try {
            $user = Auth::user();
            ActivityLog::create([
                'user_id'       => $user?->id ?? $approval->approver_id,
                'aksi'          => 'approval.status_changed',
                'deskripsi'     => 'Approver ' . ($approval->approver?->name ?? 'Sistem') .
                                   ' mengubah status Approval Level ' . $approval->level .
                                   ' Pemesanan ID #' . $approval->booking_id .
                                   ' menjadi ' . $approval->status,
                'loggable_type' => Approval::class,
                'loggable_id'   => $approval->id,
                'data_lama'     => json_encode(['status' => $approval->getOriginal('status')]),
                'data_baru'     => json_encode(['status' => $approval->status]),
            ]);
        } catch (\Throwable $e) {
            Log::error('ApprovalObserver::updated failed', [
                'approval_id' => $approval->id,
                'error'       => $e->getMessage(),
            ]);
        }
    }
}
