<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_reference')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('temple_id')->constrained('temples')->cascadeOnDelete();
            $table->date('booking_date');
            $table->string('member_name');
            $table->string('aadhaar_number')->nullable();
            $table->string('aadhaar_photo')->nullable();
            $table->string('ticket_type');
            $table->string('type');
            $table->unsignedInteger('age');
            $table->foreignId('ticket_price_id')->nullable()->constrained('ticket_prices')->nullOnDelete();
            $table->decimal('price', 10, 2);
            $table->string('razorpay_order_id')->index();
            $table->string('razorpay_payment_id')->nullable()->unique();
            $table->string('payment_status')->default('pending');
            $table->string('booking_status')->default('pending');
            $table->timestamps();

            $table->index(['user_id', 'booking_date']);
            $table->index(['temple_id', 'booking_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_bookings');
    }
};