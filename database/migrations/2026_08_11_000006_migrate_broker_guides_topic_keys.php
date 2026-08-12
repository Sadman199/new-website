<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /** @var array<string, string> */
    private array $legacyKeyToSlug = [
        'best_account_type' => 'best-account-type',
        'account_opening_guide' => 'account-opening-guide',
        'demo_account' => 'demo-account',
        'live_account' => 'live-account',
        'islamic_account' => 'islamic-account',
        'deposits_withdrawals' => 'deposits-withdrawals',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('broker_guides') || ! Schema::hasColumn('broker_guides', 'topic_key')) {
            return;
        }

        $topicIdsBySlug = DB::table('broker_guide_topics')->pluck('id', 'slug');

        DB::table('broker_guides')->orderBy('id')->each(function ($guide) use ($topicIdsBySlug) {
            $slug = $this->legacyKeyToSlug[$guide->topic_key] ?? Str::slug(str_replace('_', '-', $guide->topic_key));
            $topicId = $topicIdsBySlug[$slug] ?? null;

            if ($topicId) {
                DB::table('broker_guides')->where('id', $guide->id)->update([
                    'broker_guide_topic_id' => $topicId,
                ]);
            }
        });

        Schema::table('broker_guides', function ($table) {
            $table->dropUnique(['broker_id', 'topic_key']);
            $table->dropColumn('topic_key');
            $table->unique(['broker_id', 'broker_guide_topic_id']);
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('broker_guides')) {
            return;
        }

        Schema::table('broker_guides', function ($table) {
            if (! Schema::hasColumn('broker_guides', 'topic_key')) {
                $table->string('topic_key', 64)->nullable()->after('broker_id');
            }
        });

        $topics = DB::table('broker_guide_topics')->get(['id', 'slug']);

        foreach ($topics as $topic) {
            $legacyKey = array_search($topic->slug, $this->legacyKeyToSlug, true) ?: str_replace('-', '_', $topic->slug);

            DB::table('broker_guides')
                ->where('broker_guide_topic_id', $topic->id)
                ->update(['topic_key' => $legacyKey]);
        }

        Schema::table('broker_guides', function ($table) {
            $table->dropUnique(['broker_id', 'broker_guide_topic_id']);
            $table->dropConstrainedForeignId('broker_guide_topic_id');
            $table->unique(['broker_id', 'topic_key']);
        });
    }
};
