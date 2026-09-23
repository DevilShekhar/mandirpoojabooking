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
        Schema::create('temples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained('states')->cascadeOnDelete();
            $table->foreignId('district_id')->constrained('districts')->cascadeOnDelete();
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('temple_code')->unique();
            $table->text('address');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('main_image')->nullable();
            $table->string('banner_image')->nullable();
            $table->boolean('is_verified')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temples');
    }
};
