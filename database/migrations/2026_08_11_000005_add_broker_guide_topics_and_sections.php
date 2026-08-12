<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('broker_guide_topics', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 80)->unique();
            $table->string('title');
            $table->text('default_summary')->nullable();
            $table->string('icon', 80)->nullable();
            $table->string('context_profile', 40)->nullable();
            $table->boolean('requires_swap_free')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('broker_guide_settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
        });

        Schema::table('broker_guides', function (Blueprint $table) {
            $table->foreignId('broker_guide_topic_id')->nullable()->after('broker_id')->constrained('broker_guide_topics')->cascadeOnDelete();
        });

        Schema::create('broker_guide_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broker_guide_id')->constrained('broker_guides')->cascadeOnDelete();
            $table->string('section_type', 40);
            $table->json('section_data')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['broker_guide_id', 'sort_order']);
        });

        $this->seedDefaultTopics();
        $this->seedHubSettings();
    }

    private function seedDefaultTopics(): void
    {
        if (DB::table('broker_guide_topics')->count() > 0) {
            return;
        }
        $now = now();
        $rows = [
            ['slug' => 'best-account-type', 'title' => 'Which account type is best for you', 'default_summary' => 'Compare spreads, leverage, and features to pick the right account for your trading style.', 'icon' => 'fas fa-layer-group', 'context_profile' => 'account_types', 'requires_swap_free' => false, 'sort_order' => 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'account-opening-guide', 'title' => 'Account opening guide', 'default_summary' => 'Step-by-step overview of documents, verification, and what to expect when signing up.', 'icon' => 'fas fa-file-signature', 'context_profile' => null, 'requires_swap_free' => false, 'sort_order' => 2, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'demo-account', 'title' => 'How to open a demo account', 'default_summary' => 'Practice with virtual funds before committing real capital.', 'icon' => 'fas fa-flask', 'context_profile' => 'demo_cta', 'requires_swap_free' => false, 'sort_order' => 3, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'live-account', 'title' => 'How to open a live trading account', 'default_summary' => 'Funding, verification, and platform setup for live trading.', 'icon' => 'fas fa-chart-line', 'context_profile' => 'live_cta', 'requires_swap_free' => false, 'sort_order' => 4, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'islamic-account', 'title' => 'How the Islamic account works', 'default_summary' => 'Swap-free conditions, Sharia-compliant features, and eligibility.', 'icon' => 'fas fa-moon', 'context_profile' => null, 'requires_swap_free' => true, 'sort_order' => 5, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'deposits-withdrawals', 'title' => 'Minimum deposit & withdrawal', 'default_summary' => 'Funding methods, minimum amounts, fees, and payout timelines.', 'icon' => 'fas fa-wallet', 'context_profile' => 'deposits_withdrawals', 'requires_swap_free' => false, 'sort_order' => 6, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('broker_guide_topics')->insert($rows);
    }

    private function seedHubSettings(): void
    {
        if (DB::table('broker_guide_settings')->count() > 0) {
            return;
        }
        DB::table('broker_guide_settings')->insert([
            ['key' => 'hub_title', 'value' => config('broker-guides.hub.title')],
            ['key' => 'hub_description', 'value' => config('broker-guides.hub.description')],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('broker_guide_sections');

        Schema::table('broker_guides', function (Blueprint $table) {
            $table->dropConstrainedForeignId('broker_guide_topic_id');
        });

        Schema::dropIfExists('broker_guide_settings');
        Schema::dropIfExists('broker_guide_topics');
    }
};
