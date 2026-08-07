<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prop_firm_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('prop_firms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prop_firm_category_id')->nullable()->constrained('prop_firm_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->longText('description')->nullable();
            $table->string('website')->nullable();
            $table->string('affiliate_link')->nullable();
            $table->unsignedSmallInteger('founded_year')->nullable();
            $table->string('headquarters')->nullable();
            $table->string('max_funding')->nullable();
            $table->string('profit_split')->nullable();
            $table->decimal('min_fee', 12, 2)->nullable();
            $table->decimal('max_fee', 12, 2)->nullable();
            $table->boolean('scaling_available')->default(false);
            $table->decimal('trust_score', 4, 1)->nullable();
            $table->decimal('editor_rating', 4, 1)->nullable();
            $table->decimal('user_rating', 4, 1)->nullable();
            $table->decimal('overall_rating', 4, 1)->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('prop_firm_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prop_firm_id')->constrained('prop_firms')->cascadeOnDelete();
            $table->string('name');
            $table->string('account_size')->nullable();
            $table->decimal('entry_fee', 12, 2)->nullable();
            $table->string('profit_target')->nullable();
            $table->string('daily_drawdown')->nullable();
            $table->string('max_drawdown')->nullable();
            $table->string('profit_split')->nullable();
            $table->unsignedSmallInteger('min_trading_days')->nullable();
            $table->boolean('news_trading')->default(false);
            $table->boolean('weekend_holding')->default(false);
            $table->boolean('ea_allowed')->default(false);
            $table->boolean('copy_trading')->default(false);
            $table->boolean('hedging')->default(false);
            $table->boolean('refund_available')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('prop_firm_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('group')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('prop_firm_attribute_prop_firm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prop_firm_id')->constrained('prop_firms')->cascadeOnDelete();
            $table->foreignId('prop_firm_attribute_id')->constrained('prop_firm_attributes')->cascadeOnDelete();
            $table->unique(['prop_firm_id', 'prop_firm_attribute_id'], 'prop_firm_attribute_unique');
        });

        Schema::create('prop_firm_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prop_firm_id')->constrained('prop_firms')->cascadeOnDelete();
            $table->decimal('rating', 3, 1);
            $table->string('title');
            $table->longText('content');
            $table->string('author')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('prop_firm_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prop_firm_id')->constrained('prop_firms')->cascadeOnDelete();
            $table->string('question');
            $table->longText('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('prop_firm_module_settings', function (Blueprint $table) {
            $table->id();
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prop_firm_module_settings');
        Schema::dropIfExists('prop_firm_faqs');
        Schema::dropIfExists('prop_firm_reviews');
        Schema::dropIfExists('prop_firm_attribute_prop_firm');
        Schema::dropIfExists('prop_firm_attributes');
        Schema::dropIfExists('prop_firm_programs');
        Schema::dropIfExists('prop_firms');
        Schema::dropIfExists('prop_firm_categories');
    }
};
