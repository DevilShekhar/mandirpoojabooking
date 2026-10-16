
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('puja_bookings', function (Blueprint $table) {
            $table->date('schedule_date')->nullable()->after('puja_package_id');
            $table->time('schedule_time')->nullable()->after('schedule_date');
            $table->dropColumn('puja_schedule_id');
        });
    }

    public function down(): void
    {
        Schema::table('puja_bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('puja_schedule_id')->nullable()->after('puja_package_id');
            $table->dropColumn(['schedule_date', 'schedule_time']);
        });
    }
};
