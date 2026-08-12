<?php

namespace App\Services;

use App\Models\ForexBonus;
use Illuminate\Support\Collection;

class PromotionsGuideService
{
    /** @var array<string, array{name: string, promo_type: string, slug: string, description: string}> */
    private const TYPE_META = [
        'deposit-bonuses' => [
            'name' => 'Deposit bonuses',
            'promo_type' => 'Forex Deposit Bonus',
            'description' => 'Matched or fixed bonus credit added when you fund a live account — often tied to minimum deposit and turnover rules.',
        ],
        'no-deposit-bonuses' => [
            'name' => 'No deposit bonuses',
            'promo_type' => 'Forex No Deposit Bonus',
            'description' => 'Free trading credit without an initial deposit — useful for testing execution, but usually comes with strict withdrawal conditions.',
        ],
        'live-contests' => [
            'name' => 'Live contests',
            'promo_type' => 'Forex Live Contest',
            'description' => 'Real-money or live-account competitions ranked by profit, ROI, or volume — prizes can be cash or account credits.',
        ],
        'cashback-rebates' => [
            'name' => 'Cashback & rebates',
            'promo_type' => 'Forex Cashback Rebate',
            'description' => 'Ongoing rebates on spreads or commissions — suited to active traders who care about long-term cost reduction.',
        ],
        'crypto-bonuses' => [
            'name' => 'Crypto promos',
            'promo_type' => 'Crypto Bonus Promotion',
            'description' => 'Promotions for crypto CFD or crypto wallet accounts — check asset coverage and leverage caps separately from forex offers.',
        ],
    ];

    public function __construct(
        private readonly PromotionsIndexService $promotionsIndexService,
    ) {}

    /**
     * @param  array<int, array<string, mixed>>  $tabs
     * @param  array<string, int>  $stats
     * @param  Collection<string, Collection<int, ForexBonus>>  $catalog
     * @return array<string, mixed>
     */
    public function build(array $tabs, array $stats, Collection $catalog): array
    {
        $typeRows = $this->typeRows($tabs, $catalog);
        $currentByType = $this->currentByType($tabs, $catalog);

        return [
            'toc' => $this->tableOfContents(),
            'stats' => $stats,
            'type_rows' => $typeRows,
            'current_by_type' => $currentByType,
            'total_types_live' => collect($typeRows)->where('count', '>', 0)->count(),
            'faqs' => $this->faqs($stats, $typeRows),
        ];
    }

    /**
     * Slim hub content for the promotions index (accordion + FAQ only).
     *
     * @param  array<string, int|bool>  $stats
     * @param  array<int, array<string, mixed>>  $tabs
     * @return array<string, mixed>
     */
    public function buildHub(array $stats, array $tabs): array
    {
        $typeRows = collect($tabs)
            ->reject(fn (array $tab) => ($tab['slug'] ?? '') === PromotionsIndexService::TAB_ALL)
            ->map(fn (array $tab) => [
                'slug' => $tab['slug'],
                'name' => $tab['name'],
                'count' => (int) ($tab['count'] ?? 0),
            ])
            ->values()
            ->all();

        return [
            'faqs' => $this->faqs($stats, $typeRows),
            'type_guides' => collect($typeRows)
                ->map(function (array $row) {
                    $slug = $row['slug'];
                    $meta = self::TYPE_META[$slug] ?? null;

                    return [
                        'slug' => $slug,
                        'name' => $row['name'],
                        'count' => (int) ($row['count'] ?? 0),
                        'description' => $meta['description'] ?? '',
                        'url' => route('promotions.tab', ['type' => $slug]),
                    ];
                })
                ->all(),
            'evaluate_steps' => [
                'Confirm the broker is regulated in your region before claiming any bonus.',
                'Read withdrawal rules, volume requirements, and profit caps in the official terms.',
                'Compare spreads, fees, and safety scores — a large bonus on a weak broker rarely pays off.',
            ],
        ];
    }

    /** @return array<int, array{id: string, label: string}> */
    private function tableOfContents(): array
    {
        return [
            ['id' => 'what-is-forex-promotion', 'label' => 'What is a Forex Promotion?'],
            ['id' => 'types-of-forex-promotions', 'label' => 'Types of Forex Promotions'],
            ['id' => 'promotion-types-at-a-glance', 'label' => 'Promotion Types at a Glance'],
            ['id' => 'how-to-evaluate', 'label' => 'How to Evaluate Any Forex Promotion?'],
            ['id' => 'common-mistakes', 'label' => 'Common Mistakes With Forex Promotions'],
            ['id' => 'regulation-and-promotions', 'label' => 'Regulation and Forex Promotions'],
            ['id' => 'current-promotions', 'label' => 'Current Promotions Available on BrokersCourt'],
            ['id' => 'faqs', 'label' => 'FAQs'],
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $tabs
     * @param  Collection<string, Collection<int, ForexBonus>>  $catalog
     * @return array<int, array<string, mixed>>
     */
    private function typeRows(array $tabs, Collection $catalog): array
    {
        $rows = [];

        foreach ($tabs as $tab) {
            $slug = $tab['slug'];
            $meta = self::TYPE_META[$slug] ?? null;

            if (! $meta) {
                continue;
            }

            $sample = $this->sampleFromCatalog($catalog, $meta['promo_type']);

            $rows[] = [
                'slug' => $slug,
                'name' => $meta['name'],
                'description' => $meta['description'],
                'count' => (int) ($tab['count'] ?? 0),
                'url' => $tab['url'],
                'tone' => $sample['type_tone'] ?? 'default',
                'sample_offer' => $sample['offer'] ?? null,
                'sample_broker' => $sample['broker_name'] ?? null,
            ];
        }

        return $rows;
    }

    /**
     * @param  array<int, array<string, mixed>>  $tabs
     * @param  Collection<string, Collection<int, ForexBonus>>  $catalog
     * @return array<int, array<string, mixed>>
     */
    private function currentByType(array $tabs, Collection $catalog): array
    {
        $groups = [];

        foreach ($tabs as $tab) {
            $slug = $tab['slug'];
            $meta = self::TYPE_META[$slug] ?? null;

            if (! $meta || (int) ($tab['count'] ?? 0) === 0) {
                continue;
            }

            $promos = ($catalog->get($meta['promo_type']) ?? collect())
                ->take(8)
                ->map(fn (ForexBonus $bonus) => $this->promotionsIndexService->serializeCard($bonus))
                ->values()
                ->all();

            if ($promos === []) {
                continue;
            }

            $groups[] = [
                'slug' => $slug,
                'name' => $meta['name'],
                'count' => (int) $tab['count'],
                'url' => $tab['url'],
                'promos' => $promos,
            ];
        }

        return $groups;
    }

    /**
     * @param  Collection<string, Collection<int, ForexBonus>>  $catalog
     * @return array<string, mixed>
     */
    private function sampleFromCatalog(Collection $catalog, string $promoType): array
    {
        $bonus = ($catalog->get($promoType) ?? collect())->first();

        return $bonus ? $this->promotionsIndexService->serializeCard($bonus) : [];
    }

    /**
     * @param  array<string, int>  $stats
     * @param  array<int, array<string, mixed>>  $typeRows
     * @return array<int, array{question: string, answer: string}>
     */
    private function faqs(array $stats, array $typeRows): array
    {
        $active = number_format($stats['total_active'] ?? 0);
        $typeList = collect($typeRows)
            ->where('count', '>', 0)
            ->pluck('name')
            ->implode(', ');

        return [
            [
                'question' => 'What counts as a forex promotion on BrokersCourt?',
                'answer' => 'We track deposit bonuses, no-deposit offers, live trading contests, cashback rebates, and crypto-related promotions published by brokers. Each listing is tied to a broker profile and refreshed from our live promotions database.',
            ],
            [
                'question' => 'How many active promotions are listed right now?',
                'answer' => "There are currently {$active} active offers across ".count($typeRows).' categories on BrokersCourt. Counts change as brokers launch, extend, or expire campaigns.',
            ],
            [
                'question' => 'Which promotion types are available today?',
                'answer' => $typeList !== ''
                    ? "Active categories right now include: {$typeList}. Use the category filters above to narrow the list or open any offer for full terms."
                    : 'Categories update as new broker campaigns go live. Check back soon or browse broker reviews for regulated alternatives.',
            ],
            [
                'question' => 'Are forex bonuses free money?',
                'answer' => 'Rarely. Most bonuses are trading credit subject to volume requirements, profit caps, or withdrawal restrictions. Always read the broker\'s official terms before opting in.',
            ],
            [
                'question' => 'Can I claim promotions from unregulated brokers?',
                'answer' => 'We strongly recommend regulated brokers only. Promotions from unlicensed firms may look generous but carry higher fraud and withdrawal risk. Cross-check regulation on the broker review page first.',
            ],
            [
                'question' => 'How often is this page updated?',
                'answer' => 'Offer cards pull from our live promotions database whenever you load or filter the page. Featured status, expiry dates, and new campaigns are reflected as brokers update their programs.',
            ],
        ];
    }
}
