<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('temple_id');
            $table->string('title');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->string('image')->nullable();
            $table->string('artical_img')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            // Active by default
            $table->tinyInteger('status')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};