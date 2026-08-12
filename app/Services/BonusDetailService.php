<?php

namespace App\Services;

use App\Http\Controllers\Front\BrokerController;
use App\Models\ForexBonus;
use Illuminate\Support\Str;

class BonusDetailService
{
    /** @var array<string, string> */
    private const PROMO_TABS = [
        'Forex Deposit Bonus' => 'deposit-bonuses',
        'Forex No Deposit Bonus' => 'no-deposit-bonuses',
        'Forex Live Contest' => 'live-contests',
        'Forex Demo Contest' => 'demo-contests',
        'Forex Cashback Rebate' => 'cashback-rebates',
        'Crypto Bonus Promotion' => 'crypto-bonuses',
    ];

    /** @var array<string, string> */
    private const PROMO_INDEX_ROUTES = [
        'Forex Deposit Bonus' => 'promotions.tab',
        'Forex No Deposit Bonus' => 'promotions.tab',
        'Forex Live Contest' => 'promotions.tab',
        'Forex Demo Contest' => 'promotions.tab',
        'Forex Cashback Rebate' => 'promotions.tab',
        'Crypto Bonus Promotion' => 'promotions.tab',
    ];

    public function __construct(
        private readonly PromotionsIndexService $promotionsIndexService,
    ) {}

    /** @return array<string, mixed> */
    public function build(ForexBonus $bonus, array $editorialCredits = []): array
    {
        $broker = $bonus->broker;
        $tabSlug = self::PROMO_TABS[$bonus->promo_type] ?? 'deposit-bonuses';
        $tabName = $this->tabName($tabSlug);

        return [
            'hero' => $this->hero($bonus),
            'highlights' => $this->highlights($bonus),
            'sections' => $this->sections($bonus),
            'broker' => $this->brokerSnapshot($bonus, $broker),
            'claim_url' => $this->claimUrl($bonus),
            'terms_url' => filled($bonus->terms_conditions_url) ? $bonus->terms_conditions_url : null,
            'editorial' => $editorialCredits,
            'editorial_team' => $this->editorialTeam($bonus),
            'guide_meta' => [
                'updated_at' => ($bonus->updated_at ?? $bonus->publish_date)?->format('M j, Y')
                    ?? now()->format('M j, Y'),
            ],
            'quick_links' => $this->quickLinks($bonus, $broker, $tabSlug, $tabName),
            'related_broker' => $this->relatedBrokerPromos($bonus),
            'related_category' => $this->relatedCategoryPromos($bonus, $tabSlug),
            'breadcrumb' => $this->breadcrumb($bonus, $tabSlug, $tabName),
            'disclaimer' => $this->disclaimer($bonus),
            'category_label' => $tabName,
        ];
    }

    /** @return array<string, mixed> */
    private function hero(ForexBonus $bonus): array
    {
        return [
            'title' => $bonus->title,
            'eyebrow' => $bonus->promoTypeShort(),
            'tone' => $bonus->promoTypeTone(),
            'headline' => strip_tags((string) $bonus->prize) ?: $bonus->headlineOffer(),
            'subtitle' => Str::limit(strip_tags((string) $bonus->description), 180),
            'is_active' => $bonus->isActivePromotion(),
            'is_featured' => (bool) $bonus->is_featured,
            'status_label' => $this->statusLabel($bonus),
            'image' => $bonus->feature_image ? asset($bonus->feature_image) : null,
            'stats' => array_values(array_filter([
                $bonus->min_deposit !== null ? [
                    'label' => 'Min. deposit',
                    'value' => (float) $bonus->min_deposit <= 0
                        ? 'None'
                        : '$'.number_format((float) $bonus->min_deposit, 0),
                ] : null,
                $bonus->bonus_percentage ? [
                    'label' => 'Bonus match',
                    'value' => rtrim(rtrim(number_format((float) $bonus->bonus_percentage, 2), '0'), '.').'%',
                    'highlight' => true,
                ] : null,
                $bonus->bonus_amount ? [
                    'label' => 'Max bonus',
                    'value' => '$'.number_format((float) $bonus->bonus_amount, 0),
                ] : null,
                $bonus->expiry_date ? [
                    'label' => 'Expires',
                    'value' => $bonus->expiry_date->format('M j, Y'),
                    'urgent' => $bonus->isExpiryUrgent(),
                ] : null,
                [
                    'label' => 'Published',
                    'value' => $bonus->publish_date?->format('M j, Y') ?? '—',
                ],
            ])),
        ];
    }

    private function statusLabel(ForexBonus $bonus): string
    {
        if (! $bonus->isActivePromotion()) {
            return 'Expired';
        }

        if ($bonus->promotion_status === 'limited-time') {
            return 'Limited time';
        }

        $badge = $bonus->expiryBadge();
        if ($badge && in_array($badge['tone'], ['urgent', 'soon'], true)) {
            return $badge['short'];
        }

        return 'Active offer';
    }

    /** @return array<int, string> */
    private function highlights(ForexBonus $bonus): array
    {
        $items = [];

        if (filled($bonus->details)) {
            preg_match_all('/<li[^>]*>(.*?)<\/li>/is', (string) $bonus->details, $matches);

            foreach ($matches[1] ?? [] as $item) {
                $text = trim(strip_tags($item));
                if ($text !== '') {
                    $items[] = $text;
                }
            }
        }

        if ($items === []) {
            $items = [
                'Verified broker promotion listed on BrokersCourt',
                'Terms and eligibility apply — read before claiming',
            ];
        }

        return $items;
    }

    /** @return array<int, array{key: string, title: string, content: string, html: bool}> */
    private function sections(ForexBonus $bonus): array
    {
        $sections = [
            [
                'key' => 'how_to',
                'title' => 'How to claim',
                'content' => (string) $bonus->how_to_participate,
                'html' => true,
            ],
            [
                'key' => 'overview',
                'title' => 'Offer overview',
                'content' => (string) $bonus->bonus_type_details,
                'html' => false,
            ],
            [
                'key' => 'eligibility',
                'title' => 'Eligibility',
                'content' => (string) $bonus->eligibility_criteria,
                'html' => false,
            ],
            [
                'key' => 'terms',
                'title' => 'General terms',
                'content' => (string) $bonus->general_terms,
                'html' => false,
            ],
            [
                'key' => 'restrictions',
                'title' => 'Country restrictions',
                'content' => (string) $bonus->participate,
                'html' => false,
            ],
            [
                'key' => 'description',
                'title' => 'Full description',
                'content' => (string) $bonus->description,
                'html' => true,
            ],
        ];

        return array_values(array_filter($sections, function (array $section) {
            return trim(strip_tags($section['content'])) !== '';
        }));
    }

    /** @return array<string, mixed>|null */
    private function brokerSnapshot(ForexBonus $bonus, $broker): ?array
    {
        if (! $broker) {
            return null;
        }

        return [
            'name' => $broker->name,
            'logo' => $broker->logo ? asset($broker->logo) : null,
            'rating' => $broker->rating !== null ? round((float) $broker->rating, 1) : null,
            'country' => $broker->country,
            'min_deposit' => $broker->minimum_deposit !== null
                ? '$'.number_format((float) $broker->minimum_deposit, 0)
                : null,
            'regulation' => array_slice($broker->regulationList(), 0, 4),
            'platforms' => array_slice($broker->platformList(), 0, 3),
            'top_feature' => $broker->top_feature,
            'review_url' => route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]),
            'website' => $bonus->affiliate_link ?: $bonus->link ?: $broker->url,
        ];
    }

    private function claimUrl(ForexBonus $bonus): ?string
    {
        return $bonus->affiliate_link
            ?: $bonus->link
            ?: $bonus->broker?->visit_site
            ?: $bonus->broker?->url;
    }

    /** @return array<int, array<string, string>> */
    private function quickLinks(ForexBonus $bonus, $broker, string $tabSlug, string $tabName): array
    {
        $links = [];

        if ($broker) {
            $links[] = [
                'label' => $broker->name.' review',
                'desc' => 'Fees, regulation & platform breakdown',
                'url' => route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]),
                'icon' => 'fa-solid fa-star',
            ];
        }

        $links[] = [
            'label' => 'All '.$tabName,
            'desc' => 'Browse more offers in this category',
            'url' => route('promotions.tab', ['type' => $tabSlug]),
            'icon' => 'fa-solid fa-gift',
        ];

        $links[] = [
            'label' => 'Broker promos hub',
            'desc' => 'Deposit, no-deposit, contests & cashback',
            'url' => route('promotions.index'),
            'icon' => 'fa-solid fa-tags',
        ];

        if ($broker) {
            $otherPromo = ForexBonus::query()
                ->where('broker_id', $broker->id)
                ->where('id', '!=', $bonus->id)
                ->where(function ($query) {
                    $query->whereNull('expiry_date')
                        ->orWhereDate('expiry_date', '>=', now());
                })
                ->orderByDesc('publish_date')
                ->first();

            if ($otherPromo && $otherPromo->detailUrl()) {
                $links[] = [
                    'label' => 'More from '.$broker->name,
                    'desc' => Str::limit($otherPromo->headlineOffer(), 42),
                    'url' => $otherPromo->detailUrl(),
                    'icon' => 'fa-solid fa-arrow-right',
                ];
            }
        }

        $links[] = [
            'label' => 'Compare brokers',
            'desc' => 'Side-by-side fees, platforms & safety',
            'url' => route('broker.comparison'),
            'icon' => 'fa-solid fa-scale-balanced',
        ];

        $links[] = [
            'label' => 'Regulated brokers',
            'desc' => 'Licensed brokers by jurisdiction',
            'url' => route('regulated_brokers'),
            'icon' => 'fa-solid fa-certificate',
        ];

        if ($broker) {
            $links[] = [
                'label' => 'Scam checker',
                'desc' => 'Verify '.$broker->name.' before you deposit',
                'url' => route('broker.scam_checker'),
                'icon' => 'fa-solid fa-shield-halved',
            ];
        }

        return $links;
    }

    /** @return array<int, array<string, mixed>> */
    private function relatedBrokerPromos(ForexBonus $bonus): array
    {
        if (! $bonus->broker_id) {
            return [];
        }

        return ForexBonus::query()
            ->with('broker')
            ->where('broker_id', $bonus->broker_id)
            ->where('id', '!=', $bonus->id)
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhereDate('expiry_date', '>=', now());
            })
            ->orderByDesc('publish_date')
            ->limit(4)
            ->get()
            ->map(fn (ForexBonus $item) => $this->promotionsIndexService->serializeCard($item))
            ->all();
    }

    /** @return array<int, array<string, mixed>> */
    private function relatedCategoryPromos(ForexBonus $bonus, string $tabSlug): array
    {
        $promoType = array_search($tabSlug, self::PROMO_TABS, true) ?: $bonus->promo_type;

        return ForexBonus::query()
            ->with('broker')
            ->where('promo_type', $promoType)
            ->where('id', '!=', $bonus->id)
            ->when($bonus->broker_id, fn ($q) => $q->where('broker_id', '!=', $bonus->broker_id))
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhereDate('expiry_date', '>=', now());
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('publish_date')
            ->limit(4)
            ->get()
            ->map(fn (ForexBonus $item) => $this->promotionsIndexService->serializeCard($item))
            ->all();
    }

    /** @return array<int, array{label: string, url: string|null}> */
    private function breadcrumb(ForexBonus $bonus, string $tabSlug, string $tabName): array
    {
        return [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Broker promos', 'url' => route('promotions.index')],
            ['label' => $tabName, 'url' => route('promotions.tab', ['type' => $tabSlug])],
            ['label' => $bonus->brokerDisplayName() ?? Str::limit($bonus->title, 40), 'url' => null],
        ];
    }

    private function tabName(string $tabSlug): string
    {
        return match ($tabSlug) {
            'deposit-bonuses' => 'Deposit bonuses',
            'no-deposit-bonuses' => 'No deposit bonuses',
            'live-contests' => 'Live contests',
            'demo-contests' => 'Demo contests',
            'cashback-rebates' => 'Cashback',
            'crypto-bonuses' => 'Crypto promos',
            default => 'Promotions',
        };
    }

    private function disclaimer(ForexBonus $bonus): string
    {
        return 'Promotions change frequently and may vary by country, account type, and client status. '
            .'Always read the broker\'s official terms before depositing. Trading leveraged products involves substantial risk of loss.';
    }

    /** @return array<int, array<string, mixed>> */
    private function editorialTeam(ForexBonus $bonus): array
    {
        $team = EditorialAssignmentService::teamFor($bonus);

        return $team !== [] ? $team : EditorialAssignmentService::defaultGuideTeam();
    }
}
