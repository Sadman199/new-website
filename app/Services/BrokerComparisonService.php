<?php

namespace App\Services;

use App\Http\Controllers\Front\BrokerController;
use App\Models\Broker;
use App\Models\ForexBonus;
use Illuminate\Support\Collection;

class BrokerComparisonService
{
    public function __construct(
        protected BrokerSafetyScoreService $safetyScoreService,
        protected PromotionsIndexService $promotionsIndexService,
    ) {    }

    public static function canonicalPairUrl(string $slug1, string $slug2): string
    {
        $slugs = collect([$slug1, $slug2])->filter()->sort()->values();

        return route('brokers.compare', [
            'broker1_slug' => $slugs[0],
            'broker2_slug' => $slugs[1],
        ]);
    }

    /** @return array<string, mixed> */
    public function buildPairComparison(Broker $broker1, Broker $broker2): array
    {
        $broker1->loadCount(['reviews as approved_review_count' => fn ($q) => $q->where('status', 1)]);
        $broker2->loadCount(['reviews as approved_review_count' => fn ($q) => $q->where('status', 1)]);

        $left = $this->serializeBroker($broker1);
        $right = $this->serializeBroker($broker2);

        $left['safety'] = $this->safetyScoreService->analyze($broker1);
        $right['safety'] = $this->safetyScoreService->analyze($broker2);
        $left['category_scores'] = $this->categoryScores($broker1);
        $right['category_scores'] = $this->categoryScores($broker2);

        $sections = $this->buildResultSections($left, $right);

        return [
            'broker1' => $left,
            'broker2' => $right,
            'sections' => $sections,
            'summary' => $this->quickSummary($broker1, $broker2, $left, $right),
            'overall_winner' => $this->overallWinner($left, $right),
            'score_bars' => $this->scoreBars($left, $right),
            'promotions' => [
                'broker1' => $this->activePromoForBroker($broker1),
                'broker2' => $this->activePromoForBroker($broker2),
            ],
            'toc' => collect($sections)->map(fn ($section) => [
                'id' => $section['id'],
                'label' => $section['label'],
            ])->all(),
        ];
    }

    /** @return array<int, array<string, mixed>> */
    protected function scoreBars(array $left, array $right): array
    {
        $labels = [
            'fees' => 'Fees & costs',
            'safety' => 'Safety & regulation',
            'platforms' => 'Platforms',
            'deposit_withdrawal' => 'Deposits & withdrawals',
            'customer_support' => 'Customer support',
            'education' => 'Education',
        ];

        $bars = [];

        foreach ($labels as $key => $label) {
            $lScore = $left['category_scores'][$key] ?? null;
            $rScore = $right['category_scores'][$key] ?? null;

            if ($lScore === null && $rScore === null) {
                continue;
            }

            $bars[] = [
                'key' => $key,
                'label' => $label,
                'left' => $lScore,
                'right' => $rScore,
                'left_pct' => $lScore !== null ? min(100, max(0, ($lScore / 10) * 100)) : 0,
                'right_pct' => $rScore !== null ? min(100, max(0, ($rScore / 10) * 100)) : 0,
                'winner' => $this->winnerHigher($lScore, $rScore),
            ];
        }

        return $bars;
    }

    /** @return array<string, mixed>|null */
    protected function activePromoForBroker(Broker $broker): ?array
    {
        $bonus = ForexBonus::query()
            ->where('broker_id', $broker->id)
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhereDate('expiry_date', '>=', now());
            })
            ->where(function ($query) {
                $query->whereNull('promotion_status')
                    ->orWhereIn('promotion_status', ['ongoing', 'limited-time']);
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('publish_date')
            ->first();

        if (! $bonus || ! $bonus->isActivePromotion()) {
            return null;
        }

        return $this->promotionsIndexService->serializeCard($bonus);
    }

    /** @return array<int, array<string, mixed>> */
    protected function buildResultSections(array $left, array $right): array
    {
        $sections = [
            [
                'id' => 'overview',
                'label' => 'Overview',
                'rows' => [
                    $this->row('Overall rating', $left['rating_display'], $right['rating_display'], $this->winnerHigher($left['rating'], $right['rating'])),
                    $this->row('Safety score', ($left['safety']['overall_score'] ?? '—') . '/100', ($right['safety']['overall_score'] ?? '—') . '/100', $this->winnerHigher($left['safety']['overall_score'] ?? null, $right['safety']['overall_score'] ?? null)),
                    $this->row('Risk status', $left['safety']['risk_label'] ?? '—', $right['safety']['risk_label'] ?? '—', null),
                    $this->row('Trust score', (string) $left['trust_score'], (string) $right['trust_score'], $this->winnerHigher($this->numeric($left['trust_score']), $this->numeric($right['trust_score']))),
                    $this->row('User reviews', (string) $left['review_count'], (string) $right['review_count'], $this->winnerHigher($left['review_count'], $right['review_count'])),
                    $this->row('Founded', (string) $left['year_founded'], (string) $right['year_founded'], null),
                    $this->row('Headquarters', $left['country'], $right['country'], null),
                    $this->row('Top feature', $left['top_feature'], $right['top_feature'], null),
                ],
            ],
            [
                'id' => 'regulation',
                'label' => 'Regulation & Safety',
                'rows' => [
                    $this->row('Regulators', $left['regulation'], $right['regulation'], null),
                    $this->row('Regulatory tier', $left['regulatory_tier'], $right['regulatory_tier'], $this->winnerLowerTier($left['regulatory_tier'], $right['regulatory_tier'])),
                    $this->row('Broker type', $left['broker_type'], $right['broker_type'], $this->winnerRegulated($left['broker_type'], $right['broker_type'])),
                    $this->row('Investor protection', $left['investor_protection'], $right['investor_protection'], $this->winnerBool($left['investor_protection'], $right['investor_protection'])),
                    $this->row('Segregated funds', $left['segregation_of_funds'], $right['segregation_of_funds'], $this->winnerBool($left['segregation_of_funds'], $right['segregation_of_funds'])),
                    $this->row('Negative balance protection', $left['negative_balance_protection'], $right['negative_balance_protection'], $this->winnerBool($left['negative_balance_protection'], $right['negative_balance_protection'])),
                ],
            ],
            [
                'id' => 'trading',
                'label' => 'Trading Conditions',
                'rows' => [
                    $this->row('Minimum deposit', $left['minimum_deposit'], $right['minimum_deposit'], $this->winnerLower($this->numeric($left['minimum_deposit_raw']), $this->numeric($right['minimum_deposit_raw']))),
                    $this->row('Average spreads', $left['spreads'], $right['spreads'], null),
                    $this->row('Commission', $left['commission'], $right['commission'], null),
                    $this->row('Maximum leverage', $left['leverage'], $right['leverage'], $this->winnerLeverage($left['leverage'], $right['leverage'])),
                    $this->row('Fee level', $left['fee_level'], $right['fee_level'], null),
                    $this->row('Withdrawal fee', $left['withdrawal_fee'], $right['withdrawal_fee'], null),
                ],
            ],
            [
                'id' => 'accounts',
                'label' => 'Accounts & Markets',
                'rows' => [
                    $this->row('Account types', $left['account_types'], $right['account_types'], null),
                    $this->row('Tradable instruments', $left['instrument_count'] ? $left['instrument_count'] . '+' : '—', $right['instrument_count'] ? $right['instrument_count'] . '+' : '—', $this->winnerHigher($left['instrument_count'], $right['instrument_count'])),
                    $this->row('Markets', $left['markets'], $right['markets'], null),
                ],
            ],
            [
                'id' => 'platforms',
                'label' => 'Platforms & Tools',
                'rows' => [
                    $this->row('Trading platforms', $left['platforms'], $right['platforms'], null),
                    $this->row('Mobile trading', $left['mobile_trading'], $right['mobile_trading'], $this->winnerBool($left['mobile_trading'], $right['mobile_trading'])),
                    $this->row('Web trader', $left['web_trader'], $right['web_trader'], $this->winnerBool($left['web_trader'], $right['web_trader'])),
                    $this->row('VPS hosting', $left['vps_hosting'], $right['vps_hosting'], $this->winnerBool($left['vps_hosting'], $right['vps_hosting'])),
                    $this->row('Social trading', $left['social_trading'], $right['social_trading'], $this->winnerBool($left['social_trading'], $right['social_trading'])),
                ],
            ],
            [
                'id' => 'payments',
                'label' => 'Deposits & Withdrawals',
                'rows' => [
                    $this->row('Deposit methods', $left['deposit_methods'], $right['deposit_methods'], null),
                    $this->row('Withdrawal methods', $left['withdrawal_method'], $right['withdrawal_method'], null),
                    $this->row('Payment methods', $left['payment_methods'], $right['payment_methods'], null),
                ],
            ],
            [
                'id' => 'support',
                'label' => 'Support & Service',
                'rows' => [
                    $this->row('Support channels', $left['customer_support'], $right['customer_support'], null),
                    $this->row('Languages', $left['languages'], $right['languages'], null),
                    $this->row('Account managers', $left['account_managers'], $right['account_managers'], $this->winnerBool($left['account_managers'], $right['account_managers'])),
                ],
            ],
        ];

        $scoreRows = [];
        $labels = [
            'fees' => 'Fees & costs',
            'safety' => 'Safety & regulation',
            'platforms' => 'Platforms',
            'deposit_withdrawal' => 'Deposits & withdrawals',
            'customer_support' => 'Customer support',
            'education' => 'Education',
        ];

        foreach ($labels as $key => $label) {
            $lScore = $left['category_scores'][$key] ?? null;
            $rScore = $right['category_scores'][$key] ?? null;

            if ($lScore === null && $rScore === null) {
                continue;
            }

            $scoreRows[] = $this->row(
                $label,
                $lScore !== null ? number_format($lScore, 1) . '/10' : '—',
                $rScore !== null ? number_format($rScore, 1) . '/10' : '—',
                $this->winnerHigher($lScore, $rScore),
            );
        }

        if ($scoreRows !== []) {
            $sections[] = [
                'id' => 'scores',
                'label' => 'Category scores',
                'rows' => $scoreRows,
            ];
        }

        return $sections;
    }

    /** @return array<int, array<string, mixed>> */
    protected function quickSummary(Broker $broker1, Broker $broker2, array $left, array $right): array
    {
        $items = [];

        if ($winner = $this->winnerLower($broker1->minimum_deposit, $broker2->minimum_deposit)) {
            $items[] = [
                'label' => 'Lowest min. deposit',
                'broker' => $winner === 'broker1' ? $left['name'] : $right['name'],
                'value' => '$' . number_format(min((float) $broker1->minimum_deposit, (float) $broker2->minimum_deposit), 0),
                'tone' => 'green',
            ];
        }

        if ($winner = $this->winnerHigher($left['rating'], $right['rating'])) {
            $items[] = [
                'label' => 'Highest rating',
                'broker' => $winner === 'broker1' ? $left['name'] : $right['name'],
                'value' => number_format(max((float) $left['rating'], (float) $right['rating']), 1) . '/5',
                'tone' => 'ocean',
            ];
        }

        if ($winner = $this->winnerHigher($left['safety']['overall_score'] ?? null, $right['safety']['overall_score'] ?? null)) {
            $items[] = [
                'label' => 'Best safety score',
                'broker' => $winner === 'broker1' ? $left['name'] : $right['name'],
                'value' => max($left['safety']['overall_score'] ?? 0, $right['safety']['overall_score'] ?? 0) . '/100',
                'tone' => 'purple',
            ];
        }

        if ($winner = $this->winnerLowerTier($left['regulatory_tier'], $right['regulatory_tier'])) {
            $items[] = [
                'label' => 'Strongest regulation',
                'broker' => $winner === 'broker1' ? $left['name'] : $right['name'],
                'value' => $winner === 'broker1' ? $left['regulatory_tier'] : $right['regulatory_tier'],
                'tone' => 'amber',
            ];
        }

        if ($winner = $this->winnerHigher($left['instrument_count'], $right['instrument_count'])) {
            $items[] = [
                'label' => 'Most instruments',
                'broker' => $winner === 'broker1' ? $left['name'] : $right['name'],
                'value' => max($left['instrument_count'] ?? 0, $right['instrument_count'] ?? 0) . '+',
                'tone' => 'slate',
            ];
        }

        return $items;
    }

    /** @return array{broker: string, name: string, reason: string}|null */
    protected function overallWinner(array $left, array $right): ?array
    {
        $leftPoints = 0;
        $rightPoints = 0;

        foreach ([
            [$this->winnerHigher($left['rating'], $right['rating']), 2],
            [$this->winnerHigher($left['safety']['overall_score'] ?? null, $right['safety']['overall_score'] ?? null), 3],
            [$this->winnerLowerTier($left['regulatory_tier'], $right['regulatory_tier']), 2],
            [$this->winnerLower($this->numeric($left['minimum_deposit_raw']), $this->numeric($right['minimum_deposit_raw'])), 1],
            [$this->winnerHigher($this->numeric($left['trust_score']), $this->numeric($right['trust_score'])), 1],
        ] as [$winner, $weight]) {
            if ($winner === 'broker1') {
                $leftPoints += $weight;
            } elseif ($winner === 'broker2') {
                $rightPoints += $weight;
            }
        }

        if ($leftPoints === $rightPoints) {
            return null;
        }

        $winnerKey = $leftPoints > $rightPoints ? 'broker1' : 'broker2';
        $winner = $winnerKey === 'broker1' ? $left : $right;

        return [
            'broker' => $winnerKey,
            'name' => $winner['name'],
            'reason' => 'Leads on rating, safety, regulation, and overall trading profile in our database.',
        ];
    }

    /** @return array{label: string, left: string, right: string, winner: ?string} */
    protected function row(string $label, string $left, string $right, ?string $winner): array
    {
        return [
            'label' => $label,
            'left' => $left ?: '—',
            'right' => $right ?: '—',
            'winner' => $winner,
        ];
    }

    protected function winnerHigher(mixed $left, mixed $right): ?string
    {
        $left = is_numeric($left) ? (float) $left : null;
        $right = is_numeric($right) ? (float) $right : null;

        if ($left === null || $right === null || $left === $right) {
            return null;
        }

        return $left > $right ? 'broker1' : 'broker2';
    }

    protected function winnerLower(mixed $left, mixed $right): ?string
    {
        $left = is_numeric($left) ? (float) $left : null;
        $right = is_numeric($right) ? (float) $right : null;

        if ($left === null || $right === null || $left === $right) {
            return null;
        }

        return $left < $right ? 'broker1' : 'broker2';
    }

    protected function winnerBool(string $left, string $right): ?string
    {
        $l = $left === 'Yes';
        $r = $right === 'Yes';

        if ($l === $r) {
            return null;
        }

        return $l ? 'broker1' : 'broker2';
    }

    protected function winnerRegulated(string $left, string $right): ?string
    {
        $l = strtolower($left) === 'regulated';
        $r = strtolower($right) === 'regulated';

        if ($l === $r) {
            return null;
        }

        return $l ? 'broker1' : 'broker2';
    }

    protected function winnerLeverage(string $left, string $right): ?string
    {
        $l = $this->leverageNumber($left);
        $r = $this->leverageNumber($right);

        if ($l === null || $r === null || $l === $r) {
            return null;
        }

        return $l > $r ? 'broker1' : 'broker2';
    }

    protected function leverageNumber(string $value): ?float
    {
        if (preg_match('/1\s*:\s*([0-9,.]+)/i', $value, $matches)) {
            return (float) str_replace(',', '', $matches[1]);
        }

        $numeric = preg_replace('/[^0-9.]/', '', $value);

        return $numeric !== '' ? (float) $numeric : null;
    }

    protected function winnerLowerTier(string $left, string $right): ?string
    {
        $l = $this->tierNumber($left);
        $r = $this->tierNumber($right);

        if ($l === null || $r === null || $l === $r) {
            return null;
        }

        return $l < $r ? 'broker1' : 'broker2';
    }

    protected function tierNumber(string $value): ?int
    {
        if (preg_match('/tier\s*(\d)/i', $value, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    protected function numeric(mixed $value): ?float
    {
        if ($value === null || $value === '' || $value === '—') {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        if (preg_match('/([\d,.]+)/', (string) $value, $matches)) {
            return (float) str_replace(',', '', $matches[1]);
        }

        return null;
    }

    /** @return array<string, float> */
    protected function categoryScores(Broker $broker): array
    {
        $scores = is_array($broker->category_scores) ? $broker->category_scores : [];

        return collect($scores)
            ->filter(fn ($score) => is_numeric($score))
            ->map(fn ($score) => (float) $score)
            ->all();
    }

    /** @return array<string, array{label: string, rows: array<int, array{key: string, label: string}>}> */
    public function tabGroups(): array
    {
        return [
            'overall' => [
                'label' => 'Overall',
                'rows' => [
                    ['key' => 'rating', 'label' => 'Reputation and Quality'],
                    ['key' => 'regulation', 'label' => 'Regulation and Compliance'],
                    ['key' => 'platforms', 'label' => 'Trading Platforms'],
                    ['key' => 'spreads', 'label' => 'Trading Cost (Spreads)'],
                    ['key' => 'regulation', 'label' => 'Regulator'],
                    ['key' => 'broker_type', 'label' => 'Broker Type'],
                    ['key' => 'country', 'label' => 'Headquarters'],
                    ['key' => 'year_founded', 'label' => 'Founded'],
                    ['key' => 'minimum_deposit', 'label' => 'Min Deposit'],
                    ['key' => 'leverage', 'label' => 'Max Lev'],
                    ['key' => 'top_feature', 'label' => 'Label'],
                ],
            ],
            'regulation' => [
                'label' => 'Regulation',
                'rows' => [
                    ['key' => 'regulation', 'label' => 'Regulators'],
                    ['key' => 'regulatory_tier', 'label' => 'Regulatory Tier'],
                    ['key' => 'investor_protection', 'label' => 'Investor Protection'],
                    ['key' => 'segregation_of_funds', 'label' => 'Segregation of Funds'],
                    ['key' => 'negative_balance_protection', 'label' => 'Negative Balance Protection'],
                    ['key' => 'trust_score', 'label' => 'Trust Score'],
                ],
            ],
            'account' => [
                'label' => 'Account & Cost',
                'rows' => [
                    ['key' => 'minimum_deposit', 'label' => 'Minimum Deposit'],
                    ['key' => 'spreads', 'label' => 'Average Spreads'],
                    ['key' => 'commission', 'label' => 'Commission'],
                    ['key' => 'leverage', 'label' => 'Maximum Leverage'],
                    ['key' => 'account_types', 'label' => 'Account Types'],
                    ['key' => 'instrument_count', 'label' => 'Tradable Instruments'],
                    ['key' => 'markets', 'label' => 'Markets'],
                ],
            ],
            'deposit' => [
                'label' => 'Deposit & Withdrawal',
                'rows' => [
                    ['key' => 'deposit_methods', 'label' => 'Deposit Methods'],
                    ['key' => 'withdrawal_method', 'label' => 'Withdrawal Methods'],
                    ['key' => 'withdrawal_fee', 'label' => 'Withdrawal Fee'],
                    ['key' => 'payment_methods', 'label' => 'Payment Methods'],
                ],
            ],
            'company' => [
                'label' => 'Company and Service',
                'rows' => [
                    ['key' => 'country', 'label' => 'Headquarters'],
                    ['key' => 'year_founded', 'label' => 'Year Founded'],
                    ['key' => 'languages', 'label' => 'Languages'],
                    ['key' => 'customer_support', 'label' => 'Customer Support'],
                    ['key' => 'mobile_trading', 'label' => 'Mobile Trading'],
                    ['key' => 'web_trader', 'label' => 'Web Trader'],
                    ['key' => 'vps_hosting', 'label' => 'VPS Hosting'],
                    ['key' => 'social_trading', 'label' => 'Social Trading'],
                ],
            ],
            'reviews' => [
                'label' => 'User Reviews',
                'rows' => [
                    ['key' => 'rating', 'label' => 'Overall Rating'],
                    ['key' => 'review_count', 'label' => 'User Reviews'],
                    ['key' => 'trust_score', 'label' => 'Trust Score'],
                ],
            ],
        ];
    }

    public function suggestedBrokers(int $limit = 6): Collection
    {
        return Broker::query()
            ->where('is_scam', false)
            ->orderByDesc('rating')
            ->orderByDesc('featured_broker')
            ->limit($limit)
            ->get();
    }

    /** @return Collection<int, Broker> */
    public function allBrokersForCompare(): Collection
    {
        return Broker::query()
            ->where('is_scam', false)
            ->withCount(['reviews as approved_review_count' => function ($query) {
                $query->where('status', 1);
            }])
            ->orderBy('name')
            ->get();
    }

    /** @return array<string, mixed> */
    public function serializeBroker(Broker $broker): array
    {
        $accountTypes = is_array($broker->account_types)
            ? $broker->account_types
            : (is_string($broker->account_types) && $broker->account_types !== ''
                ? json_decode($broker->account_types, true) ?? []
                : []);

        return [
            'id' => $broker->id,
            'name' => $broker->name,
            'slug' => $broker->slug,
            'logo' => $broker->logo ? asset($broker->logo) : null,
            'og_image' => $broker->ogShareImageUrl(),
            'rating' => $broker->rating !== null ? (float) $broker->rating : null,
            'regulation' => implode(', ', $broker->regulationList()) ?: '—',
            'regulatory_tier' => $broker->regulatory_tier ? 'Tier ' . $broker->regulatory_tier : '—',
            'platforms' => implode(', ', $broker->platformList()) ?: '—',
            'markets' => implode(', ', $broker->marketList()) ?: '—',
            'minimum_deposit' => $broker->minimum_deposit !== null
                ? '$' . number_format((float) $broker->minimum_deposit, 0)
                : '—',
            'minimum_deposit_raw' => $broker->minimum_deposit,
            'spreads' => $broker->spreads ?: '—',
            'leverage' => $broker->leverage ?: '—',
            'commission' => $broker->commission ?: 'None',
            'fee_level' => ucfirst((string) ($broker->fee_level ?: 'medium')),
            'country' => $broker->country ?: '—',
            'year_founded' => $broker->year_founded ?: '—',
            'broker_type' => $broker->isRegulated() ? 'Regulated' : 'Unregulated',
            'top_feature' => $broker->top_feature ?: '—',
            'deposit_methods' => strip_tags((string) ($broker->deposit_methods ?: '')) ?: '—',
            'withdrawal_method' => strip_tags((string) ($broker->withdrawal_method ?: '')) ?: '—',
            'withdrawal_fee' => $broker->withdrawal_fee ?: '—',
            'payment_methods' => strip_tags((string) ($broker->payment_methods ?: '')) ?: '—',
            'languages' => strip_tags((string) ($broker->languages ?: '')) ?: '—',
            'customer_support' => strip_tags((string) ($broker->customer_support ?: '')) ?: '—',
            'instrument_count' => $broker->instrument_count ? (int) $broker->instrument_count : null,
            'account_types' => $accountTypes ? implode(', ', $accountTypes) : '—',
            'trust_score' => $broker->trust_score ?: '—',
            'review_count' => (int) ($broker->approved_review_count ?? $broker->reviews()->where('status', 1)->count()),
            'investor_protection' => $this->boolLabel($broker->investor_protection),
            'segregation_of_funds' => $this->boolLabel($broker->segregation_of_funds),
            'negative_balance_protection' => $this->boolLabel($broker->negative_balance_protection),
            'mobile_trading' => $this->boolLabel((bool) $broker->mobile_trading),
            'web_trader' => $this->boolLabel((bool) $broker->web_trader),
            'vps_hosting' => $this->boolLabel($broker->vps_hosting),
            'social_trading' => $this->boolLabel($broker->social_trading),
            'account_managers' => $this->boolLabel($broker->account_managers),
            'rating_display' => $broker->rating !== null ? number_format((float) $broker->rating, 1) . '/5' : '—',
            'review_url' => route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]),
            'scam_checker_url' => route('broker.scam_checker.show', ['slug' => $broker->listingSlug()]),
            'visit_url' => $broker->open_live ?: $broker->visit_site ?: $broker->url,
        ];
    }

    protected function boolLabel(?bool $value): string
    {
        if ($value === null) {
            return '—';
        }

        return $value ? 'Yes' : 'No';
    }
}
