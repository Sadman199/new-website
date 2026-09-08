<?php

namespace App\Services;

use App\Http\Controllers\Front\BrokerController;
use App\Models\Broker;
use App\Models\BrokerAlternativePage;
use App\Support\BrokerFacts;
use App\Support\BrokerRating;
use App\Support\JsonList;
use App\Support\RichText;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class BrokerAlternativesService
{
    public const MAX_AUTO = 6;

    public const MIN_PREFERRED = 4;

    public function publishedPages(): Collection
    {
        if (! Schema::hasTable('broker_alternative_pages')) {
            return collect();
        }

        return BrokerAlternativePage::query()
            ->published()
            ->with(['broker' => function ($query) {
                $query->where('is_scam', false);
            }])
            ->latest('updated_at')
            ->get()
            ->filter(fn (BrokerAlternativePage $page) => $page->broker !== null)
            ->values();
    }

    public function findPublishedPage(string $slug): ?BrokerAlternativePage
    {
        $brokerSlug = $this->canonicalBrokerSlug($slug);

        if ($brokerSlug === null) {
            return null;
        }

        return BrokerAlternativePage::query()
            ->published()
            ->whereHas('broker', fn ($query) => $query->where('slug', $brokerSlug)->where('is_scam', false))
            ->with(['broker.forexBonuses', 'items.alternativeBroker'])
            ->first();
    }

    public function canonicalBrokerSlug(string $slug): ?string
    {
        $slug = trim($slug);

        if ($slug === '') {
            return null;
        }

        $resolved = BrokerController::brokerSlugFromPublicSlug($slug);

        return $resolved ?: null;
    }

    /**
     * @return array{
     *     page: BrokerAlternativePage,
     *     broker: Broker,
     *     alternatives: Collection<int, Broker>,
     *     source: string,
     *     rows: array<int, array{key: string, label: string, cells: array<int, string>}>,
     *     faqs: array<int, array{question: string, answer: string}>,
     *     related: Collection<int, BrokerAlternativePage>,
     *     seo_title: string,
     *     meta_description: string,
     *     intro: string,
     *     why_consider: string,
     * }
     */
    public function buildShowPayload(BrokerAlternativePage $page): array
    {
        $broker = $page->broker;
        $resolved = $this->resolveAlternatives($page);
        $alternatives = $resolved['brokers'];
        $compared = collect([$broker])->concat($alternatives)->values();
        $compared->each(function (Broker $item) {
            $item->loadMissing('forexBonuses');
            $item->loadCount(['reviews as approved_review_count' => fn ($q) => $q->where('status', 1)]);
        });
        $serialized = $compared->mapWithKeys(
            fn (Broker $item) => [$item->id => app(BrokerComparisonService::class)->serializeBroker($item)]
        );

        return [
            'page' => $page,
            'broker' => $broker,
            'alternatives' => $alternatives,
            'source' => $resolved['source'],
            'rows' => $this->comparisonRows($compared, $serialized),
            'faqs' => $page->normalizedFaqs(),
            'related' => $this->relatedPages($page),
            'seo_title' => $this->seoTitle($page, $broker),
            'meta_description' => $this->metaDescription($page, $broker),
            'intro' => $this->introCopy($page, $broker),
            'why_consider' => $this->whyConsiderCopy($page, $broker),
            'review_url' => route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]),
            'compare_url' => route('broker.comparison', ['brokers' => $broker->slug]),
        ];
    }

    /**
     * @return array{brokers: Collection<int, Broker>, source: string}
     */
    public function resolveAlternatives(BrokerAlternativePage $page): array
    {
        $curated = $page->items
            ->map(fn ($item) => $item->alternativeBroker)
            ->filter(fn ($broker) => $broker instanceof Broker && ! $broker->is_scam)
            ->unique('id')
            ->values();

        if ($curated->isNotEmpty()) {
            return ['brokers' => $curated, 'source' => 'curated'];
        }

        $automatic = $this->autoRecommend($page->broker);

        if ($automatic->isNotEmpty()) {
            return ['brokers' => $automatic, 'source' => 'automatic'];
        }

        return ['brokers' => collect(), 'source' => 'empty'];
    }

    public function autoRecommend(Broker $source, int $limit = self::MAX_AUTO): Collection
    {
        $pool = Broker::query()
            ->where('is_scam', false)
            ->where('id', '!=', $source->id)
            ->whereNotNull('name')
            ->where('name', '!=', '')
            ->orderByDesc('rating')
            ->limit(48)
            ->get();

        $sourceFacts = BrokerFacts::for($source);

        return $pool
            ->map(function (Broker $candidate) use ($sourceFacts) {
                $score = $this->similarityScore($sourceFacts, $candidate);

                if ($score === null) {
                    return null;
                }

                return ['broker' => $candidate, 'score' => $score];
            })
            ->filter()
            ->sortByDesc('score')
            ->take($limit)
            ->pluck('broker')
            ->values();
    }

    public function seoTitle(BrokerAlternativePage $page, Broker $broker): string
    {
        $custom = $this->plainText($page->seo_title);

        if ($custom !== '') {
            return $custom;
        }

        return 'Best Alternatives to '.$broker->name.' '.date('Y').' | BrokersCourt';
    }

    public function metaDescription(BrokerAlternativePage $page, Broker $broker): string
    {
        $custom = $this->plainText($page->meta_description);

        if ($custom !== '') {
            return $custom;
        }

        return 'Compare live BrokersCourt data for '.$broker->name.' alternatives — regulation, minimum deposit, spreads, platforms, and account terms — then read each independent review.';
    }

    public function introCopy(BrokerAlternativePage $page, Broker $broker): string
    {
        $custom = $this->plainText($page->intro);

        if ($custom !== '') {
            return $custom;
        }

        $rating = BrokerRating::outOfFive($broker->rating);
        $regulation = implode(', ', array_slice($broker->regulationList(), 0, 3));

        $bits = ['Looking for brokers similar to '.$broker->name.'?'];

        if ($regulation !== '') {
            $bits[] = $broker->name.' is listed with '.$regulation.' in our database.';
        }

        if ($rating !== null) {
            $bits[] = 'Its current BrokersCourt rating is '.number_format($rating, 1).' out of 5.';
        }

        $bits[] = 'The alternatives below are drawn from live catalog data so you can compare costs, platforms, and account terms before opening an account.';

        return implode(' ', $bits);
    }

    public function whyConsiderCopy(BrokerAlternativePage $page, Broker $broker): string
    {
        $custom = $this->plainText($page->why_consider);

        if ($custom !== '') {
            return $custom;
        }

        $facts = BrokerFacts::for($broker);
        $reasons = [];

        if (! $broker->isRegulated()) {
            $reasons[] = 'Traders often look for a regulated option with clearer client-fund protections.';
        }

        $deposit = $facts['min_deposit']['value'] ?? null;
        if (is_numeric($deposit) && (float) $deposit >= 200) {
            $reasons[] = 'A lower minimum deposit can make it easier to start with a smaller account.';
        }

        $spread = $facts['spread']['value'] ?? null;
        if (is_numeric($spread) && (float) $spread >= 1) {
            $reasons[] = 'Tighter advertised spreads are a common reason to compare similar platforms.';
        }

        if ($reasons === []) {
            $reasons[] = 'Even a well-reviewed broker is worth stacking against peers for regulation, costs, platforms, and account types that match how you trade.';
        }

        return $broker->name.' may still be a fit, but it is useful to compare live BrokersCourt data before you commit. '.implode(' ', $reasons);
    }

    public static function flush(): void
    {
        SitemapService::flush();
    }

    /** @return Collection<int, BrokerAlternativePage> */
    protected function relatedPages(BrokerAlternativePage $page): Collection
    {
        return BrokerAlternativePage::query()
            ->published()
            ->where('id', '!=', $page->id)
            ->with('broker')
            ->latest('updated_at')
            ->limit(6)
            ->get()
            ->filter(fn (BrokerAlternativePage $related) => $related->broker && ! $related->broker->is_scam)
            ->values();
    }

    /**
     * @param  Collection<int, Broker>  $brokers
     * @param  Collection<int, array<string, mixed>>  $serialized
     * @return array<int, array{key: string, label: string, cells: array<int, string>}>
     */
    protected function comparisonRows(Collection $brokers, Collection $serialized): array
    {
        $fields = [
            'regulation' => 'Regulation',
            'minimum_deposit' => 'Minimum deposit',
            'spreads' => 'Spreads',
            'commission' => 'Commission',
            'leverage' => 'Leverage',
            'platforms' => 'Trading platforms',
            'account_types' => 'Account types',
            'payment_methods' => 'Payment methods',
            'bonus' => 'Bonus / promotions',
        ];

        $rows = [];

        foreach ($fields as $key => $label) {
            $cells = [];

            foreach ($brokers as $broker) {
                $cells[] = $key === 'bonus'
                    ? $this->bonusLabel($broker)
                    : $this->displayCell($serialized[$broker->id][$key] ?? '—');
            }

            if ($key === 'bonus' && collect($cells)->every(fn ($cell) => $cell === '—')) {
                continue;
            }

            $rows[] = compact('key', 'label', 'cells');
        }

        return $rows;
    }

    protected function displayCell(mixed $value): string
    {
        if (is_array($value)) {
            $value = JsonList::toPlainText($value) ?? implode(', ', BrokerFacts::decodeMangledList($value));
        }

        $text = $this->plainText(is_scalar($value) ? (string) $value : '');

        return $text !== '' ? $text : '—';
    }

    protected function plainText(?string $value): string
    {
        return trim((string) (RichText::toPlainText($value) ?? ''));
    }

    protected function bonusLabel(Broker $broker): string
    {
        $bonus = $broker->forexBonuses
            ->first(fn ($item) => method_exists($item, 'isActivePromotion') && $item->isActivePromotion());

        if (! $bonus) {
            return '—';
        }

        $title = trim(RichText::toPlainText($bonus->title) ?? '');

        return $title !== '' ? $title : 'Live offer available';
    }

    /** @param array<string, mixed> $sourceFacts */
    protected function similarityScore(array $sourceFacts, Broker $candidate): ?float
    {
        $facts = BrokerFacts::for($candidate);
        $hasUsableData = $candidate->rating
            || $candidate->logo
            || $candidate->isRegulated()
            || ($facts['platforms'] ?? []) !== []
            || ($facts['min_deposit']['known'] ?? false);

        if (! $hasUsableData) {
            return null;
        }

        $score = 0.0;
        $rating = (float) ($candidate->rating ?: 0);
        $score += min($rating, 10) * 4;

        if ($candidate->logo) {
            $score += 8;
        }

        if ($candidate->isRegulated()) {
            $score += 18;
        }

        if ($candidate->featured_broker) {
            $score += 4;
        }

        $sourcePlatforms = collect($sourceFacts['platforms'] ?? [])->map(fn ($item) => mb_strtolower((string) $item));
        $candidatePlatforms = collect($facts['platforms'] ?? [])->map(fn ($item) => mb_strtolower((string) $item));
        $score += $sourcePlatforms->intersect($candidatePlatforms)->count() * 10;

        $sourceMarkets = collect($sourceFacts['markets'] ?? [])->map(fn ($item) => mb_strtolower((string) $item));
        $candidateMarkets = collect($facts['markets'] ?? [])->map(fn ($item) => mb_strtolower((string) $item));
        $score += $sourceMarkets->intersect($candidateMarkets)->count() * 6;

        $sourceDeposit = $sourceFacts['min_deposit']['value'] ?? null;
        $candidateDeposit = $facts['min_deposit']['value'] ?? null;
        if (is_numeric($sourceDeposit) && is_numeric($candidateDeposit)) {
            $diff = abs((float) $sourceDeposit - (float) $candidateDeposit);
            if ($diff <= 50) {
                $score += 8;
            } elseif ($diff <= 200) {
                $score += 4;
            }
        }

        $sourceTier = $sourceFacts['regulatory_tier']['tier'] ?? null;
        $candidateTier = $facts['regulatory_tier']['tier'] ?? null;
        if ($sourceTier && $candidateTier && (int) $sourceTier === (int) $candidateTier) {
            $score += 10;
        }

        return $score;
    }
}
