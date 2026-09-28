<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('puja_bookings', function (Blueprint $table) {
            $table->id();

            $table->string('booking_number')->unique();

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('temple_id')->nullable()->constrained('temples')->nullOnDelete();
            $table->foreignId('deity_id')->nullable()->constrained('deities')->nullOnDelete();
            $table->foreignId('puja_id')->nullable()->constrained('pujas')->nullOnDelete();
            $table->foreignId('puja_package_id')->nullable()->constrained('puja_packages')->nullOnDelete();
            $table->foreignId('puja_schedule_id')->nullable()->constrained('puja_schedules')->nullOnDelete();

            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_mobile', 20);
            $table->unsignedBigInteger('address_id')->nullable();

            // Pricing snapshot
            $table->string('package_name')->nullable();
            $table->unsignedInteger('person_count')->default(1);

            $table->decimal('base_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('coupon_discount', 12, 2)->default(0);
            $table->decimal('addon_amount', 12, 2)->default(0);
            $table->decimal('shipping_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);

            // Workflow statuses
            $table->string('payment_status')->default('pending');
            $table->string('booking_status')->default('new');
            $table->string('sankalp_status')->default('pending');
            $table->string('puja_status')->default('pending');
            $table->string('video_status')->default('pending');
            $table->string('prasad_status')->default('pending');

            $table->timestamps();

            $table->index('booking_number');
            $table->index('customer_mobile');
            $table->index('payment_status');
            $table->index('booking_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('puja_bookings');
    }
};
