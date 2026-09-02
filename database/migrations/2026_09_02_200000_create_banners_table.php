<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('banners')) {
            Schema::create('banners', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('desktop_image');
                $table->string('mobile_image')->nullable();
                $table->string('banner_type', 40)->default('promotional');
                $table->string('targeting', 40)->default('website_wide');
                $table->string('placement', 60);
                $table->string('page_path', 255)->nullable();
                $table->string('button_text', 120)->nullable();
                $table->string('button_url', 500)->nullable();
                $table->date('start_date');
                $table->date('end_date');
                $table->unsignedInteger('priority')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['placement', 'is_active', 'priority']);
                $table->index(['start_date', 'end_date']);
                $table->index('targeting');
            });
        }

        if (! Schema::hasTable('banner_broker')) {
            Schema::create('banner_broker', function (Blueprint $table) {
                $table->id();
                $table->foreignId('banner_id')->constrained('banners')->cascadeOnDelete();
                $table->foreignId('broker_id')->constrained('brokers')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['banner_id', 'broker_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('banner_broker');
        Schema::dropIfExists('banners');
    }
};
