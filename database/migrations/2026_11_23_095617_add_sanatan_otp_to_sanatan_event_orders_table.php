<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sanatan_event_orders', function (Blueprint $table) {
            $table->string('sanatan_otp', 6)->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('sanatan_event_orders', function (Blueprint $table) {
            $table->dropColumn('sanatan_otp');
        });
    }
};