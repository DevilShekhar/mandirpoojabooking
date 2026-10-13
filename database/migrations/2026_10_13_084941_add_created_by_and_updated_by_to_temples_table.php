<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temples', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')
                ->nullable()
                ->after('updated_at')
                ->index();

            $table->unsignedBigInteger('updated_by')
                ->nullable()
                ->after('created_by')
                ->index();
        });
    }

    public function down(): void
    {
        Schema::table('temples', function (Blueprint $table) {
            $table->dropIndex(['created_by']);
            $table->dropIndex(['updated_by']);

            $table->dropColumn([
                'created_by',
                'updated_by',
            ]);
        });
    }
};