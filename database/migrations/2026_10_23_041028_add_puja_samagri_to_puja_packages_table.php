<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('puja_packages', function (Blueprint $table) {
            $table->json('puja_samagri')->nullable()->after('includes_certificate');
        });
    }

    public function down(): void
    {
        Schema::table('puja_packages', function (Blueprint $table) {
            $table->dropColumn('puja_samagri');
        });
    }
};