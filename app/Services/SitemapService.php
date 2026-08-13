<?php

namespace App\Services;

use App\Http\Controllers\Front\BrokerController;
use App\Models\Author;
use App\Models\Broker;
use App\Models\BrokerGuide;
use App\Models\CmsPage;
use App\Models\ForexBonus;
use App\Models\Post;
use App\Models\PropFirm;
use App\Models\PropFirmCategory;
use App\Models\SubCategory;
use App\Models\TradingTool;
use App\Support\AwardTaxonomy;
use App\Support\BrokerTaxonomy;
use App\Support\CmsSectionRegistry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

class SitemapService
{
    private const CACHE_KEY = 'sitemap_xml_v1';

    private const CACHE_TTL = 3600;

    public function toXml(): string
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return $this->render($this->urls());
        });
    }

    /** @return array<string, array{loc: string, lastmod: string, changefreq: string, priority: string}> */
    public function urls(): array
    {
        $urls = [];

        $this->addStatic($urls);
        $this->addBrokers($urls);
        $this->addGuides($urls);
        $this->addBestBrokerLists($urls);
        $this->addCountries($urls);
        $this->addComparisons($urls);
        $this->addPromotions($urls);
        $this->addPropFirms($urls);
        $this->addContent($urls);
        $this->addToolsAndAwards($urls);

        return $urls;
    }

    /** @param array<string, array{loc: string, lastmod: string, changefreq: string, priority: string}> $urls */
    private function addStatic(array &$urls): void
    {
        $now = now();

        $this->push($urls, route('home'), $now, 'daily', '1.0');
        $this->push($urls, route('broker.reviews.index'), $now, 'daily', '0.9');
        $this->push($urls, route('brokers.best.index'), $now, 'weekly', '0.8');
        $this->push($urls, route('find_my_broker'), $now, 'weekly', '0.8');
        $this->push($urls, route('broker.comparison'), $now, 'weekly', '0.8');
        $this->push($urls, route('promotions.index'), $now, 'daily', '0.8');
        $this->push($urls, route('scam_brokers'), $now, 'weekly', '0.7');
        $this->push($urls, route('broker.scam_checker'), $now, 'weekly', '0.6');
        $this->push($urls, route('regulated_brokers'), $now, 'weekly', '0.6');
        $this->push($urls, route('prop_firms.index'), $now, 'weekly', '0.7');
        $this->push($urls, route('blog'), $now, 'daily', '0.7');
        $this->push($urls, route('authors'), $now, 'monthly', '0.5');
        $this->push($urls, route('awards.index'), $now, 'monthly', '0.6');
        $this->push($urls, route('trading.tools'), $now, 'monthly', '0.6');
        $this->push($urls, route('about'), $now, 'monthly', '0.4');
        $this->push($urls, route('methodology'), $now, 'monthly', '0.5');
        $this->push($urls, route('contact'), $now, 'yearly', '0.3');
        $this->push($urls, route('terms'), $now, 'yearly', '0.2');
        $this->push($urls, route('privacy'), $now, 'yearly', '0.2');
        $this->push($urls, route('disclaimer'), $now, 'yearly', '0.2');
    }

    /** @param array<string, array{loc: string, lastmod: string, changefreq: string, priority: string}> $urls */
    private function addBrokers(array &$urls): void
    {
        $this->safe(function () use (&$urls) {
            if (! Schema::hasTable('brokers')) {
                return;
            }

            Broker::query()
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->orderBy('id')
                ->get(['slug', 'name', 'is_scam', 'updated_at'])
                ->each(function (Broker $broker) use (&$urls) {
                    $this->push(
                        $urls,
                        route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]),
                        $broker->updated_at,
                        'weekly',
                        '0.9'
                    );

                    if ($broker->is_scam) {
                        $this->push(
                            $urls,
                            route('scam_broker_detail', ['slug' => $broker->scam_slug]),
                            $broker->updated_at,
                            'monthly',
                            '0.5'
                        );
                    }
                });
        });
    }

    /** @param array<string, array{loc: string, lastmod: string, changefreq: string, priority: string}> $urls */
    private function addGuides(array &$urls): void
    {
        $this->safe(function () use (&$urls) {
            if (! Schema::hasTable('broker_guides')) {
                return;
            }

            BrokerGuide::query()
                ->published()
                ->with(['broker:id,slug,name', 'topic:id,slug,is_active'])
                ->orderBy('id')
                ->get()
                ->each(function (BrokerGuide $guide) use (&$urls) {
                    if (! $guide->broker || ! $guide->topic?->is_active || $guide->topicSlug() === '') {
                        return;
                    }

                    $this->push(
                        $urls,
                        app(BrokerGuideService::class)->publicUrl($guide),
                        $guide->updated_at,
                        'monthly',
                        '0.6'
                    );
                });
        });
    }

    /** @param array<string, array{loc: string, lastmod: string, changefreq: string, priority: string}> $urls */
    private function addBestBrokerLists(array &$urls): void
    {
        $now = now();

        foreach (array_keys(BrokerTaxonomy::categories()) as $slug) {
            $this->push($urls, route('brokers.best', ['slug' => $slug]), $now, 'weekly', '0.7');
        }

        foreach (array_keys(BrokerTaxonomy::regions()) as $slug) {
            $this->push($urls, route('brokers.best', ['slug' => $slug]), $now, 'weekly', '0.6');
        }
    }

    /** @param array<string, array{loc: string, lastmod: string, changefreq: string, priority: string}> $urls */
    private function addCountries(array &$urls): void
    {
        $now = now();

        foreach (BrokerTaxonomy::countriesWithFlags() as $slug => $meta) {
            if ($slug === 'global') {
                continue;
            }

            $this->push($urls, route('brokers.best', ['slug' => $slug]), $now, 'weekly', '0.7');
        }
    }

    /** @param array<string, array{loc: string, lastmod: string, changefreq: string, priority: string}> $urls */
    private function addComparisons(array &$urls): void
    {
        $this->safe(function () use (&$urls) {
            foreach (app(FooterIndexService::class)->popularComparisons() as $pair) {
                if (! empty($pair['url'])) {
                    $this->push($urls, $pair['url'], now(), 'weekly', '0.7');
                }
            }
        });
    }

    /** @param array<string, array{loc: string, lastmod: string, changefreq: string, priority: string}> $urls */
    private function addPromotions(array &$urls): void
    {
        foreach (array_keys(PromotionsIndexService::tabs()) as $tab) {
            $this->push($urls, route('promotions.tab', ['type' => $tab]), now(), 'daily', '0.6');
        }

        $this->safe(function () use (&$urls) {
            if (! Schema::hasTable('forex_bonuses')) {
                return;
            }

            ForexBonus::query()
                ->orderByDesc('id')
                ->get()
                ->each(function (ForexBonus $bonus) use (&$urls) {
                    $url = $bonus->detailUrl();
                    if (! $url) {
                        return;
                    }

                    $this->push($urls, $url, $bonus->updated_at, 'weekly', '0.5');
                });
        });
    }

    /** @param array<string, array{loc: string, lastmod: string, changefreq: string, priority: string}> $urls */
    private function addPropFirms(array &$urls): void
    {
        $this->safe(function () use (&$urls) {
            if (! Schema::hasTable('prop_firms')) {
                return;
            }

            PropFirm::query()
                ->where('is_active', true)
                ->whereNotNull('slug')
                ->orderBy('id')
                ->get(['slug', 'updated_at'])
                ->each(function (PropFirm $firm) use (&$urls) {
                    $this->push($urls, route('prop_firms.show', ['slug' => $firm->slug]), $firm->updated_at, 'weekly', '0.6');
                });
        });

        $this->safe(function () use (&$urls) {
            if (! Schema::hasTable('prop_firm_categories')) {
                return;
            }

            PropFirmCategory::query()
                ->where('is_active', true)
                ->whereNotNull('slug')
                ->orderBy('id')
                ->get(['slug', 'updated_at'])
                ->each(function (PropFirmCategory $category) use (&$urls) {
                    $this->push($urls, route('prop_firms.category', ['slug' => $category->slug]), $category->updated_at, 'weekly', '0.5');
                });
        });
    }

    /** @param array<string, array{loc: string, lastmod: string, changefreq: string, priority: string}> $urls */
    private function addContent(array &$urls): void
    {
        $this->safe(function () use (&$urls) {
            if (! Schema::hasTable('posts') || ! Schema::hasTable('sub_categories')) {
                return;
            }

            Post::query()
                ->with('rSubCategory:id,slug')
                ->orderByDesc('id')
                ->get(['id', 'slug', 'sub_category_id', 'updated_at'])
                ->each(function (Post $post) use (&$urls) {
                    $subSlug = $post->rSubCategory->slug ?? null;
                    if (! $subSlug || ! $post->slug) {
                        return;
                    }

                    $this->push(
                        $urls,
                        route('news_detail', [
                            'subcategory_slug' => $subSlug,
                            'post_slug' => $post->slug,
                        ]),
                        $post->updated_at,
                        'weekly',
                        '0.6'
                    );
                });

            SubCategory::query()
                ->whereNotNull('slug')
                ->orderBy('id')
                ->get(['slug', 'updated_at'])
                ->each(function (SubCategory $sub) use (&$urls) {
                    $this->push($urls, route('category', ['slug' => $sub->slug]), $sub->updated_at, 'weekly', '0.5');
                });
        });

        $this->safe(function () use (&$urls) {
            if (! Schema::hasTable('cms_pages')) {
                return;
            }

            $reserved = CmsSectionRegistry::reservedSlugs();

            CmsPage::query()
                ->where('status', 'published')
                ->orderBy('id')
                ->get(['slug', 'updated_at'])
                ->each(function (CmsPage $page) use (&$urls, $reserved) {
                    if (! $page->slug || in_array($page->slug, $reserved, true)) {
                        return;
                    }

                    $this->push($urls, route('cms_page.show', ['slug' => $page->slug]), $page->updated_at, 'monthly', '0.4');
                });
        });

        $this->safe(function () use (&$urls) {
            if (! Schema::hasTable('authors')) {
                return;
            }

            Author::query()
                ->orderBy('id')
                ->get(['id', 'name', 'updated_at'])
                ->each(function (Author $author) use (&$urls) {
                    $this->push($urls, $author->profileUrl(), $author->updated_at, 'monthly', '0.4');
                });
        });
    }

    /** @param array<string, array{loc: string, lastmod: string, changefreq: string, priority: string}> $urls */
    private function addToolsAndAwards(array &$urls): void
    {
        $this->safe(function () use (&$urls) {
            if (! Schema::hasTable('trading_tools')) {
                return;
            }

            TradingTool::query()
                ->active()
                ->get(['slug', 'updated_at'])
                ->each(function (TradingTool $tool) use (&$urls) {
                    $this->push($urls, route('trading.tools.show', ['slug' => $tool->slug]), $tool->updated_at, 'monthly', '0.5');
                });
        });

        foreach (AwardTaxonomy::routeSlugs() as $slug) {
            $this->push($urls, route('awards.show', ['award' => $slug]), now(), 'monthly', '0.5');
        }
    }

    /**
     * @param  array<string, array{loc: string, lastmod: string, changefreq: string, priority: string}>  $urls
     */
    private function push(array &$urls, string $loc, mixed $lastmod, string $changefreq, string $priority): void
    {
        $urls[$loc] = [
            'loc' => $loc,
            'lastmod' => Carbon::parse($lastmod ?? now())->toAtomString(),
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    private function safe(callable $callback): void
    {
        try {
            $callback();
        } catch (Throwable) {
            // Keep the sitemap available even if an optional table is missing.
        }
    }

    /** @param array<string, array{loc: string, lastmod: string, changefreq: string, priority: string}> $urls */
    private function render(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc>\n";
            $xml .= '    <lastmod>' . $url['lastmod'] . "</lastmod>\n";
            $xml .= '    <changefreq>' . $url['changefreq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $url['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
