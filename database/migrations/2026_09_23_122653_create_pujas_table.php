<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pujas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('temple_id')
                ->constrained('temples')
                ->cascadeOnDelete();

            $table->foreignId('deity_id')
                ->constrained('deities')
                ->cascadeOnDelete();

            $table->foreignId('puja_category_id')
                ->constrained('puja_categories')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('puja_code')->unique();

            $table->text('description')->nullable();
            $table->text('benefits')->nullable();
            $table->text('ritual_details')->nullable();
            $table->text('samagri_details')->nullable();

            $table->string('duration')->nullable();

            $table->string('image')->nullable();
            $table->string('banner_image')->nullable();

            $table->boolean('is_online')->default(0);
            $table->boolean('is_offline')->default(1);
            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pujas');
    }
};
