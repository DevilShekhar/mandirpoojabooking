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
        Schema::table('online_puja_orders', function (Blueprint $table) {
            $table->text('cancellation_reason')->nullable()->after('booking_status');
            $table->timestamp('cancelled_at')->nullable()->after('cancellation_reason');
            $table->unsignedBigInteger('cancelled_by')->nullable()->after('cancelled_at');
            $table->decimal('cancellation_fee_percentage', 5, 2)->default(0)->after('cancelled_by');
            $table->decimal('refund_amount', 10, 2)->default(0)->after('cancellation_fee_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('online_puja_orders', function (Blueprint $table) {
            $table->dropColumn([
                'cancellation_reason',
                'cancelled_at',
                'cancelled_by',
                'cancellation_fee_percentage',
                'refund_amount'
            ]);
        });
    }
};