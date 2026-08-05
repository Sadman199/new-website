<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brokers', function (Blueprint $table) {
            // Priority 1 — app views already reference these
            $table->unsignedSmallInteger('year_founded')->nullable()->after('country');
            $table->string('commission', 100)->nullable()->after('pricing');
            $table->string('withdrawal_fee', 100)->nullable()->after('withdrawal_method');
            $table->string('demo_link', 255)->nullable()->after('open_demo');
            $table->string('demo_duration', 50)->nullable()->after('demo_link');
            $table->boolean('demo_account_available')->default(false)->after('demo_duration');

            // Priority 2 — BrokerChooser / BrokersView summary fields
            $table->enum('fee_level', ['low', 'medium', 'high'])->nullable()->after('commission');
            $table->boolean('investor_protection')->default(false)->after('insurance');
            $table->unsignedTinyInteger('trust_score')->nullable()->after('rating');
            $table->unsignedTinyInteger('regulatory_tier')->nullable()->after('trust_score');
            $table->boolean('negative_balance_protection')->default(false)->after('segregation_of_funds');
            $table->json('markets')->nullable()->after('account_types');
            $table->unsignedInteger('instrument_count')->nullable()->after('markets');
            $table->json('category_scores')->nullable()->after('instrument_count');
            $table->text('verdict')->nullable()->after('cons');
        });
    }

    public function down(): void
    {
        Schema::table('brokers', function (Blueprint $table) {
            $table->dropColumn([
                'year_founded',
                'commission',
                'withdrawal_fee',
                'demo_link',
                'demo_duration',
                'demo_account_available',
                'fee_level',
                'investor_protection',
                'trust_score',
                'regulatory_tier',
                'negative_balance_protection',
                'markets',
                'instrument_count',
                'category_scores',
                'verdict',
            ]);
        });
    }
};
