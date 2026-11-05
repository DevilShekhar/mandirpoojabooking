<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_booking_cancellations', function (Blueprint $table) {
            $table->id();

            $table->string('razorpay_order_id')->index();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('temple_id');

            $table->dateTime('booking_date');

            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('deduction_percentage', 5, 2)->default(0);
            $table->decimal('refund_percentage', 5, 2)->default(100);
            $table->decimal('refund_amount', 10, 2)->default(0);

            $table->string('cancellation_status')->default('pending');
            $table->string('refund_status')->default('pending');

            $table->text('cancellation_reason')->nullable();

            $table->string('razorpay_refund_id')->nullable();

            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('processed_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'cancellation_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_booking_cancellations');
    }
};