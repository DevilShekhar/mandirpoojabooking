<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temple_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('temple_id')
                ->constrained('temples')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // pending, approved, rejected
            $table->string('approval_status')
                ->default('pending');

            $table->timestamps();

            $table->unique(
                ['temple_id', 'user_id'],
                'temple_user_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temple_user');
    }
};