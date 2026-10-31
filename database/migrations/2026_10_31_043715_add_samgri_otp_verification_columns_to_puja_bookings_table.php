<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('puja_bookings', function (Blueprint $table) {
            $table->boolean('samgri_otp_verified')
                ->default(false)
                ->after('samgri_otp');

            $table->timestamp('samgri_otp_verified_at')
                ->nullable()
                ->after('samgri_otp_verified');
        });
    }

    public function down(): void
    {
        Schema::table('puja_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'samgri_otp_verified',
                'samgri_otp_verified_at',
            ]);
        });
    }
};