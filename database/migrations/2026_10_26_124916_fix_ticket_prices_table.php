
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ticket_prices')) {
            Schema::create('ticket_prices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('temple_id')->constrained('temples')->cascadeOnDelete();
                $table->string('ticket_type', 20)->default('normal');
                $table->string('type', 100);
                $table->unsignedSmallInteger('min_age')->default(0);
                $table->unsignedSmallInteger('max_age')->nullable();
                $table->decimal('price', 10, 2)->default(0);
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->boolean('status')->default(true);
                $table->timestamps();
                $table->unique(
                    ['temple_id', 'ticket_type', 'type'],
                    'ticket_prices_temple_ticket_type_unique'
                );
            });

            return;
        }

        if (!Schema::hasColumn('ticket_prices', 'temple_id')) {
            Schema::table('ticket_prices', function (Blueprint $table) {
                $table->foreignId('temple_id')->constrained('temples')->cascadeOnDelete();
            });
        }

        if (!Schema::hasColumn('ticket_prices', 'ticket_type')) {
            Schema::table('ticket_prices', function (Blueprint $table) {
                $table->string('ticket_type', 20)->default('normal');
            });
        }

        if (!Schema::hasColumn('ticket_prices', 'type')) {
            Schema::table('ticket_prices', function (Blueprint $table) {
                $table->string('type', 100);
            });
        }

        if (!Schema::hasColumn('ticket_prices', 'min_age')) {
            Schema::table('ticket_prices', function (Blueprint $table) {
                $table->unsignedSmallInteger('min_age')->default(0);
            });
        }

        if (!Schema::hasColumn('ticket_prices', 'max_age')) {
            Schema::table('ticket_prices', function (Blueprint $table) {
                $table->unsignedSmallInteger('max_age')->nullable();
            });
        }

        if (!Schema::hasColumn('ticket_prices', 'price')) {
            Schema::table('ticket_prices', function (Blueprint $table) {
                $table->decimal('price', 10, 2)->default(0);
            });
        }

        if (!Schema::hasColumn('ticket_prices', 'status')) {
            Schema::table('ticket_prices', function (Blueprint $table) {
                $table->boolean('status')->default(true);
            });
        }

        if (!Schema::hasColumn('ticket_prices', 'created_by')) {
            Schema::table('ticket_prices', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('ticket_prices', 'updated_by')) {
            Schema::table('ticket_prices', function (Blueprint $table) {
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('ticket_prices', 'created_at')) {
            Schema::table('ticket_prices', function (Blueprint $table) {
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
    }
};