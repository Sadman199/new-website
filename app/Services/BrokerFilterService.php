<?php

namespace App\Services;

use App\Models\Broker;
use App\Support\FindMyBrokerFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BrokerFilterService
{
    public function __construct(
        protected BrokerReviewsIndexService $reviewsIndexService,
        protected BrokerAssessmentService $assessmentService,
    ) {}

    public function filter(Request $request): array
    {
        $catalogs = FindMyBrokerFilters::catalogs();
        $filters = $this->extractFilters($request);
        $query = Broker::query()
            ->where('is_scam', false)
            ->with(['accountOptions'])
            ->withCount(['reviews as approved_review_count' => fn ($q) => $q->where('status', 1)]);

        $this->applyFilters($query, $filters);
        $this->applySort($query, $filters['sort'] ?? 'highest_rated');

        $brokers = $query->paginate(20)->withQueryString();
        $brokers->getCollection()->transform(fn (Broker $broker) => $this->serializeCard($broker));

        $matchSlugs = FindMyBrokerFilters::parseList($request->get('match'));
        $fromQuiz = $request->query('from') === 'quiz';

        if ($matchSlugs !== [] && $brokers->currentPage() === 1) {
            $order = array_flip($matchSlugs);
            $collection = $brokers->getCollection()
                ->sortBy(fn (array $broker) => $order[$broker['slug']] ?? 1000)
                ->values()
                ->map(function (array $broker) use ($matchSlugs) {
                    $broker['is_match'] = in_array($broker['slug'], $matchSlugs, true);
                    $broker['is_best_match'] = ($matchSlugs[0] ?? null) === ($broker['slug'] ?? null);

                    return $broker;
                });
            $brokers->setCollection($collection);
        }

        $activeChips = $this->buildActiveChips($filters, $catalogs);
        $activeLabels = array_column($activeChips, 'label');

        $canonicalQuery = FindMyBrokerFilters::buildCanonicalQuery($request->query());
        $canonicalUrl = url('/find-my-broker') . ($canonicalQuery ? '?' . $canonicalQuery : '');

        return [
            'brokers' => $brokers,
            'catalogs' => $catalogs,
            'filters' => $filters,
            'activeChips' => $activeChips,
            'activeLabels' => $activeLabels,
            'canonicalUrl' => $canonicalUrl,
            'seoTitle' => FindMyBrokerFilters::seoTitle($activeLabels),
            'seoDescription' => FindMyBrokerFilters::seoDescription($activeLabels, $brokers->total()),
            'total' => $brokers->total(),
            'pageStats' => $this->pageStats(),
            'quickPresets' => $this->quickPresets(),
            'fromQuiz' => $fromQuiz,
            'matchSlugs' => $matchSlugs,
        ];
    }

    /** @return array<string, mixed> */
    protected function serializeCard(Broker $broker): array
    {
        $data = $this->reviewsIndexService->serialize($broker);
        $data['performance'] = $this->assessmentService->cardMetrics($broker);
        $data['minimum_deposit'] = $broker->minimum_deposit !== null
            ? '$' . number_format((float) $broker->minimum_deposit, 0)
            : '—';
        $data['leverage'] = $broker->leverage ?: '—';
        $data['platforms'] = implode(', ', array_slice($broker->platformList(), 0, 3)) ?: '—';
        $data['regulatory_tier'] = $broker->regulatory_tier ? 'Tier ' . $broker->regulatory_tier : null;
        $data['is_featured'] = (bool) $broker->featured_broker;

        return $data;
    }

    /** @return array<string, int|float|string> */
    protected function pageStats(): array
    {
        $base = Broker::query()->where('is_scam', false);

        return [
            'total' => (clone $base)->count(),
            'regulated' => (clone $base)->whereNotNull('regulation')->where('regulation', '!=', '')->count(),
            'with_demo' => (clone $base)->where('demo_account_available', true)->count(),
            'avg_rating' => round((float) (clone $base)->whereNotNull('rating')->avg('rating'), 1) ?: '—',
        ];
    }

    /** @return array<int, array<string, string>> */
    protected function quickPresets(): array
    {
        return [
            ['label' => 'Low deposit ($10)', 'url' => route('find_my_broker', ['min_deposit' => 10])],
            ['label' => 'CySEC regulated', 'url' => route('find_my_broker', ['regulation' => 'cysec'])],
            ['label' => 'MetaTrader 5', 'url' => route('find_my_broker', ['platform' => 'mt5'])],
            ['label' => 'High leverage', 'url' => route('find_my_broker', ['leverage' => 1000])],
            ['label' => 'Low spreads', 'url' => route('find_my_broker', ['spread' => 'low'])],
            ['label' => 'Copy trading', 'url' => route('find_my_broker', ['features' => 'copy_trading'])],
            ['label' => 'Zero commission', 'url' => route('find_my_broker', ['commission' => 'zero'])],
            ['label' => '4+ star rating', 'url' => route('find_my_broker', ['rating' => 4])],
        ];
    }

    public function extractFilters(Request $request): array
    {
        return [
            'q' => trim((string) $request->get('q', '')),
            'min_deposit' => (string) $request->get('min_deposit', ''),
            'account_type' => FindMyBrokerFilters::parseList($request->get('account_type')),
            'regulation' => FindMyBrokerFilters::parseList($request->get('regulation')),
            'platform' => FindMyBrokerFilters::parseList($request->get('platform')),
            'leverage' => (string) $request->get('leverage', ''),
            'spread' => (string) $request->get('spread', ''),
            'commission' => (string) $request->get('commission', ''),
            'markets' => FindMyBrokerFilters::parseList($request->get('markets')),
            'payment' => FindMyBrokerFilters::parseList($request->get('payment')),
            'features' => FindMyBrokerFilters::parseList($request->get('features')),
            'deposit_bonus' => (string) $request->get('deposit_bonus', ''),
            'country' => FindMyBrokerFilters::parseList($request->get('country')),
            'rating' => (string) $request->get('rating', ''),
            'sort' => (string) ($request->get('sort') ?: 'highest_rated'),
        ];
    }

    protected function applyFilters(Builder $query, array $filters): void
    {
        if ($filters['q'] !== '') {
            $q = $filters['q'];
            $query->where(function (Builder $sub) use ($q) {
                $sub->where('name', 'LIKE', '%' . $q . '%')
                    ->orWhere('slug', 'LIKE', '%' . $q . '%');
            });
        }

        if ($filters['min_deposit'] !== '' && is_numeric($filters['min_deposit'])) {
            $max = (float) $filters['min_deposit'];
            $query->where(function (Builder $sub) use ($max) {
                $sub->whereNotNull('minimum_deposit')
                    ->where('minimum_deposit', '<=', $max);
            });
        }

        if (!empty($filters['account_type'])) {
            $query->where(function (Builder $sub) use ($filters) {
                foreach ($filters['account_type'] as $slug) {
                    $terms = FindMyBrokerFilters::searchTerms('account_type', $slug);
                    $sub->orWhere(function (Builder $inner) use ($terms) {
                        foreach ($terms as $term) {
                            $inner->orWhereHas('accountOptions', function (Builder $ao) use ($term) {
                                $ao->where('account_type', 'LIKE', '%' . $term . '%');
                            })->orWhere('account_types', 'LIKE', '%' . $term . '%');
                        }
                    });
                }
            });
        }

        if (!empty($filters['regulation'])) {
            $query->where(function (Builder $sub) use ($filters) {
                foreach ($filters['regulation'] as $slug) {
                    $terms = FindMyBrokerFilters::searchTerms('regulation', $slug);
                    foreach ($terms as $term) {
                        $sub->orWhere('regulation', 'LIKE', '%' . $term . '%')
                            ->orWhere('regulated_jurisdictions', 'LIKE', '%' . $term . '%')
                            ->orWhere('regulatory_licenses', 'LIKE', '%' . $term . '%');
                    }
                }
            });
        }

        if (!empty($filters['platform'])) {
            $query->where(function (Builder $sub) use ($filters) {
                foreach ($filters['platform'] as $slug) {
                    $terms = FindMyBrokerFilters::searchTerms('platform', $slug);
                    foreach ($terms as $term) {
                        $sub->orWhere('platforms', 'LIKE', '%' . $term . '%');
                    }
                }
            });
        }

        if ($filters['leverage'] !== '' && is_numeric($filters['leverage'])) {
            $minLev = (float) $filters['leverage'];
            $query->where(function (Builder $sub) use ($minLev) {
                $sub->whereHas('accountOptions', function (Builder $ao) use ($minLev) {
                    $ao->where('max_leverage_numeric', '>=', $minLev);
                })->orWhere(function (Builder $lev) use ($minLev) {
                    // leverage stored as text like "1:500" or "500:1"
                    $lev->whereRaw(
                        "CAST(REPLACE(REPLACE(REPLACE(COALESCE(leverage, '0'), '1:', ''), ':1', ''), ' ', '') AS UNSIGNED) >= ?",
                        [$minLev]
                    );
                });
            });
        }

        if ($filters['spread'] !== '') {
            $terms = FindMyBrokerFilters::searchTerms('spread', $filters['spread']);
            $query->where(function (Builder $sub) use ($terms, $filters) {
                foreach ($terms as $term) {
                    $sub->orWhere('spreads', 'LIKE', '%' . $term . '%')
                        ->orWhereHas('accountOptions', function (Builder $ao) use ($term) {
                            $ao->where('spread_type', 'LIKE', '%' . $term . '%');
                        });
                }
                if ($filters['spread'] === 'low') {
                    $sub->orWhereHas('accountOptions', function (Builder $ao) {
                        $ao->where(function (Builder $spread) {
                            $spread->where(function (Builder $from) {
                                $from->whereNotNull('spread_from_pips')->where('spread_from_pips', '<=', 1.2);
                            })->orWhere(function (Builder $legacy) {
                                $legacy->whereNull('spread_from_pips')
                                    ->whereNotNull('spread_value')
                                    ->where('spread_value', '<=', 1.2);
                            });
                        });
                    });
                }
            });
        }

        if ($filters['commission'] === 'zero') {
            $query->whereHas('accountOptions', function (Builder $ao) {
                $ao->where(function (Builder $c) {
                    $c->whereNull('commission_per_lot')->where(function (Builder $label) {
                        $label->whereNull('commission')
                            ->orWhere('commission', '0')
                            ->orWhere('commission', 'like', '%none%')
                            ->orWhere('commission', 'like', '%free%');
                    });
                });
            });
        } elseif ($filters['commission'] === 'low') {
            $query->whereHas('accountOptions', function (Builder $ao) {
                $ao->whereNotNull('commission_per_lot')
                    ->where('commission_per_lot', '>', 0)
                    ->where('commission_per_lot', '<=', 7);
            });
        }

        if (!empty($filters['markets'])) {
            $query->where(function (Builder $sub) use ($filters) {
                foreach ($filters['markets'] as $slug) {
                    $terms = FindMyBrokerFilters::searchTerms('markets', $slug);
                    foreach ($terms as $term) {
                        $sub->orWhere('short_description', 'LIKE', '%' . $term . '%')
                            ->orWhere('markets', 'LIKE', '%' . $term . '%');
                    }
                }
            });
        }

        if (!empty($filters['payment'])) {
            $query->where(function (Builder $sub) use ($filters) {
                foreach ($filters['payment'] as $slug) {
                    $terms = FindMyBrokerFilters::searchTerms('payment', $slug);
                    foreach ($terms as $term) {
                        $sub->orWhere('deposit_methods', 'LIKE', '%' . $term . '%')
                            ->orWhere('withdrawal_method', 'LIKE', '%' . $term . '%')
                            ->orWhere('payment_methods', 'LIKE', '%' . $term . '%');
                    }
                }
            });
        }

        if (!empty($filters['features'])) {
            foreach ($filters['features'] as $feature) {
                if ($feature === 'vps') {
                    $query->where(function (Builder $sub) {
                        $sub->where('vps_hosting', true)
                            ->orWhere('vps_hosting', 1)
                            ->orWhere('educational_resources', 'LIKE', '%vps%')
                            ->orWhere('top_feature', 'LIKE', '%vps%');
                    });
                } elseif ($feature === 'copy_trading') {
                    $query->where(function (Builder $sub) {
                        $sub->where('account_types', 'LIKE', '%copy-trading%')
                            ->orWhere('account_types', 'LIKE', '%copytrading%')
                            ->orWhere('social_trading', 'LIKE', '%copy%')
                            ->orWhere('social_trading', 'LIKE', '%Copy%');
                    });
                } elseif ($feature === 'ea_support') {
                    $query->where(function (Builder $sub) {
                        $terms = FindMyBrokerFilters::searchTerms('features', 'ea_support');
                        foreach ($terms as $term) {
                            $sub->orWhere('platforms', 'LIKE', '%' . $term . '%')
                                ->orWhere('top_feature', 'LIKE', '%' . $term . '%')
                                ->orWhere('short_description', 'LIKE', '%' . $term . '%')
                                ->orWhere('pros', 'LIKE', '%' . $term . '%');
                        }
                    });
                }
            }
        }

        if ($filters['deposit_bonus'] === '1') {
            $query->whereHas('accountOptions', function (Builder $ao) {
                $ao->where(function (Builder $b) {
                    $b->where('bonus_eligibility', true)
                        ->orWhere('bonus_eligibility', 1)
                        ->orWhere('bonus_eligibility', '1')
                        ->orWhere('bonus_eligibility', 'yes')
                        ->orWhere('bonus_eligibility', 'Yes');
                });
            });
        }

        if (!empty($filters['country'])) {
            $query->where(function (Builder $sub) use ($filters) {
                foreach ($filters['country'] as $slug) {
                    $sub->orWhere('associated_countries', 'LIKE', '%' . $slug . '%')
                        ->orWhere('country', 'LIKE', '%' . str_replace('-', ' ', $slug) . '%');
                }
            });
        }

        if ($filters['rating'] !== '' && is_numeric($filters['rating'])) {
            $query->where('rating', '>=', (float) $filters['rating']);
        }
    }

    protected function applySort(Builder $query, string $sort): void
    {
        switch ($sort) {
            case 'lowest_deposit':
                $query->orderByRaw('CASE WHEN minimum_deposit IS NULL THEN 1 ELSE 0 END')
                    ->orderBy('minimum_deposit', 'asc')
                    ->orderBy('rating', 'desc');
                break;

            case 'lowest_spread':
                $query->withMin('accountOptions', 'spread_value')
                    ->orderByRaw('account_options_min_spread_value IS NULL')
                    ->orderBy('account_options_min_spread_value', 'asc')
                    ->orderBy('rating', 'desc');
                break;

            case 'most_popular':
                $query->orderByDesc('featured_broker')
                    ->orderByDesc('top_broker')
                    ->orderBy('rating', 'desc')
                    ->orderBy('id', 'desc');
                break;

            case 'newest':
                $query->orderBy('id', 'desc');
                break;

            case 'highest_rated':
            default:
                $query->orderBy('rating', 'desc')->orderBy('id', 'desc');
                break;
        }
    }

    protected function buildActiveChips(array $filters, array $catalogs): array
    {
        $chips = [];

        if ($filters['q'] !== '') {
            $chips[] = ['key' => 'q', 'value' => $filters['q'], 'label' => '“' . $filters['q'] . '”'];
        }

        if ($filters['min_deposit'] !== '' && isset($catalogs['min_deposit'][$filters['min_deposit']])) {
            $chips[] = [
                'key' => 'min_deposit',
                'value' => $filters['min_deposit'],
                'label' => $catalogs['min_deposit'][$filters['min_deposit']],
            ];
        }

        foreach (['account_type', 'regulation', 'platform', 'markets', 'payment', 'features', 'country'] as $group) {
            foreach ($filters[$group] as $slug) {
                if (!isset($catalogs[$group][$slug])) {
                    continue;
                }
                $chips[] = [
                    'key' => $group,
                    'value' => $slug,
                    'label' => $catalogs[$group][$slug],
                ];
            }
        }

        foreach (['leverage', 'spread', 'commission', 'deposit_bonus', 'rating'] as $single) {
            if ($filters[$single] === '' || !isset($catalogs[$single][$filters[$single]])) {
                continue;
            }
            if ($catalogs[$single][$filters[$single]] === 'Any' || str_starts_with($catalogs[$single][$filters[$single]], 'Any ')) {
                continue;
            }
            $chips[] = [
                'key' => $single,
                'value' => $filters[$single],
                'label' => $catalogs[$single][$filters[$single]],
            ];
        }

        return $chips;
    }
}
