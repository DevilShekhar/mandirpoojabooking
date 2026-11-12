<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('online_puja_faqs', function (Blueprint $table) {
            $table->id();

            // Online Puja Relationship
            $table->foreignId('online_puja_id')
                ->constrained('online_pujas')
                ->cascadeOnDelete();

            // FAQ Details
            $table->longText('question');
            $table->longText('answer');

            // User Tracking
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('online_puja_faqs');
    }
};