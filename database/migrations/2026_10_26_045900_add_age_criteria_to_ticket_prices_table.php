<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_prices', function (Blueprint $table) {
            $table->unsignedSmallInteger('min_age')
                ->default(0)
                ->after('type');

            $table->unsignedSmallInteger('max_age')
                ->nullable()
                ->after('min_age');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_prices', function (Blueprint $table) {
            $table->dropColumn(['min_age', 'max_age']);
        });
    }
};