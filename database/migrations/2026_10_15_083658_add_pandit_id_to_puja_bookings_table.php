<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('puja_bookings', function (Blueprint $table) {
            $table->foreignId('pandit_id')
                ->nullable()
                ->after('puja_schedule_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->index(['temple_id', 'pandit_id']);
        });
    }

    public function down(): void
    {
        Schema::table('puja_bookings', function (Blueprint $table) {
            $table->dropIndex(['temple_id', 'pandit_id']);
            $table->dropConstrainedForeignId('pandit_id');
        });
    }
};