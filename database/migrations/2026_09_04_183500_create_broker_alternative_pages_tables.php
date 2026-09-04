<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('broker_alternative_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broker_id')->unique()->constrained('brokers')->cascadeOnDelete();
            $table->boolean('is_published')->default(false);
            $table->string('seo_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->text('intro')->nullable();
            $table->text('why_consider')->nullable();
            $table->json('faqs')->nullable();
            $table->timestamps();
        });

        Schema::create('broker_alternative_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('broker_alternative_pages')->cascadeOnDelete();
            $table->foreignId('alternative_broker_id')->constrained('brokers')->cascadeOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['page_id', 'alternative_broker_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('broker_alternative_items');
        Schema::dropIfExists('broker_alternative_pages');
    }
};
