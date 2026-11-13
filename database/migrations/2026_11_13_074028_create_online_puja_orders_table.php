<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('online_puja_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone', 20)->nullable();
            $table->unsignedBigInteger('online_puja_id');
            $table->unsignedBigInteger('online_puja_cat_id')->nullable();
            $table->unsignedBigInteger('package_id');
            $table->string('package_name');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('INR');
            $table->string('payment_status')->default('pending');
            $table->string('razorpay_order_id')->nullable()->unique();
            $table->string('razorpay_payment_id')->nullable()->unique();
            $table->string('razorpay_signature')->nullable();
            $table->string('booking_status')->default('pending');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('customer_id');
            $table->index('online_puja_id');
            $table->index('online_puja_cat_id');
            $table->index('package_id');
            $table->index('payment_status');
            $table->index('booking_status');
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('online_puja_orders');
    }
};