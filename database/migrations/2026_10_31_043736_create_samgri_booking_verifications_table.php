<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samgri_booking_verifications', function (Blueprint $table) {

            $table->id();

            $table->foreignId('puja_booking_id')
                ->constrained('puja_bookings')
                ->cascadeOnDelete();

            $table->foreignId('temple_id')
                ->nullable()
                ->constrained('temples')
                ->nullOnDelete();

            $table->foreignId('puja_id')
                ->nullable()
                ->constrained('pujas')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('customer_name');
            $table->string('customer_mobile', 20)->nullable();

            $table->string('otp', 10);

            $table->boolean('is_verified')->default(false);

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();

            // Audit columns
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Created and updated timestamps
            $table->timestamps();

            // One verification record per booking
            $table->unique('puja_booking_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samgri_booking_verifications');
    }
};