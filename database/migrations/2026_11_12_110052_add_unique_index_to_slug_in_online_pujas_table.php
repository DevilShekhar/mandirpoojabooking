<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $indexes = DB::select("SHOW INDEX FROM online_pujas");

        $indexExists = collect($indexes)->contains(function ($index) {
            return $index->Key_name === 'online_pujas_slug_unique';
        });

        if (!$indexExists) {
            Schema::table('online_pujas', function (Blueprint $table) {
                $table->unique('slug', 'online_pujas_slug_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $indexes = DB::select("SHOW INDEX FROM online_pujas");

        $indexExists = collect($indexes)->contains(function ($index) {
            return $index->Key_name === 'online_pujas_slug_unique';
        });

        if ($indexExists) {
            Schema::table('online_pujas', function (Blueprint $table) {
                $table->dropUnique('online_pujas_slug_unique');
            });
        }
    }
};