<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained('drivers')->cascadeOnDelete();
            $table->foreignId('approver_level_1_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approver_level_2_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('keperluan', 255);
            $table->decimal('konsumsi_bbm', 8, 2)->nullable();
            $table->enum('status_pembokingan', [
                'pending',
                'disetujui_level_1',
                'disetujui_final',
                'ditolak',
                'dibatalkan',
            ])->default('pending');
            $table->timestamps();

            // Composite indexes for conflict detection
            $table->index(['vehicle_id', 'tanggal_mulai', 'tanggal_selesai'], 'idx_vehicle_dates');
            $table->index(['driver_id', 'tanggal_mulai', 'tanggal_selesai'], 'idx_driver_dates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
