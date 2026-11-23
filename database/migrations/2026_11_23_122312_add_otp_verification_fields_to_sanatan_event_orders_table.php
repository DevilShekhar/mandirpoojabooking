<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sanatan_event_orders', function (Blueprint $table) {
            $table->foreignId('otp_verifiction_by')
                ->nullable()
                ->after('completed_at')
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('otp_verifiction_at')->nullable()->after('otp_verifiction_by');
        });
    }

    public function down(): void
    {
        Schema::table('sanatan_event_orders', function (Blueprint $table) {
            $table->dropForeign(['otp_verifiction_by']);

            $table->dropColumn([
                'otp_verifiction_by',
                'otp_verifiction_at',
            ]);
        });
    }
};