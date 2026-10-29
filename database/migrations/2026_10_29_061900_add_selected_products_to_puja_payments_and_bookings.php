<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('puja_booking_payments', function (Blueprint $table) {
            $table->json('selected_products')->nullable();
        });

        Schema::table('puja_bookings', function (Blueprint $table) {
            $table->json('selected_products')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('puja_bookings', function (Blueprint $table) {
            $table->dropColumn('selected_products');
        });

        Schema::table('puja_booking_payments', function (Blueprint $table) {
            $table->dropColumn('selected_products');
        });
    }
};