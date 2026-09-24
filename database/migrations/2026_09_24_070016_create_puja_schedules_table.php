<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('puja_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('temple_id')
                ->constrained('temples')
                ->cascadeOnDelete();

            $table->foreignId('puja_id')
                ->constrained('pujas')
                ->cascadeOnDelete();

            $table->date('puja_date');

            $table->time('start_time');
            $table->time('end_time');

            $table->unsignedInteger('capacity')->default(0);
            $table->unsignedInteger('booked_capacity')->default(0);
            $table->unsignedInteger('remaining_capacity')->default(0);

            $table->date('booking_start_date')->nullable();
            $table->date('booking_end_date')->nullable();

            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('puja_schedules');
    }
};