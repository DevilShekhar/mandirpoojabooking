
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_booking_cancellations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('puja_booking_id')
                ->constrained('puja_bookings')
                ->cascadeOnDelete();

            $table->foreignId('customer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('cancellation_reason');

            $table->string('status', 30)->default('pending');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('review_notes')->nullable();

            $table->decimal('refund_amount', 10, 2)->default(0);

            $table->string('refund_status', 30)->default('not_required');

            $table->string('razorpay_refund_id', 100)->nullable();

            $table->text('refund_error')->nullable();

            $table->timestamp('reviewed_at')->nullable();

            $table->timestamp('refunded_at')->nullable();

            $table->timestamps();

            $table->index(['status', 'refund_status'], 'cbc_status_refund_index');

            $table->unique(
                ['puja_booking_id', 'customer_id'],
                'cbc_booking_customer_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_booking_cancellations');
    }
};