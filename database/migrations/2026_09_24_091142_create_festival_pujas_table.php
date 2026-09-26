<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('festival_pujas', function (Blueprint $table) {

            $table->id();
            $table->foreignId('festival_id')->constrained('festivals')->cascadeOnDelete();
            $table->foreignId('puja_id')->constrained('pujas')->cascadeOnDelete();
            $table->decimal('special_price', 10, 2)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->unique(['festival_id', 'puja_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('festival_pujas');
    }
};
