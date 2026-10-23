
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('puja_booking_payments', function (Blueprint $table) {
            $table->timestamp('emails_sent_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('puja_booking_payments', function (Blueprint $table) {
            $table->dropColumn('emails_sent_at');
        });
    }
};
