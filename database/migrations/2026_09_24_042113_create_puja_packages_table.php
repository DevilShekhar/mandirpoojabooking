<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('puja_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('puja_id')->constrained('pujas')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('person_count')->default(1);
            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->string('package_image')->nullable();
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->boolean('includes_sankalp')->default(false);
            $table->boolean('includes_prasad')->default(false);
            $table->boolean('includes_video')->default(false);
            $table->boolean('includes_certificate')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puja_packages');
    }
};
