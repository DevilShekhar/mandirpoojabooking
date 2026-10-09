<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sanatans', function (Blueprint $table) {
            $table->string('language', 100)->nullable()->after('program_type');
        });
    }

    public function down(): void
    {
        Schema::table('sanatans', function (Blueprint $table) {
            $table->dropColumn('language');
        });
    }
};
