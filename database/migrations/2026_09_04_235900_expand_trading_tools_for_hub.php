<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('trading_tools')) {
            return;
        }

        Schema::table('trading_tools', function (Blueprint $table) {
            if (! Schema::hasColumn('trading_tools', 'category')) {
                $table->string('category', 40)->default('trading_calculators')->after('sort_order');
            }
            if (! Schema::hasColumn('trading_tools', 'seo_title')) {
                $table->string('seo_title')->nullable()->after('category');
            }
            if (! Schema::hasColumn('trading_tools', 'meta_description')) {
                $table->string('meta_description', 500)->nullable()->after('seo_title');
            }
            if (! Schema::hasColumn('trading_tools', 'canonical_url')) {
                $table->string('canonical_url', 500)->nullable()->after('meta_description');
            }
            if (! Schema::hasColumn('trading_tools', 'og_title')) {
                $table->string('og_title')->nullable()->after('canonical_url');
            }
            if (! Schema::hasColumn('trading_tools', 'og_description')) {
                $table->string('og_description', 500)->nullable()->after('og_title');
            }
            if (! Schema::hasColumn('trading_tools', 'introduction')) {
                $table->text('introduction')->nullable()->after('og_description');
            }
            if (! Schema::hasColumn('trading_tools', 'how_to_use')) {
                $table->text('how_to_use')->nullable()->after('introduction');
            }
            if (! Schema::hasColumn('trading_tools', 'formula')) {
                $table->text('formula')->nullable()->after('how_to_use');
            }
            if (! Schema::hasColumn('trading_tools', 'example')) {
                $table->text('example')->nullable()->after('formula');
            }
            if (! Schema::hasColumn('trading_tools', 'additional_explanation')) {
                $table->text('additional_explanation')->nullable()->after('example');
            }
            if (! Schema::hasColumn('trading_tools', 'faqs')) {
                $table->json('faqs')->nullable()->after('additional_explanation');
            }
            if (! Schema::hasColumn('trading_tools', 'related_tool_ids')) {
                $table->json('related_tool_ids')->nullable()->after('faqs');
            }
            if (! Schema::hasColumn('trading_tools', 'related_broker_ids')) {
                $table->json('related_broker_ids')->nullable()->after('related_tool_ids');
            }
        });

        $now = now();
        $categories = [
            'pip' => 'trading_calculators',
            'position' => 'trading_calculators',
            'profit' => 'trading_calculators',
            'margin' => 'trading_calculators',
            'risk' => 'trading_calculators',
            'cost' => 'trading_calculators',
            'pivot' => 'technical_analysis',
            'fibonacci' => 'technical_analysis',
            'converter' => 'market_tools',
            'live-markets' => 'market_tools',
        ];

        foreach ($categories as $slug => $category) {
            DB::table('trading_tools')->where('slug', $slug)->update([
                'category' => $category,
                'updated_at' => $now,
            ]);
        }

        $this->ensureTool([
            'slug' => 'cost',
            'name' => 'Trading Cost Calculator',
            'icon' => 'fas fa-file-invoice-dollar',
            'short_description' => 'Estimate spread, commission, and swap costs',
            'description' => 'Estimate the cost of a forex trade from spread, commission, and overnight swap.',
            'category' => 'trading_calculators',
            'sort_order' => 6,
        ]);

        $this->ensureTool([
            'slug' => 'live-markets',
            'name' => 'Live Market Widgets',
            'icon' => 'fas fa-chart-area',
            'short_description' => 'Live FX rates, heatmap, and economic calendar',
            'description' => 'Track live FX crosses, heatmap strength, and upcoming economic events.',
            'category' => 'market_tools',
            'sort_order' => 10,
        ]);

        $this->shiftSortOrder('pivot', 7);
        $this->shiftSortOrder('fibonacci', 8);
        $this->shiftSortOrder('converter', 9);

        $idBySlug = DB::table('trading_tools')->pluck('id', 'slug');
        $related = [
            'pip' => ['position', 'profit', 'margin', 'cost'],
            'position' => ['risk', 'profit', 'margin'],
            'profit' => ['pip', 'cost', 'position'],
            'margin' => ['position', 'risk', 'cost'],
            'risk' => ['position', 'profit', 'margin'],
            'cost' => ['pip', 'profit', 'position'],
            'pivot' => ['fibonacci'],
            'fibonacci' => ['pivot'],
            'converter' => ['pip', 'cost'],
            'live-markets' => ['converter', 'pip', 'cost'],
        ];

        foreach ($related as $slug => $keys) {
            $ids = [];
            foreach ($keys as $key) {
                if (isset($idBySlug[$key])) {
                    $ids[] = (int) $idBySlug[$key];
                }
            }

            if ($ids === [] || ! isset($idBySlug[$slug])) {
                continue;
            }

            DB::table('trading_tools')->where('slug', $slug)->update([
                'related_tool_ids' => json_encode($ids),
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('trading_tools')) {
            return;
        }

        Schema::table('trading_tools', function (Blueprint $table) {
            foreach ([
                'related_broker_ids',
                'related_tool_ids',
                'faqs',
                'additional_explanation',
                'example',
                'formula',
                'how_to_use',
                'introduction',
                'og_description',
                'og_title',
                'canonical_url',
                'meta_description',
                'seo_title',
                'category',
            ] as $column) {
                if (Schema::hasColumn('trading_tools', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /** @param array<string, mixed> $tool */
    private function ensureTool(array $tool): void
    {
        if (DB::table('trading_tools')->where('slug', $tool['slug'])->exists()) {
            return;
        }

        $now = now();
        DB::table('trading_tools')->insert(array_merge($tool, [
            'is_active' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]));
    }

    private function shiftSortOrder(string $slug, int $order): void
    {
        DB::table('trading_tools')->where('slug', $slug)->update([
            'sort_order' => $order,
            'updated_at' => now(),
        ]);
    }
};
