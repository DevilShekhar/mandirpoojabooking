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
        Schema::table('ticket_bookings', function (Blueprint $table) {
            $table->foreignId('completed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('completed_at')->nullable();

            $table->foreignId('ticket_otp_verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('ticket_otp_verified_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ticket_bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('completed_by');
            $table->dropConstrainedForeignId('ticket_otp_verified_by');

            $table->dropColumn([
                'completed_at',
                'ticket_otp_verified_at',
            ]);
        });
    }
};
