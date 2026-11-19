<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('puja_bookings', function (Blueprint $table) {
            $table->string('puja_completed_otp', 6)
                ->nullable()
                ->after('samgri_otp_verified_at');

            $table->timestamp('puja_completed_otp_at')
                ->nullable()
                ->after('puja_completed_otp');

            $table->foreignId('puja_completed_otp_verify_by')
                ->nullable()
                ->after('puja_completed_otp_at')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('puja_bookings', function (Blueprint $table) {
            $table->dropForeign(['puja_completed_otp_verify_by']);

            $table->dropColumn([
                'puja_completed_otp',
                'puja_completed_otp_at',
                'puja_completed_otp_verify_by',
            ]);
        });
    }
};