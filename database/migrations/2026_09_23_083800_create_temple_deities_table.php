<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temple_deities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temple_id')->constrained('temples')->cascadeOnDelete();
            $table->foreignId('deity_id')->constrained('deities')->cascadeOnDelete();
            $table->boolean('is_primary')->default(0);
            $table->integer('display_order')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->unique(['temple_id', 'deity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temple_deities');
    }
};
