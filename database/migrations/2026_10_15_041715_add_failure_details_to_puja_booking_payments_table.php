<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('puja_booking_payments', function (Blueprint $table) {
            $table->string('error_code')->nullable();
            $table->text('error_description')->nullable();
            $table->string('error_source')->nullable();
            $table->string('error_step')->nullable();
            $table->string('error_reason')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('puja_booking_payments', function (Blueprint $table) {
            $table->dropColumn([
                'error_code',
                'error_description',
                'error_source',
                'error_step',
                'error_reason',
            ]);
        });
    }
};