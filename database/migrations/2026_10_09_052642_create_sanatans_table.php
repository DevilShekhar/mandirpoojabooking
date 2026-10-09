<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sanatans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('program_type', 100);
            $table->string('short_description', 500)->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('duration')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_online')->default(true);
            $table->boolean('is_offline')->default(true);
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->index('program_type');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanatans');
    }
};