<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pandit_pujas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pandit_id')->constrained('pandits')->cascadeOnDelete();
            $table->foreignId('puja_id')->constrained('pujas')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['pandit_id', 'puja_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pandit_pujas');
    }
};
