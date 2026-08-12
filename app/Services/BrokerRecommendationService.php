<?php

namespace App\Services;

use App\Models\Broker;
use App\Support\BrokerMatchQuiz;
use App\Support\BrokerTaxonomy;
use App\Support\FindMyBrokerFilters;
use Illuminate\Support\Str;

class BrokerRecommendationService
{
    public function __construct(
        protected BrokerReviewsIndexService $reviewsIndexService,
        protected BrokerAssessmentService $assessmentService,
    ) {}

    /**
     * @param  array<string, mixed>  $answers
     * @return array<string, mixed>
     */
    public function recommend(array $answers): array
    {
        $normalized = $this->normalizeAnswers($answers);
        $weights = $this->dimensionWeights($normalized);

        $brokers = Broker::query()
            ->where('is_scam', false)
            ->with(['accountOptions'])
            ->withCount(['reviews as approved_review_count' => fn ($q) => $q->where('status', 1)])
            ->get();

        $scored = $brokers
            ->map(function (Broker $broker) use ($normalized, $weights) {
                $dimensions = $this->scoreDimensions($broker, $normalized);
                $reasons = $this->buildReasons($broker, $normalized, $dimensions);
                $score = $this->weightedScore($dimensions, $weights);

                return [
                    'score' => $score,
                    'dimensions' => $dimensions,
                    'reasons' => $reasons,
                    'broker' => $broker,
                ];
            })
            ->sortByDesc('score')
            ->values();

        $top = $scored->take(3)->values();
        $profile = $this->buildProfile($normalized);

        $results = $top->map(function (array $row, int $index) use ($profile) {
            $broker = $row['broker'];
            $data = $this->reviewsIndexService->serialize($broker);
            $data['match_percent'] = (int) round($row['score']);
            $data['match_breakdown'] = $row['dimensions'];
            $data['match_reasons'] = array_slice($row['reasons'], 0, 4);
            $data['minimum_deposit'] = $broker->minimum_deposit !== null
                ? '$' . number_format((float) $broker->minimum_deposit, 0)
                : '—';
            $data['regulation_summary'] = implode(', ', array_slice($broker->regulationList(), 0, 2)) ?: '—';
            $data['performance'] = array_slice($this->assessmentService->cardMetrics($broker), 0, 2);
            $data['is_best_match'] = $index === 0;
            $data['profile_fit'] = $this->profileFitLabel($row['score']);

            return $data;
        })->all();

        return [
            'brokers' => $results,
            'profile' => $profile,
            'match_url' => $this->buildMatchUrl($normalized),
            'compare_url' => $this->buildCompareUrl($results),
            'summary' => $this->buildSummary($normalized),
            'meta' => [
                'evaluated' => $brokers->count(),
                'dimension_labels' => BrokerMatchQuiz::dimensionLabels(),
            ],
        ];
    }

    /** @param  array<string, mixed>  $answers */
    protected function normalizeAnswers(array $answers): array
    {
        return [
            'country' => (string) ($answers['country'] ?? 'global'),
            'markets' => array_values(array_filter((array) ($answers['markets'] ?? []))),
            'experience' => (string) ($answers['experience'] ?? 'intermediate'),
            'cost_focus' => (string) ($answers['cost_focus'] ?? 'balanced'),
            'activity' => (string) ($answers['activity'] ?? 'weekly'),
            'deposit' => (string) ($answers['deposit'] ?? '100'),
            'extras' => array_values(array_filter((array) ($answers['extras'] ?? []))),
        ];
    }

    /** @return array<string, int> */
    protected function dimensionWeights(array $answers): array
    {
        $weights = [
            'safety' => 22,
            'costs' => 18,
            'markets' => 24,
            'access' => 18,
            'features' => 18,
        ];

        if ($answers['cost_focus'] === 'low') {
            $weights['costs'] += 12;
            $weights['features'] -= 6;
        } elseif ($answers['cost_focus'] === 'premium') {
            $weights['safety'] += 12;
            $weights['costs'] -= 8;
        }

        if ($answers['experience'] === 'beginner') {
            $weights['features'] += 8;
            $weights['costs'] -= 4;
        } elseif ($answers['experience'] === 'professional') {
            $weights['costs'] += 8;
            $weights['markets'] += 4;
        }

        if ($answers['activity'] === 'investor') {
            $weights['safety'] += 10;
            $weights['costs'] -= 6;
        } elseif ($answers['activity'] === 'daily') {
            $weights['costs'] += 8;
            $weights['features'] += 4;
        }

        if ($answers['country'] !== 'global') {
            $weights['access'] += 8;
        }

        $total = array_sum($weights) ?: 1;

        return array_map(fn (int $w) => (int) round(($w / $total) * 100), $weights);
    }

    /** @return array<string, int> */
    protected function scoreDimensions(Broker $broker, array $answers): array
    {
        return [
            'safety' => $this->dimensionSafety($broker, $answers),
            'costs' => $this->dimensionCosts($broker, $answers),
            'markets' => $this->dimensionMarkets($broker, $answers),
            'access' => $this->dimensionAccess($broker, $answers),
            'features' => $this->dimensionFeatures($broker, $answers),
        ];
    }

    /** @param  array<string, int>  $dimensions
     * @param  array<string, int>  $weights
     */
    protected function weightedScore(array $dimensions, array $weights): float
    {
        $sum = 0;
        $weightTotal = 0;

        foreach ($dimensions as $key => $value) {
            $w = $weights[$key] ?? 0;
            $sum += $value * $w;
            $weightTotal += $w;
        }

        return $weightTotal > 0 ? round($sum / $weightTotal, 1) : 0;
    }

    protected function dimensionSafety(Broker $broker, array $answers): int
    {
        $score = 40;

        if ($broker->regulatory_tier === 1) {
            $score += 30;
        } elseif ($broker->regulatory_tier === 2) {
            $score += 18;
        } elseif ($broker->regulationList() !== []) {
            $score += 10;
        }

        if ($broker->investor_protection) {
            $score += 12;
        }
        if ($broker->segregation_of_funds) {
            $score += 8;
        }
        if ($broker->rating !== null) {
            $score += min(10, (int) round(((float) $broker->rating - 3) * 5));
        }

        return min(100, max(0, $score));
    }

    protected function dimensionCosts(Broker $broker, array $answers): int
    {
        $score = 45;

        if ($this->hasLowSpreadSignal($broker)) {
            $score += 25;
        }
        if ($this->matchesTerm($broker, ['zero commission', 'no commission', 'commission free'])) {
            $score += 15;
        }
        if (in_array($broker->fee_level, ['low', 'Low'], true)) {
            $score += 12;
        }

        $scores = is_array($broker->category_scores) ? $broker->category_scores : [];
        if (isset($scores['fees']) && is_numeric($scores['fees'])) {
            $score += min(15, (int) round(((float) $scores['fees'] / 5) * 15));
        }

        if ($answers['cost_focus'] === 'premium' && !$this->hasLowSpreadSignal($broker)) {
            $score = max($score - 10, 55);
        }

        return min(100, max(0, $score));
    }

    protected function dimensionMarkets(Broker $broker, array $answers): int
    {
        if ($answers['markets'] === []) {
            return 70;
        }

        $hits = 0;
        foreach ($answers['markets'] as $market) {
            if ($this->matchesTerm($broker, FindMyBrokerFilters::searchTerms('markets', $market))) {
                $hits++;
            }
        }

        $ratio = $hits / max(1, count($answers['markets']));

        return (int) round(35 + ($ratio * 65));
    }

    protected function dimensionAccess(Broker $broker, array $answers): int
    {
        $score = 50;
        $depositMax = is_numeric($answers['deposit']) ? (float) $answers['deposit'] : 100.0;

        if ($answers['country'] === 'global' || $this->matchesCountry($broker, $answers['country'])) {
            $score += 25;
        } else {
            $score -= 20;
        }

        if ($broker->minimum_deposit !== null) {
            if ((float) $broker->minimum_deposit <= $depositMax) {
                $score += 20;
            } else {
                $score -= 25;
            }
        } else {
            $score += 8;
        }

        return min(100, max(0, $score));
    }

    protected function dimensionFeatures(Broker $broker, array $answers): int
    {
        $score = 40;

        switch ($answers['experience']) {
            case 'beginner':
                if ($broker->demo_account_available) {
                    $score += 20;
                }
                break;
            case 'professional':
                if ($this->matchesTerm($broker, ['raw', 'ecn'])) {
                    $score += 18;
                }
                break;
            case 'active':
                if ($this->hasLowSpreadSignal($broker)) {
                    $score += 12;
                }
                break;
            default:
                $score += 8;
        }

        if ($broker->demo_account_available) {
            $score += 6;
        }
        if ($broker->educational_resources) {
            $score += 8;
        }
        if ($broker->mobile_trading) {
            $score += 8;
        }
        if ($broker->platformList() !== []) {
            $score += min(10, count($broker->platformList()) * 3);
        }

        if ($answers['extras'] !== []) {
            $extraHits = 0;
            foreach ($answers['extras'] as $extra) {
                if ($this->matchesExtra($broker, $extra)) {
                    $extraHits++;
                }
            }
            $score += (int) round(($extraHits / count($answers['extras'])) * 25);
        } else {
            $score += 10;
        }

        return min(100, max(0, $score));
    }

    /** @param  array<string, int>  $dimensions
     * @return array<int, string>
     */
    protected function buildReasons(Broker $broker, array $answers, array $dimensions): array
    {
        $reasons = [];

        if ($dimensions['safety'] >= 75) {
            $reasons[] = 'Strong regulatory profile';
        }
        if ($dimensions['costs'] >= 75 && $answers['cost_focus'] !== 'premium') {
            $reasons[] = 'Competitive trading costs';
        }
        if ($dimensions['markets'] >= 80) {
            $reasons[] = 'Covers your chosen markets';
        }
        if ($dimensions['access'] >= 80) {
            $reasons[] = 'Accessible for your region & budget';
        }
        if ($dimensions['features'] >= 75) {
            $reasons[] = 'Platform matches your profile';
        }
        if ($broker->demo_account_available && $answers['experience'] === 'beginner') {
            $reasons[] = 'Demo account for practice';
        }
        foreach ($answers['extras'] as $extra) {
            if ($this->matchesExtra($broker, $extra)) {
                $reasons[] = $this->extraReason($extra);
            }
        }

        return array_values(array_unique($reasons));
    }

    /** @param  array<string, mixed>  $answers
     * @return array<string, mixed>
     */
    protected function buildProfile(array $answers): array
    {
        $catalogs = FindMyBrokerFilters::catalogs();
        $tags = [];

        if ($answers['country'] !== 'global') {
            $tags[] = BrokerTaxonomy::countries()[$answers['country']] ?? 'Your region';
        }
        foreach ($answers['markets'] as $market) {
            if (isset($catalogs['markets'][$market])) {
                $tags[] = $catalogs['markets'][$market];
            }
        }

        $experienceMap = [
            'beginner' => 'New trader',
            'intermediate' => 'Intermediate',
            'active' => 'Active trader',
            'professional' => 'Professional',
        ];
        $costMap = [
            'low' => 'Cost-focused',
            'balanced' => 'Value seeker',
            'premium' => 'Premium quality',
        ];

        $title = ($experienceMap[$answers['experience']] ?? 'Trader') . ' · ' . ($costMap[$answers['cost_focus']] ?? 'Balanced');

        return [
            'title' => $title,
            'tags' => array_slice($tags, 0, 6),
            'experience' => $experienceMap[$answers['experience']] ?? '',
            'cost_focus' => $costMap[$answers['cost_focus']] ?? '',
            'deposit' => '$' . $answers['deposit'] . ($answers['deposit'] === '500' ? '+' : ' max'),
        ];
    }

    protected function profileFitLabel(float $score): string
    {
        return match (true) {
            $score >= 88 => 'Excellent fit',
            $score >= 78 => 'Strong fit',
            $score >= 68 => 'Good fit',
            default => 'Moderate fit',
        };
    }

    protected function matchesCountry(Broker $broker, string $slug): bool
    {
        if ($slug === 'global') {
            return true;
        }

        $haystacks = [
            json_encode($broker->associated_countries ?? []),
            (string) $broker->country,
            (string) $broker->regions,
        ];

        foreach ($haystacks as $haystack) {
            if (stripos($haystack, $slug) !== false || stripos($haystack, str_replace('-', ' ', $slug)) !== false) {
                return true;
            }
        }

        return false;
    }

    /** @param  array<int, string>  $terms */
    protected function matchesTerm(Broker $broker, array $terms): bool
    {
        $blob = strtolower(implode(' ', [
            (string) $broker->short_description,
            is_array($broker->markets) ? json_encode($broker->markets) : (string) $broker->markets,
            (string) $broker->top_feature,
            (string) $broker->spreads,
            is_array($broker->account_types) ? json_encode($broker->account_types) : (string) $broker->account_types,
            is_array($broker->platforms) ? json_encode($broker->platforms) : (string) $broker->platforms,
            (string) $broker->commission,
        ]));

        foreach ($terms as $term) {
            if ($term !== '' && str_contains($blob, strtolower($term))) {
                return true;
            }
        }

        return false;
    }

    protected function hasLowSpreadSignal(Broker $broker): bool
    {
        if ($this->matchesTerm($broker, FindMyBrokerFilters::searchTerms('spread', 'low'))) {
            return true;
        }

        return $broker->accountOptions->contains(function ($option) {
            $pips = $option->spread_from_pips ?? $option->spread_value;

            return $pips !== null && (float) $pips <= 1.2;
        });
    }

    protected function matchesExtra(Broker $broker, string $extra): bool
    {
        return match ($extra) {
            'copy_trading' => $this->matchesTerm($broker, FindMyBrokerFilters::searchTerms('features', 'copy_trading')),
            'islamic' => $this->matchesTerm($broker, ['islamic', 'swap-free', 'swap free', 'halal']),
            'vps' => (bool) $broker->vps_hosting || $this->matchesTerm($broker, ['vps']),
            'mobile' => (bool) $broker->mobile_trading || $this->matchesTerm($broker, ['mobile', 'app']),
            'education' => (bool) $broker->educational_resources || (bool) $broker->research_tools,
            'bonuses' => $broker->accountOptions->contains(fn ($ao) => (bool) $ao->bonus_eligibility)
                || $this->matchesTerm($broker, ['bonus', 'promotion', 'deposit bonus']),
            default => false,
        };
    }

    protected function extraReason(string $extra): string
    {
        return match ($extra) {
            'copy_trading' => 'Copy trading available',
            'islamic' => 'Islamic / swap-free options',
            'vps' => 'VPS hosting offered',
            'mobile' => 'Strong mobile offering',
            'education' => 'Education & research tools',
            'bonuses' => 'Deposit bonus eligible',
            default => 'Matches your preferences',
        };
    }

    /** @param  array<string, mixed>  $answers */
    protected function buildMatchUrl(array $answers): string
    {
        $params = [];

        if ($answers['country'] !== 'global') {
            $params['country'] = $answers['country'];
        }
        if ($answers['markets'] !== []) {
            $params['markets'] = implode(',', $answers['markets']);
        }
        if ($answers['deposit'] !== '') {
            $params['min_deposit'] = $answers['deposit'];
        }
        if ($answers['cost_focus'] === 'low') {
            $params['spread'] = 'low';
        }
        if ($answers['experience'] === 'beginner') {
            $params['rating'] = '4';
        }
        if (in_array('copy_trading', $answers['extras'], true)) {
            $params['features'] = 'copy_trading';
        }
        if (in_array('islamic', $answers['extras'], true)) {
            $params['account_type'] = implode(',', array_unique(array_merge(
                FindMyBrokerFilters::parseList($params['account_type'] ?? ''),
                ['islamic']
            )));
        }
        if (in_array('vps', $answers['extras'], true)) {
            $features = FindMyBrokerFilters::parseList($params['features'] ?? '');
            $features[] = 'vps';
            $params['features'] = implode(',', array_unique($features));
        }
        if (in_array('bonuses', $answers['extras'], true)) {
            $params['deposit_bonus'] = '1';
        }
        if ($answers['cost_focus'] === 'premium') {
            $params['rating'] = '4.5';
        }

        $query = FindMyBrokerFilters::buildCanonicalQuery($params);

        return url('/find-my-broker') . ($query ? '?' . $query : '');
    }

    /** @param  array<int, array<string, mixed>>  $results */
    protected function buildCompareUrl(array $results): ?string
    {
        if (count($results) < 2) {
            return null;
        }

        $slug1 = $results[0]['slug'] ?? null;
        $slug2 = $results[1]['slug'] ?? null;

        if (! $slug1 || ! $slug2) {
            return null;
        }

        return BrokerComparisonService::canonicalPairUrl($slug1, $slug2);
    }

    /** @param  array<string, mixed>  $answers */
    protected function buildSummary(array $answers): string
    {
        $profile = $this->buildProfile($answers);

        return 'Profile: ' . $profile['title'];
    }
}
