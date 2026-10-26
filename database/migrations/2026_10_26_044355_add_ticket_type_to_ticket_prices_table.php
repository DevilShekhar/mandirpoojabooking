<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_prices', function (Blueprint $table) {
            $table->string('ticket_type', 20)
                ->default('normal')
                ->after('temple_id');
        });

        Schema::table('ticket_prices', function (Blueprint $table) {
            $table->dropUnique('ticket_prices_temple_type_unique');

            $table->unique(
                ['temple_id', 'ticket_type', 'type'],
                'ticket_prices_temple_ticket_type_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('ticket_prices', function (Blueprint $table) {
            $table->dropUnique('ticket_prices_temple_ticket_type_unique');
        });

        Schema::table('ticket_prices', function (Blueprint $table) {
            $table->dropColumn('ticket_type');

            $table->unique(
                ['temple_id', 'type'],
                'ticket_prices_temple_type_unique'
            );
        });
    }
};