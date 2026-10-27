<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temple_daily_ticket_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temple_id')->constrained('temples')->cascadeOnDelete();
            $table->date('booking_date');
            $table->enum('ticket_type', ['normal', 'vip']);
            $table->unsignedInteger('daily_limit');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(
                ['temple_id', 'booking_date', 'ticket_type'],
                'tdtl_temple_date_type_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temple_daily_ticket_limits');
    }
};