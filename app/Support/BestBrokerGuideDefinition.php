<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Editorial copy for a best-broker guide.
 *
 * Comparison columns no longer live here: they are chosen at render time from whichever
 * metrics the ranked brokers actually have data for (see {@see BestBrokerGuideMetrics}).
 * What remains is the writing — headings, explainers, and FAQs — with per-category
 * overrides where a generic sentence would be useless.
 */
class BestBrokerGuideDefinition
{
    /**
     * Category-specific explainers. Anything not listed falls back to {@see genericExplainer()}.
     *
     * @var array<string, array{lead: string, title: string, body: array<int, string>, checklist: array<int, string>}>
     */
    private const EXPLAINERS = [
        'micro-accounts-brokers' => [
            'lead' => 'Trade in fractions of a standard lot, so you can learn with real money without risking much of it.',
            'title' => 'What is a micro account, and who is it for?',
            'body' => [
                'A micro lot is 1,000 units of the base currency — a tenth of a mini lot and a hundredth of a standard lot. On EUR/USD that puts roughly $0.10 on each pip instead of $10, so a 50-pip move costs you $5 rather than $500.',
                'Cent accounts go further: your balance is denominated in cents, so a $20 deposit shows as 2,000 cents and the platform lets you trade sizes that would otherwise round down to zero. Both are aimed at the same trader — someone testing a strategy live, or sizing down while they build confidence.',
                'The trade-off is that micro and cent accounts often sit on the broker\'s standard pricing tier, which means wider spreads than a raw or ECN account. That is why we weight the spread alongside the minimum deposit rather than ranking on entry cost alone.',
            ],
            'checklist' => [
                'Confirm the broker offers a named micro, cent, or nano account rather than just a low minimum deposit',
                'Check the minimum trade size — 0.01 lots is standard, 0.001 is better for very small balances',
                'Compare the spread on the micro tier specifically, not the raw account headline rate',
                'Look for negative balance protection so a gap cannot take the account below zero',
                'Verify the broker holds a licence in a jurisdiction that will actually take your complaint',
            ],
        ],
        'brokers-for-beginners' => [
            'lead' => 'A good first broker keeps the entry cost low and holds a licence somewhere that answers the phone.',
            'title' => 'What matters most when you are starting out',
            'body' => [
                'The costs that hurt beginners are rarely the headline spread — they are the ones you did not budget for: inactivity fees, withdrawal charges, and swap on positions held overnight.',
                'A demo account matters more than most rankings suggest. Being able to place the same trade twice, once on paper and once live, is the fastest way to learn what slippage and spread actually cost you.',
            ],
            'checklist' => [
                'Start on a demo account and only fund once you can place orders without hesitating',
                'Check the inactivity fee — many brokers charge after 90 days of no trading',
                'Prefer a Tier 1 regulator (FCA, ASIC, BaFin) for your first account',
                'Make sure the broker supports micro lots so you can size positions sensibly',
            ],
        ],
        'low-spread-brokers' => [
            'lead' => 'Spread is charged on every trade you place, so it compounds faster than any other fee.',
            'title' => 'Reading a spread quote honestly',
            'body' => [
                'A "from 0.0 pips" headline almost always refers to a raw or ECN account that charges a separate per-lot commission. The number that matters is the all-in cost: spread plus commission, round turn.',
                'Advertised spreads are also typically the best case during London/New York overlap. Costs widen around news releases and in thin overnight sessions, which is when many retail stops get hit.',
            ],
            'checklist' => [
                'Add the round-turn commission to the raw spread before comparing brokers',
                'Ask whether the quoted spread is an average or a minimum',
                'Check spreads on the pairs you actually trade, not just EUR/USD',
            ],
        ],
        'high-leverage' => [
            'lead' => 'High leverage lets a small balance control a large position — and loses it just as quickly.',
            'title' => 'What high leverage actually changes',
            'body' => [
                'Leverage does not change your risk on its own; position size does. At 1:1000 you can open a position a hundred times larger than at 1:10, but the loss per pip scales identically.',
                'Regulators cap retail leverage for a reason: FCA and ASIC limit major FX pairs to 1:30. Brokers offering 1:1000 or more do so through offshore entities, which usually means weaker recourse if something goes wrong.',
            ],
            'checklist' => [
                'Confirm which entity you would be onboarded to, and its leverage cap',
                'Check the margin call and stop-out levels, not just the maximum leverage',
                'Insist on negative balance protection at these leverage levels',
            ],
        ],
        'scalping-brokers' => [
            'lead' => 'Scalping lives or dies on execution cost and fill quality.',
            'title' => 'What a scalping-friendly broker looks like',
            'body' => [
                'Raw spreads plus commission almost always beats a "commission-free" standard account once you are trading dozens of times a day.',
                'Read the terms before funding: some brokers restrict trades closed inside 60 seconds, or reserve the right to reject fills on latency-arbitrage grounds.',
            ],
            'checklist' => [
                'Confirm scalping and EAs are permitted in the client agreement',
                'Compare round-turn cost, not spread alone',
                'Check for free or discounted VPS hosting near the broker\'s servers',
            ],
        ],
    ];

    /** @return array<string, mixed>|null */
    public static function forSlug(string $slug): ?array
    {
        $type = BrokerListingFilter::slugType($slug);

        if ($type === null) {
            return null;
        }

        $label = BrokerListingFilter::labelFor($slug);

        return self::makeGuide($type, $slug, $label);
    }

    /**
     * @return array<string, mixed>
     */
    public static function forCountry(string $slug, string $label): array
    {
        return self::makeGuide('country', $slug, $label);
    }

    /**
     * @return array<string, mixed>
     */
    private static function makeGuide(string $type, string $slug, string $label): array
    {
        $explainer = self::EXPLAINERS[$slug] ?? self::genericExplainer($type, $label);
        $customDescription = \App\Models\BrokerTaxonomyTerm::descriptionFor($type, $slug);
        $customPlain = \App\Support\RichText::toPlainText($customDescription);

        return [
            'type' => $type,
            'title' => self::title($type, $label),
            'meta_title' => self::metaTitle($type, $label),
            'meta_description' => $customPlain
                ? Str::limit($customPlain, 155, '')
                : self::metaDescription($type, $label),
            'breadcrumb' => self::breadcrumb($type, $label),
            'score_label' => self::scoreLabel($type, $label),
            'topic' => self::topicLabel($type, $label),
            'lead' => $explainer['lead'],
            'description' => $customDescription,
            'explainer' => [
                'title' => $explainer['title'],
                'body' => $explainer['body'],
                'checklist' => $explainer['checklist'],
            ],
            'spotlight_title' => self::spotlightTitle($type, $label),
            'cta_title' => self::ctaTitle($type, $label),
            'compare_title' => self::compareTitle($type, $label),
            'scoring_title' => self::scoringTitle($type, $label),
            'reviews_title' => self::reviewsTitle($type, $label),
            'methodology' => [
                'title' => self::methodologyTitle($type, $label),
                'intro' => 'Every broker on this page is scored against the same pillars, weighted for what this list is about. Pillars we have no verified data for are excluded rather than assumed, so a broker is never penalised for a gap in our records.',
                'points' => [
                    'Compared advertised spreads and fee class on major FX pairs',
                    'Checked minimum deposit and the account tiers actually on offer',
                    'Verified licences against each regulator\'s public register',
                    'Reviewed platform availability, including MetaTrader and cTrader',
                    'Assessed leverage, swap-free options, and demo access',
                    'Re-ran every metric against live broker data on publication',
                ],
            ],
            'faqs' => self::faqs($type, $label, $slug),
        ];
    }

    /**
     * @return array{lead: string, title: string, body: array<int, string>, checklist: array<int, string>}
     */
    private static function genericExplainer(string $type, string $label): array
    {
        $focus = match ($type) {
            'country', 'region' => "traders in {$label}",
            default => Str::lower($label),
        };

        return [
            'lead' => "Every broker scored for {$focus} on cost, entry requirements, platforms, and regulation.",
            'title' => "How to choose a broker for {$focus}",
            'body' => [
                'Trading costs compound: a spread that looks marginally wider adds up quickly once you are trading regularly, and non-trading fees like withdrawal and inactivity charges rarely appear in headline comparisons.',
                'Regulation determines what happens when something goes wrong. A Tier 1 licence means segregated client money and access to a statutory complaints process; an offshore registration usually means neither.',
            ],
            'checklist' => [
                'Compare all-in cost — spread plus commission — on the instruments you trade',
                'Confirm which legal entity would hold your account and who regulates it',
                'Check withdrawal fees and processing times before you fund',
                'Test the platform on a demo account first',
            ],
        ];
    }

    private static function methodologyTitle(string $type, string $label): string
    {
        return match ($type) {
            'country' => "How we picked the best brokers in {$label}",
            'region' => "How we picked the best brokers for {$label}",
            default => self::hasBrokersSuffix($label)
                ? "How we picked the best {$label}"
                : "How we picked the best {$label} brokers",
        };
    }

    private static function title(string $type, string $label): string
    {
        return match ($type) {
            'country' => "Best Forex Brokers in {$label} {year}",
            'region' => "Best {$label} {year}",
            default => self::hasBrokersSuffix($label)
                ? "Best {$label} in {year}"
                : "Best {$label} Brokers in {year}",
        };
    }

    private static function hasBrokersSuffix(string $label): bool
    {
        return Str::endsWith(Str::lower($label), 'brokers');
    }

    private static function metaTitle(string $type, string $label): string
    {
        return match ($type) {
            'country' => "Best Forex Brokers in {$label} {year} – Fees & Regulation Compared",
            default => self::hasBrokersSuffix($label)
                ? "Best {$label} {year} – Fees, Platforms & Trust Compared"
                : "Best {$label} Brokers {year} – Fees, Platforms & Trust Compared",
        };
    }

    private static function metaDescription(string $type, string $label): string
    {
        $focus = match ($type) {
            'country', 'region' => "traders in {$label}",
            default => Str::lower($label),
        };

        return "Compare the best forex brokers for {$focus} in {year}. Ranked on verified spreads, minimum deposits, leverage, platforms, and regulation from our broker database.";
    }

    private static function breadcrumb(string $type, string $label): string
    {
        return $type === 'country' ? "Best brokers in {$label}" : $label;
    }

    private static function topicLabel(string $type, string $label): string
    {
        return match ($type) {
            'country', 'region' => $label,
            default => Str::lower($label),
        };
    }

    private static function spotlightTitle(string $type, string $label): string
    {
        return match ($type) {
            'country', 'region' => "Our top pick for {$label}",
            default => self::hasBrokersSuffix($label)
                ? "Our top {$label} pick"
                : "Our top {$label} broker pick",
        };
    }

    private static function compareTitle(string $type, string $label): string
    {
        return match ($type) {
            'country', 'region' => "Compare the top brokers in {$label}",
            default => 'Compare the shortlist side by side',
        };
    }

    private static function scoringTitle(string $type, string $label): string
    {
        return match ($type) {
            'country', 'region' => "What we weighted for {$label}",
            default => self::hasBrokersSuffix($label)
                ? "What we weighted for {$label}"
                : "What we weighted for {$label} brokers",
        };
    }

    private static function reviewsTitle(string $type, string $label): string
    {
        return match ($type) {
            'country', 'region' => "Every broker on the {$label} shortlist",
            default => 'Every broker on the shortlist',
        };
    }

    private static function ctaTitle(string $type, string $label): string
    {
        return match ($type) {
            'country' => "Need help finding the right broker in {$label}?",
            'region' => "Need help finding the right broker for {$label}?",
            default => self::hasBrokersSuffix($label)
                ? "Need help finding the right {$label} for you?"
                : "Need help finding the right {$label} broker for you?",
        };
    }

    private static function scoreLabel(string $type, string $label): string
    {
        return match ($type) {
            'country', 'region' => 'Overall fit',
            default => 'Fit score',
        };
    }

    /** @return array<int, array{question: string, answer: string}> */
    private static function faqs(string $type, string $label, string $slug): array
    {
        $faqs = [
            [
                'question' => match ($type) {
                    'country' => "How did BrokersCourt rank the best brokers in {$label}?",
                    default => self::hasBrokersSuffix($label)
                        ? "How did BrokersCourt rank the best {$label}?"
                        : "How did BrokersCourt rank the best {$label} brokers?",
                },
                'answer' => 'Each broker is scored on weighted pillars chosen for this specific list, using verified data from our broker database rather than a single overall rating. Pillars we cannot verify for a broker are excluded from its score and the remaining weights are rebalanced, so a missing field lowers our confidence instead of silently counting as a zero.',
            ],
            [
                'question' => 'Why do some brokers show fewer details than others?',
                'answer' => 'We only publish a figure when we have verified it. Where a broker has not disclosed a commission, withdrawal fee, or founding year, the page says so rather than showing a placeholder that could be read as a real value.',
            ],
            [
                'question' => 'Are all the brokers on this page regulated?',
                'answer' => 'No, and we label it clearly. Each broker shows the regulators it holds licences with and its regulatory tier. Brokers with no licence on record carry a warning flag, and you should treat the absence of oversight as a material risk.',
            ],
        ];

        if ($slug === 'micro-accounts-brokers') {
            $faqs[] = [
                'question' => 'What is the difference between a micro account and a cent account?',
                'answer' => 'A micro account trades in micro lots of 1,000 units, so a pip is worth roughly $0.10 on most major pairs. A cent account denominates your balance in cents instead of dollars — a $50 deposit shows as 5,000 cents — which lets you place even smaller positions. Both reduce the capital at risk per trade; cent accounts simply allow finer granularity.',
            ];
            $faqs[] = [
                'question' => 'Is a micro account worth it compared to a demo account?',
                'answer' => 'They serve different purposes. A demo account teaches you the platform mechanics with no risk and no emotional pressure. A micro account puts real money on the line at a size where a bad week costs you very little, which is the only way to learn how you personally react to a losing position.',
            ];
        }

        $faqs[] = [
            'question' => $type === 'country'
                ? "Can traders in {$label} open accounts with these brokers?"
                : 'How do I choose between the brokers on this list?',
            'answer' => $type === 'country'
                ? "These brokers accept or actively serve clients in {$label}, but the entity you are onboarded to — and therefore your leverage cap, product access, and protections — varies. Confirm the local entity, payment methods, and regulation on the broker's own site before signing up."
                : 'Start with the pillar that matters most to your strategy and read across the comparison table, then open the full review for the two or three brokers that fit. The differences that matter most are usually the ones the headline score smooths over.',
        ];

        return $faqs;
    }
}
