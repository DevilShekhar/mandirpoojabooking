<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_devotees', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')->constrained('puja_bookings')->cascadeOnDelete();
            $table->string('name');
            $table->string('relation')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gotra')->nullable();
            $table->string('rashi')->nullable();
            $table->string('nakshatra')->nullable();
            $table->string('mobile')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_devotees');
    }
};
