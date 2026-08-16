<?php

namespace App\Services;

use App\Models\Broker;
use App\Support\BrokerRating;
use Illuminate\Support\Str;

class BrokerReviewScoreService
{
    /** @var array<string, array{label: string, icon: string}> */
    private const CATEGORIES = [
        'fees' => ['label' => 'Fees & Costs', 'icon' => 'fa-coins'],
        'safety' => ['label' => 'Safety & Regulation', 'icon' => 'fa-shield-alt'],
        'platforms' => ['label' => 'Trading Platforms', 'icon' => 'fa-desktop'],
        'deposit_withdrawal' => ['label' => 'Deposit & Withdrawal', 'icon' => 'fa-university'],
        'customer_support' => ['label' => 'Customer Support', 'icon' => 'fa-headset'],
        'education' => ['label' => 'Education', 'icon' => 'fa-graduation-cap'],
        'research' => ['label' => 'Research', 'icon' => 'fa-chart-line'],
        'account_opening' => ['label' => 'Account Opening', 'icon' => 'fa-user-check'],
        'products' => ['label' => 'Products & Markets', 'icon' => 'fa-globe'],
    ];

    /** @return array<string, mixed> */
    public function breakdown(Broker $broker): array
    {
        $overall = BrokerRating::outOfFive($broker->rating) ?? 0;
        $categoryScores = is_array($broker->category_scores) ? $broker->category_scores : [];
        $items = [];

        foreach (self::CATEGORIES as $key => $meta) {
            if (! isset($categoryScores[$key]) || $categoryScores[$key] === '' || $categoryScores[$key] === null) {
                continue;
            }

            $score = min(10, max(0, (float) $categoryScores[$key]));
            $tier = $this->tier($score);

            $items[] = [
                'key' => $key,
                'label' => $meta['label'],
                'icon' => $meta['icon'],
                'score' => $score,
                'display' => number_format($score, 1),
                'percent' => (int) round($score * 10),
                'tier' => $tier,
                'tier_label' => $this->tierLabel($tier),
            ];
        }

        usort($items, fn (array $a, array $b) => $b['score'] <=> $a['score']);

        $strengths = array_values(array_filter(
            $items,
            fn (array $item) => $item['score'] >= 7.5
        ));
        $strengths = array_slice($strengths, 0, 3);

        $weaknesses = array_values(array_filter(
            $items,
            fn (array $item) => $item['score'] < 6.5
        ));
        usort($weaknesses, fn (array $a, array $b) => $a['score'] <=> $b['score']);
        $weaknesses = array_slice($weaknesses, 0, 3);

        $updatedAt = $broker->updated_at;

        return [
            'overall' => $overall,
            'overall_display' => number_format($overall, 1),
            'overall_percent' => BrokerRating::percent($broker->rating),
            'trust_score' => $broker->trust_score ? (int) $broker->trust_score : null,
            'items' => $items,
            'strengths' => $strengths,
            'weaknesses' => $weaknesses,
            'average_category' => $items
                ? round(array_sum(array_column($items, 'score')) / count($items), 1)
                : null,
            'updated_at' => $updatedAt?->format('M j, Y'),
            'updated_iso' => $updatedAt?->toDateString(),
            'updated_relative' => $updatedAt?->diffForHumans(),
            'has_scores' => $items !== [],
        ];
    }

    private function tier(float $score): string
    {
        return match (true) {
            $score >= 8.5 => 'excellent',
            $score >= 7.0 => 'good',
            $score >= 5.5 => 'average',
            default => 'weak',
        };
    }

    private function tierLabel(string $tier): string
    {
        return match ($tier) {
            'excellent' => 'Excellent',
            'good' => 'Good',
            'average' => 'Average',
            'weak' => 'Below average',
            default => Str::title($tier),
        };
    }
}
