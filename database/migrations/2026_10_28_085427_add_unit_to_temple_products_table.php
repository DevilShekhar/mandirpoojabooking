<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temple_products', function (Blueprint $table) {
            $table->string('unit', 50)
                ->default('piece')
                ->after('qty');
        });
    }

    public function down(): void
    {
        Schema::table('temple_products', function (Blueprint $table) {
            $table->dropColumn('unit');
        });
    }
};