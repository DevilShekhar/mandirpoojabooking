<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('puja_packages', 'pandit_price')) {
            Schema::table('puja_packages', function (Blueprint $table) {
                $table->decimal('pandit_price', 10, 2)->default(0)->after('person_count');
            });
        }

        if (!Schema::hasColumn('puja_packages', 'products')) {
            Schema::table('puja_packages', function (Blueprint $table) {
                $table->longText('products')->nullable()->after('pandit_price');
            });
        }

        if (Schema::hasColumn('puja_packages', 'puja_samagri')) {
            Schema::table('puja_packages', function (Blueprint $table) {
                $table->dropColumn('puja_samagri');
            });
        }

        if (Schema::hasColumn('pujas', 'samagri_details')) {
            Schema::table('pujas', function (Blueprint $table) {
                $table->dropColumn('samagri_details');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('puja_packages', 'products')) {
            Schema::table('puja_packages', function (Blueprint $table) {
                $table->dropColumn('products');
            });
        }

        if (Schema::hasColumn('puja_packages', 'pandit_price')) {
            Schema::table('puja_packages', function (Blueprint $table) {
                $table->dropColumn('pandit_price');
            });
        }

        if (!Schema::hasColumn('puja_packages', 'puja_samagri')) {
            Schema::table('puja_packages', function (Blueprint $table) {
                $table->longText('puja_samagri')->nullable();
            });
        }

        if (!Schema::hasColumn('pujas', 'samagri_details')) {
            Schema::table('pujas', function (Blueprint $table) {
                $table->longText('samagri_details')->nullable();
            });
        }
    }
};