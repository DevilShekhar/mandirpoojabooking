<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('online_puja_packages', function (Blueprint $table) {
            $table->id();

            // Online Puja Relationship
            $table->foreignId('online_puja_id')
                ->constrained('online_pujas')
                ->cascadeOnDelete();

            // Package Details
            $table->string('package_name');
            $table->unsignedInteger('devotees_count')->default(1);
            $table->decimal('price', 10, 2);

            // Package Description
            $table->text('description')->nullable();

            // Package Settings
            $table->boolean('is_recommended')->default(0);
            $table->boolean('status')->default(1);
            $table->unsignedInteger('sort_order')->default(0);

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
        Schema::dropIfExists('online_puja_packages');
    }
};