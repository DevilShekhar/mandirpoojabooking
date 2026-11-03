<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sanatan_event_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('completed_by')
                ->nullable()
                ->after('cancelled_at');

            $table->timestamp('completed_at')
                ->nullable()
                ->after('completed_by');

            $table->index('completed_by');
            $table->index('completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('sanatan_event_orders', function (Blueprint $table) {
            $table->dropIndex(['completed_by']);
            $table->dropIndex(['completed_at']);

            $table->dropColumn([
                'completed_by',
                'completed_at',
            ]);
        });
    }
};