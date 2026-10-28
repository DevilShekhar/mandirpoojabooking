<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temple_products', function (Blueprint $table) {
            $table->dropColumn(['stock', 'image']);
        });
    }

    public function down(): void
    {
        Schema::table('temple_products', function (Blueprint $table) {
            $table->integer('stock')->default(0);
            $table->string('image')->nullable();
        });
    }
};