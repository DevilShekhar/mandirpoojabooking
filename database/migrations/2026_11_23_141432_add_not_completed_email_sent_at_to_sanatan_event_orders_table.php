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
        Schema::table('sanatan_event_orders', function (Blueprint $table) {
            $table->timestamp('not_completed_email_sent_at')
                ->nullable()
                ->after('otp_verifiction_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sanatan_event_orders', function (Blueprint $table) {
            $table->dropColumn('not_completed_email_sent_at');
        });
    }
};
