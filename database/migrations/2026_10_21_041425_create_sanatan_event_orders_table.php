<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sanatan_event_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sanatan_id')->constrained('sanatans')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->date('event_date');
            $table->time('event_time');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('INR');
            $table->string('razorpay_order_id')->nullable()->unique();
            $table->string('razorpay_payment_id')->nullable()->unique();
            $table->string('payment_status')->default('pending');
            $table->string('booking_status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanatan_event_orders');
    }
};