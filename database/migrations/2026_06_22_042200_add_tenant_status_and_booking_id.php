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
        Schema::table('tenant_profiles', function (Blueprint $table) {
            $table->string('status')->default('Aktif')->after('address');
            // Status: Menunggu Persetujuan, Aktif, Keluar, Nonaktif, Ditolak
            $table->foreignId('booking_id')->nullable()->after('user_id')->constrained('bookings')->nullOnDelete();
            $table->text('rejection_reason')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('rejection_reason');
            $table->timestamp('checked_out_at')->nullable()->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_profiles', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropColumn(['status', 'booking_id', 'rejection_reason', 'approved_at', 'checked_out_at']);
        });
    }
};