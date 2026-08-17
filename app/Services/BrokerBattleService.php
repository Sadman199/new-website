<?php

namespace App\Services;

use App\Models\Broker;

class BrokerBattleService
{
    public function __construct(
        protected BrokerComparisonService $comparisonService,
    ) {
    }

    public static function canonicalBattleUrl(string $slug1, string $slug2): string
    {
        $slugs = collect([$slug1, $slug2])->filter()->sort()->values();

        return route('brokers.battle', [
            'broker1_slug' => $slugs[0],
            'broker2_slug' => $slugs[1],
        ]);
    }

    /**
     * @param  array<string, mixed>  $comparison
     * @return array<string, mixed>
     */
    public function buildBattle(Broker $broker1, Broker $broker2, array $comparison): array
    {
        $left = $comparison['broker1'] ?? [];
        $right = $comparison['broker2'] ?? [];
        $rounds = $this->categoryRounds($left, $right, $comparison);

        $leftWins = collect($rounds)->where('outcome', 'broker1')->count();
        $rightWins = collect($rounds)->where('outcome', 'broker2')->count();
        $ties = collect($rounds)->where('outcome', 'tie')->count();
        $insufficient = collect($rounds)->where('outcome', 'insufficient')->count();

        $leftScore = $this->overallScore($left);
        $rightScore = $this->overallScore($right);
        $winner = $this->resolveWinner($left, $right, $leftWins, $rightWins, $leftScore, $rightScore);

        return [
            'broker1' => $left,
            'broker2' => $right,
            'rounds' => $rounds,
            'wins' => [
                'broker1' => $leftWins,
                'broker2' => $rightWins,
                'ties' => $ties,
                'insufficient' => $insufficient,
            ],
            'scores' => [
                'broker1' => $leftScore,
                'broker2' => $rightScore,
            ],
            'winner' => $winner,
            'share_title' => ($left['name'] ?? 'Broker') . ' vs ' . ($right['name'] ?? 'Broker') . ' Broker Battle',
            'year' => (int) date('Y'),
        ];
    }

    /**
     * @param  array<string, mixed>  $left
     * @param  array<string, mixed>  $right
     * @param  array<string, mixed>  $comparison
     * @return array<int, array<string, mixed>>
     */
    protected function categoryRounds(array $left, array $right, array $comparison): array
    {
        $rounds = [];

        foreach ($comparison['sections'] ?? [] as $section) {
            foreach ($section['rows'] ?? [] as $row) {
                $rounds[] = $this->roundFromRow($row);
            }
        }

        // Prefer bonus presence when available from comparison promotions.
        $leftPromo = $comparison['promotions']['broker1'] ?? null;
        $rightPromo = $comparison['promotions']['broker2'] ?? null;
        if ($leftPromo || $rightPromo) {
            $rounds[] = $this->round(
                'Bonus / promotion',
                $leftPromo ? ($leftPromo['offer'] ?? $leftPromo['title'] ?? 'Active promo') : '—',
                $rightPromo ? ($rightPromo['offer'] ?? $rightPromo['title'] ?? 'Active promo') : '—',
                $this->textPresenceWinner($leftPromo, $rightPromo),
            );
        }

        return $rounds;
    }

    /**
     * @param  array{label: string, left: string, right: string, winner: ?string}  $row
     * @return array<string, mixed>
     */
    protected function roundFromRow(array $row): array
    {
        $left = (string) ($row['left'] ?? '—');
        $right = (string) ($row['right'] ?? '—');
        $winner = $row['winner'] ?? null;

        if (in_array($winner, ['broker1', 'broker2'], true)) {
            $outcome = $winner;
        } elseif (! $this->hasComparableValue($left) || ! $this->hasComparableValue($right)) {
            $outcome = 'insufficient';
        } elseif ($this->normalizeDisplay($left) === $this->normalizeDisplay($right)) {
            $outcome = 'tie';
        } else {
            // Different free-text values with no ranking rule — never invent a winner.
            $outcome = 'insufficient';
        }

        return $this->round((string) $row['label'], $left, $right, $outcome);
    }

    /**
     * @return array<string, mixed>
     */
    protected function round(string $label, string $left, string $right, string $outcome): array
    {
        return [
            'label' => $label,
            'left' => $left !== '' ? $left : '—',
            'right' => $right !== '' ? $right : '—',
            'outcome' => $outcome,
            'winner_label' => match ($outcome) {
                'broker1', 'broker2' => 'Win',
                'tie' => 'Tie',
                default => 'Not enough data',
            },
        ];
    }

    /**
     * @param  array<string, mixed>  $broker
     * @return array{value: ?float, display: string, evidence_count: int}
     */
    protected function overallScore(array $broker): array
    {
        $evidence = [];

        $rating = is_numeric($broker['rating'] ?? null) ? (float) $broker['rating'] : null;
        if ($rating !== null) {
            $evidence[] = min(10.0, max(0.0, $rating * 2));
        }

        $safety = is_numeric($broker['safety']['overall_score'] ?? null)
            ? (float) $broker['safety']['overall_score']
            : null;
        if ($safety !== null) {
            $evidence[] = min(10.0, max(0.0, $safety / 10));
        }

        $trust = is_numeric($broker['trust_score'] ?? null) ? (float) $broker['trust_score'] : null;
        if ($trust !== null) {
            $evidence[] = min(10.0, max(0.0, $trust / 10));
        }

        foreach ($broker['category_scores'] ?? [] as $score) {
            if (is_numeric($score)) {
                $evidence[] = min(10.0, max(0.0, (float) $score));
            }
        }

        if ($evidence === []) {
            return [
                'value' => null,
                'display' => '—',
                'evidence_count' => 0,
            ];
        }

        $value = round(array_sum($evidence) / count($evidence), 1);

        return [
            'value' => $value,
            'display' => number_format($value, 1) . '/10',
            'evidence_count' => count($evidence),
        ];
    }

    /**
     * @param  array<string, mixed>  $left
     * @param  array<string, mixed>  $right
     * @param  array{value: ?float, display: string, evidence_count: int}  $leftScore
     * @param  array{value: ?float, display: string, evidence_count: int}  $rightScore
     * @return array{broker: ?string, name: ?string, reason: string}|null
     */
    protected function resolveWinner(
        array $left,
        array $right,
        int $leftWins,
        int $rightWins,
        array $leftScore,
        array $rightScore
    ): ?array {
        if ($leftWins !== $rightWins) {
            $key = $leftWins > $rightWins ? 'broker1' : 'broker2';
            $winner = $key === 'broker1' ? $left : $right;
            $loserWins = $key === 'broker1' ? $rightWins : $leftWins;
            $winnerWins = $key === 'broker1' ? $leftWins : $rightWins;

            return [
                'broker' => $key,
                'name' => $winner['name'] ?? null,
                'reason' => ($winner['name'] ?? 'This broker')
                    . ' wins '
                    . $winnerWins
                    . ' '
                    . ($winnerWins === 1 ? 'category' : 'categories')
                    . ', while '
                    . (($key === 'broker1' ? $right['name'] : $left['name']) ?? 'the other broker')
                    . ' wins '
                    . $loserWins
                    . '.',
            ];
        }

        $leftValue = $leftScore['value'];
        $rightValue = $rightScore['value'];
        $minEvidence = 2;

        if (
            $leftValue !== null
            && $rightValue !== null
            && ($leftScore['evidence_count'] ?? 0) >= $minEvidence
            && ($rightScore['evidence_count'] ?? 0) >= $minEvidence
            && $leftValue !== $rightValue
        ) {
            $key = $leftValue > $rightValue ? 'broker1' : 'broker2';
            $winner = $key === 'broker1' ? $left : $right;

            return [
                'broker' => $key,
                'name' => $winner['name'] ?? null,
                'reason' => 'Categories are tied, so the higher evidence-based battle score decides the winner.',
            ];
        }

        return null;
    }

    protected function textPresenceWinner(mixed $left, mixed $right): string
    {
        $l = ! empty($left);
        $r = ! empty($right);

        if ($l && ! $r) {
            return 'broker1';
        }

        if ($r && ! $l) {
            return 'broker2';
        }

        if ($l && $r) {
            return 'tie';
        }

        return 'insufficient';
    }

    protected function hasComparableValue(string $value): bool
    {
        $value = trim($value);

        return $value !== '' && $value !== '—' && strcasecmp($value, 'n/a') !== 0;
    }

    protected function normalizeDisplay(string $value): string
    {
        return strtolower(trim(preg_replace('/\s+/', ' ', $value) ?? $value));
    }
}
