<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_cart', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('temple_id');
            $table->unsignedBigInteger('user_id');
            $table->date('booking_date');
            $table->string('member_name', 150);
            $table->string('aadhaar_number', 12);
            $table->string('aadhaar_photo');
            $table->string('ticket_type', 50);
            $table->string('type', 50);
            $table->unsignedTinyInteger('age');
            $table->unsignedBigInteger('ticket_price_id');
            $table->decimal('price', 10, 2);
            $table->string('status', 30)->default('active');
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['temple_id', 'booking_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_cart');
    }
};