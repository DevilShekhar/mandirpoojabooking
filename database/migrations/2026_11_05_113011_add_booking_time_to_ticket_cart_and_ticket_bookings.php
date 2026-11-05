<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the selected visit time to the ticket cart and confirmed bookings.
     */
    public function up(): void
    {
        // The cart table in this application is named ticket_cart (singular).
        if (Schema::hasTable('ticket_cart') && !Schema::hasColumn('ticket_cart', 'booking_time')) {
            Schema::table('ticket_cart', function (Blueprint $table) {
                $table->time('booking_time')->nullable()->after('booking_date');
            });
        }

        // Confirmed ticket bookings also need to retain the selected visit time.
        if (Schema::hasTable('ticket_bookings') && !Schema::hasColumn('ticket_bookings', 'booking_time')) {
            Schema::table('ticket_bookings', function (Blueprint $table) {
                $table->time('booking_time')->nullable()->after('booking_date');
            });
        }
    }

    /**
     * Remove the added visit-time columns.
     */
    public function down(): void
    {
        if (Schema::hasTable('ticket_bookings') && Schema::hasColumn('ticket_bookings', 'booking_time')) {
            Schema::table('ticket_bookings', function (Blueprint $table) {
                $table->dropColumn('booking_time');
            });
        }

        if (Schema::hasTable('ticket_cart') && Schema::hasColumn('ticket_cart', 'booking_time')) {
            Schema::table('ticket_cart', function (Blueprint $table) {
                $table->dropColumn('booking_time');
            });
        }
    }
};