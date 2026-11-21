
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temple_timings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temple_id')->constrained('temples')->cascadeOnDelete();
            $table->foreignId('temple_timing_category_id')->constrained('temple_timing_categories')->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->string('start_day', 20);
            $table->string('end_day', 20);
            $table->time('opening_time');
            $table->time('closing_time');
            $table->boolean('status')->default(1);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['temple_id', 'status']);
            $table->index(['temple_timing_category_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temple_timings');
    }
};