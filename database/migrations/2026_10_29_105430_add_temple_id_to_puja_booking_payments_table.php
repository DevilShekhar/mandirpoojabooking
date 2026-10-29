<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('puja_booking_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('temple_id')
                ->nullable()
                ->after('puja_booking_id');

            // Add a foreign key only if temple IDs reference the temples table
            // and the referenced column uses a compatible type.
            $table->foreign('temple_id')
                ->references('id')
                ->on('temples')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('puja_booking_payments', function (Blueprint $table) {
            $table->dropForeign(['temple_id']);
            $table->dropColumn('temple_id');
        });
    }
};