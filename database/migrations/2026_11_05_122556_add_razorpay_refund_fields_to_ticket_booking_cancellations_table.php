<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_booking_cancellations', function (Blueprint $table) {
            if (!Schema::hasColumn('ticket_booking_cancellations', 'razorpay_payment_id')) {
                $table->string('razorpay_payment_id')->nullable();
            }
            if (!Schema::hasColumn('ticket_booking_cancellations', 'razorpay_refund_id')) {
                $table->string('razorpay_refund_id')->nullable();
            }
            if (!Schema::hasColumn('ticket_booking_cancellations', 'refund_error')) {
                $table->text('refund_error')->nullable();
            }
            if (!Schema::hasColumn('ticket_booking_cancellations', 'refunded_at')) {
                $table->timestamp('refunded_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('ticket_booking_cancellations', function (Blueprint $table) {
            $columns = [];
            foreach (['razorpay_payment_id', 'razorpay_refund_id', 'refund_error', 'refunded_at'] as $column) {
                if (Schema::hasColumn('ticket_booking_cancellations', $column)) {
                    $columns[] = $column;
                }
            }
            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};