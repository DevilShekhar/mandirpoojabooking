<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('puja_bookings', function (Blueprint $table) {
            $table->foreignId('completed_by')
                ->nullable()
                ->after('updated_by')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('completed_at')
                ->nullable()
                ->after('completed_by');
        });
    }

    public function down(): void
    {
        Schema::table('puja_bookings', function (Blueprint $table) {
            $table->dropForeign(['completed_by']);
            $table->dropColumn([
                'completed_by',
                'completed_at',
            ]);
        });
    }
};