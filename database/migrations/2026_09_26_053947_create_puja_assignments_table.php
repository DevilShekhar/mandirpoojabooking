<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('puja_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->cascadeOnDelete();

            $table->foreignId('pandit_id')
                ->constrained('pandits')
                ->cascadeOnDelete();

            $table->foreignId('assigned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('assigned_at')->nullable();

            $table->enum('status', [
                'assigned',
                'accepted',
                'started',
                'completed',
                'cancelled'
            ])->default('assigned');

            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('puja_assignments');
    }
};
