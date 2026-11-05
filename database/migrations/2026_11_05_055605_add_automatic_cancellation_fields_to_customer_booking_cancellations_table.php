
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_booking_cancellations', function (Blueprint $table) {

            if (!Schema::hasColumn('customer_booking_cancellations', 'cancelled_by')) {
                $table->foreignId('cancelled_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('customer_booking_cancellations', 'cancellation_role')) {
                $table->string('cancellation_role', 50)->nullable();
            }

            if (!Schema::hasColumn('customer_booking_cancellations', 'cancellation_percentage')) {
                $table->decimal('cancellation_percentage', 5, 2)->default(0);
            }

            if (!Schema::hasColumn('customer_booking_cancellations', 'cancellation_charge')) {
                $table->decimal('cancellation_charge', 10, 2)->default(0);
            }

            if (!Schema::hasColumn('customer_booking_cancellations', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable();
            }

            if (!Schema::hasColumn('customer_booking_cancellations', 'razorpay_refund_id')) {
                $table->string('razorpay_refund_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Keep existing columns safe.
    }
};
