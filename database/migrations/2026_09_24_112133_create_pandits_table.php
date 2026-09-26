<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pandits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('temple_id')->nullable()->constrained('temples')->nullOnDelete();
            $table->string('name');
            $table->string('mobile', 20);
            $table->string('email')->nullable();
            $table->string('profile_image')->nullable();
            $table->integer('experience')->default(0);
            $table->text('languages')->nullable();
            $table->text('specialization')->nullable();
            $table->enum('verification_status', [
                'pending',
                'verified',
                'rejected'
            ])->default('pending');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pandits');
    }
};
