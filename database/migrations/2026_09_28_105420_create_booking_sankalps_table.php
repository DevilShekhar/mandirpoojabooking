<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_sankalps', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained('puja_bookings')
                ->cascadeOnDelete();

            $table->date('sankalp_date')->nullable();

            $table->text('sankalp_text')->nullable();

            $table->foreignId('pandit_id')
                ->nullable()
                ->constrained('pandits')
                ->nullOnDelete();

            $table->enum('status', [
                'pending',
                'in_progress',
                'completed'
            ])->default('pending');

            $table->timestamp('completed_at')->nullable();

            $table->string('video_url')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_sankalps');
    }
};
