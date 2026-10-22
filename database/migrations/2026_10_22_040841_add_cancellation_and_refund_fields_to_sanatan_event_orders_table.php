<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sanatan_event_orders', function (Blueprint $table) {
            $table->text('cancellation_reason')->nullable();
            $table->unsignedBigInteger('cancelled_by')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('refund_status')->default('not_requested');
            $table->string('razorpay_refund_id')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->timestamp('refunded_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('sanatan_event_orders', function (Blueprint $table) {
            $table->dropColumn([
                'cancellation_reason',
                'cancelled_by',
                'cancelled_at',
                'refund_status',
                'razorpay_refund_id',
                'refund_amount',
                'refunded_at',
            ]);
        });
    }
};