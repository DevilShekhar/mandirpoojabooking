<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('online_pujas', function (Blueprint $table) {
            $table->id();

            // Category Relationship
            $table->foreignId('online_puja_category_id')
                ->constrained('online_puja_categories')
                ->cascadeOnDelete();

            // Basic Details
            $table->string('title');
            $table->string('slug')->unique();
            $table->boolean('status')->default(1);
            $table->text('short_description')->nullable();

            // Images
            $table->string('banner_image')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->json('gallery_images')->nullable();

            // Puja Information Tabs
            $table->longText('puja_significance')->nullable();
            $table->longText('puja_benefits')->nullable();
            $table->longText('puja_procedure')->nullable();
            $table->longText('what_you_will_receive')->nullable();
            $table->longText('temple_description')->nullable();

            // Temple Details
            $table->string('temple_name')->nullable();
            $table->string('temple_location')->nullable();
            $table->string('temple_city')->nullable();
            $table->string('temple_state')->nullable();
            $table->text('temple_address')->nullable();

            // Puja Schedule
            $table->dateTime('puja_datetime')->nullable();
            $table->string('puja_day')->nullable();
            $table->string('tithi')->nullable();
            $table->string('muhurat')->nullable();
            $table->dateTime('muhurat_end_at')->nullable();

            // Booking Details
            $table->dateTime('booking_start_at')->nullable();
            $table->dateTime('booking_end_at')->nullable();
            $table->unsignedInteger('total_seat')->nullable();
            $table->unsignedInteger('booked_seat')->default(0);

            // Video Delivery
            $table->unsignedInteger('video_delivery_hours')->default(48);
            $table->boolean('whatsapp_delivery_enabled')->default(1);
            $table->string('video_url')->nullable();

            // Display Settings
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_popular')->default(0);
            $table->unsignedInteger('sort_order')->default(0);

            // SEO Details
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            // User Tracking
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('online_pujas');
    }
};