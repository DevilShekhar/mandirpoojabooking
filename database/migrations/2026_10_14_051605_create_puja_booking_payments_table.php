
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('puja_booking_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('puja_id')
                ->constrained('pujas')
                ->cascadeOnDelete();

            $table->foreignId('puja_package_id')
                ->constrained('puja_packages')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('puja_booking_id')
                ->nullable()
                ->index();

            $table->unsignedBigInteger('amount_paise');

            $table->string('currency', 3)->default('INR');

            $table->string('razorpay_order_id')->unique();

            $table->string('razorpay_payment_id')
                ->nullable()
                ->unique();

            $table->string('razorpay_signature', 255)->nullable();

            $table->string('status', 30)->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('puja_booking_payments');
    }
};
