<?php

namespace App\Services;

use App\Models\Broker;
use App\Models\BrokerReport;
use App\Http\Controllers\Front\BrokerController;
use App\Support\BestBrokerGuideMetrics;

class BrokerSafetyScoreService
{
    /** @var array<string, float> */
    private const WEIGHTS = [
        'regulation' => 0.30,
        'trust' => 0.25,
        'history' => 0.15,
        'protection' => 0.15,
        'reports' => 0.15,
    ];

    /** @return array<string, mixed> */
    public function analyze(Broker $broker): array
    {
        $broker->loadCount([
            'reports as pending_reports_count' => fn ($q) => $q->whereIn('status', ['pending', 'approved']),
        ]);

        $components = [
            'regulation' => $this->regulationComponent($broker),
            'trust' => $this->trustComponent($broker),
            'history' => $this->historyComponent($broker),
            'protection' => $this->protectionComponent($broker),
            'reports' => $this->reportsComponent($broker),
        ];

        $overall = 0;
        foreach ($components as $key => $component) {
            $overall += $component['score'] * self::WEIGHTS[$key];
        }
        $overall = (int) round($overall);

        if ($broker->is_scam) {
            $overall = min($overall, 25);
        }

        $overall = max(0, min(100, $overall));
        $riskLevel = $this->resolveRiskLevel($broker, $overall);
        $riskFactors = $this->riskFactors($broker);
        $safetyFactors = $this->safetyFactors($broker);

        return [
            'broker' => $this->serializeBroker($broker),
            'overall_score' => $overall,
            'risk_level' => $riskLevel,
            'risk_label' => $this->riskLabel($riskLevel),
            'risk_icon' => $this->riskIcon($riskLevel),
            'risk_color' => $this->riskColor($riskLevel),
            'components' => $components,
            'regulation' => $this->regulationDetails($broker),
            'company' => $this->companyDetails($broker),
            'trust' => $this->trustDetails($broker),
            'protection' => $this->protectionDetails($broker),
            'risk_factors' => $riskFactors,
            'safety_factors' => $safetyFactors,
            'show_warning' => $riskLevel === 'high' || $broker->is_scam,
            'warning_title' => $broker->is_scam
                ? 'High Risk Warning'
                : ($riskLevel === 'high' ? 'Trader Caution Recommended' : null),
            'warning_message' => $this->warningMessage($broker, $riskLevel),
            'report_count' => (int) ($broker->pending_reports_count ?? 0),
            'review_url' => route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]),
            'scam_detail_url' => $broker->is_scam
                ? route('scam_broker_detail', ['slug' => $broker->scam_slug])
                : null,
        ];
    }

    /** @return array<string, mixed>|null */
    public function analyzeBySlug(string $slug): ?array
    {
        $broker = $this->findBroker($slug);

        return $broker ? $this->analyze($broker) : null;
    }

    public function findBroker(string $query): ?Broker
    {
        $query = trim($query);
        if ($query === '') {
            return null;
        }

        $slug = \Illuminate\Support\Str::slug($query);

        $broker = Broker::query()
            ->where('slug', $slug)
            ->orWhere('slug', $slug . '-review')
            ->first();

        if ($broker) {
            return $broker;
        }

        $normalized = str_replace([' ', '-'], '', strtolower($query));

        return Broker::query()
            ->get()
            ->first(function (Broker $broker) use ($query, $normalized) {
                $name = strtolower($broker->name);
                $brokerSlug = strtolower($broker->listingSlug());

                return $name === strtolower($query)
                    || str_contains($name, strtolower($query))
                    || str_replace([' ', '-'], '', $name) === $normalized
                    || $brokerSlug === \Illuminate\Support\Str::slug($query);
            });
    }

    /** @return array<int, array<string, mixed>> */
    public function searchSuggestions(string $query, int $limit = 8): array
    {
        $query = trim($query);
        if (strlen($query) < 2) {
            return [];
        }

        $normalized = str_replace([' ', '-'], '', strtolower($query));

        return Broker::query()
            ->orderByDesc('rating')
            ->limit(50)
            ->get()
            ->filter(function (Broker $broker) use ($query, $normalized) {
                $name = strtolower($broker->name);

                return str_contains($name, strtolower($query))
                    || str_replace([' ', '-'], '', $name) === $normalized;
            })
            ->take($limit)
            ->map(function (Broker $broker) {
                $analysis = $this->analyze($broker);

                return [
                    'name' => $broker->name,
                    'slug' => $broker->listingSlug(),
                    'logo_url' => $broker->logo ? asset($broker->logo) : asset('images/default-broker.png'),
                    'overall_score' => $analysis['overall_score'],
                    'risk_level' => $analysis['risk_level'],
                    'risk_label' => $analysis['risk_label'],
                    'url' => route('broker.scam_checker.show', ['slug' => $broker->listingSlug()]),
                ];
            })
            ->values()
            ->all();
    }

    /** @param  array<int, string>  $slugs
     * @return array<int, array<string, mixed>>
     */
    public function compare(array $slugs): array
    {
        return collect($slugs)
            ->map(fn (string $slug) => $this->analyzeBySlug($slug))
            ->filter()
            ->values()
            ->all();
    }

    /** @return array<string, mixed> */
    private function serializeBroker(Broker $broker): array
    {
        return [
            'id' => $broker->id,
            'name' => $broker->name,
            'slug' => $broker->listingSlug(),
            'logo_url' => $broker->logo ? asset($broker->logo) : asset('images/default-broker.png'),
            'is_scam' => (bool) $broker->is_scam,
            'rating' => $broker->rating ? number_format((float) $broker->rating, 1) : null,
        ];
    }

    /** @return array{score: int, label: string} */
    private function regulationComponent(Broker $broker): array
    {
        $regulators = $broker->regulationList();
        $tier = (int) ($broker->regulatory_tier ?? 0);

        $score = match ($tier) {
            1 => 95,
            2 => 72,
            3 => 48,
            default => count($regulators) > 0 ? 55 : 15,
        };

        if (count($regulators) >= 2) {
            $score = min(100, $score + 5);
        }

        if (! $broker->isRegulated()) {
            $score = min($score, 20);
        }

        return [
            'score' => max(0, min(100, $score)),
            'label' => 'Regulation',
        ];
    }

    /** @return array{score: int, label: string} */
    private function trustComponent(Broker $broker): array
    {
        if ($broker->trust_score !== null) {
            $normalized = (int) round(((int) $broker->trust_score / 99) * 100);

            return ['score' => max(0, min(100, $normalized)), 'label' => 'Trust score'];
        }

        $rating = (float) ($broker->rating ?? 0);
        $score = $rating > 0 ? (int) min(100, round(($rating / 10) * 100)) : 40;

        return ['score' => $score, 'label' => 'Trust score'];
    }

    /** @return array{score: int, label: string} */
    private function historyComponent(Broker $broker): array
    {
        $year = (int) ($broker->year_founded ?? 0);
        if ($year <= 0) {
            return ['score' => 25, 'label' => 'Company history'];
        }

        $years = max(0, (int) date('Y') - $year);

        $score = match (true) {
            $years >= 15 => 95,
            $years >= 10 => 82,
            $years >= 5 => 68,
            $years >= 2 => 50,
            default => 35,
        };

        return ['score' => $score, 'label' => 'Company history'];
    }

    /** @return array{score: int, label: string} */
    private function protectionComponent(Broker $broker): array
    {
        $score = 20;

        if ($broker->investor_protection) {
            $score += 35;
        }
        if ($broker->segregation_of_funds) {
            $score += 25;
        }
        if ($broker->negative_balance_protection) {
            $score += 20;
        }

        return ['score' => min(100, $score), 'label' => 'Client protection'];
    }

    /** @return array{score: int, label: string} */
    private function reportsComponent(Broker $broker): array
    {
        $count = (int) ($broker->pending_reports_count ?? 0);

        $score = match (true) {
            $count === 0 => 100,
            $count === 1 => 75,
            $count === 2 => 55,
            $count <= 4 => 35,
            default => 15,
        };

        return ['score' => $score, 'label' => 'Community reports'];
    }

    private function resolveRiskLevel(Broker $broker, int $overall): string
    {
        if ($broker->is_scam) {
            return 'high';
        }

        if ($overall >= 75 && $broker->isRegulated() && (int) ($broker->regulatory_tier ?? 99) <= 2) {
            return 'safe';
        }

        if ($overall >= 55) {
            return 'medium';
        }

        return 'high';
    }

    private function riskLabel(string $level): string
    {
        return match ($level) {
            'safe' => 'Safe Broker',
            'medium' => 'Medium Risk',
            default => 'High Risk',
        };
    }

    private function riskIcon(string $level): string
    {
        return match ($level) {
            'safe' => '🟢',
            'medium' => '🟡',
            default => '🔴',
        };
    }

    private function riskColor(string $level): string
    {
        return match ($level) {
            'safe' => '#22c55e',
            'medium' => '#eab308',
            default => '#ef4444',
        };
    }

    /** @return array<string, mixed> */
    private function regulationDetails(Broker $broker): array
    {
        $regulators = $broker->regulationList();
        $licenses = trim((string) ($broker->regulatory_licenses ?? ''));

        $items = collect($regulators)
            ->map(fn (string $reg) => [
                'label' => $reg . ' Regulated',
                'positive' => true,
            ])
            ->all();

        if ($licenses !== '') {
            $items[] = ['label' => $licenses, 'positive' => true];
        }

        if (empty($items)) {
            $items[] = ['label' => 'No verified regulation on file', 'positive' => false];
        }

        return [
            'items' => $items,
            'tier' => BestBrokerGuideMetrics::regulatoryTierLabel($broker->regulatory_tier),
            'jurisdictions' => $broker->regulated_jurisdictions ?: '—',
        ];
    }

    /** @return array<string, mixed> */
    private function companyDetails(Broker $broker): array
    {
        return [
            'founded' => $broker->year_founded ?: 'Unknown',
            'country' => $broker->country ?: 'Unknown',
            'years_active' => $broker->year_founded
                ? max(0, (int) date('Y') - (int) $broker->year_founded) . ' years'
                : 'Unknown',
        ];
    }

    /** @return array<string, mixed> */
    private function trustDetails(Broker $broker): array
    {
        $score = $broker->trust_score;
        $label = match (true) {
            $score === null => 'Not rated',
            $score >= 80 => 'High Trust',
            $score >= 60 => 'Moderate Trust',
            default => 'Low Trust',
        };

        return [
            'score' => $score,
            'label' => $label,
            'rating' => $broker->rating ? number_format((float) $broker->rating, 1) . '/10' : null,
        ];
    }

    /** @return array<string, mixed> */
    private function protectionDetails(Broker $broker): array
    {
        return [
            'items' => [
                ['label' => 'Client Fund Protection', 'active' => (bool) $broker->investor_protection],
                ['label' => 'Segregated Client Funds', 'active' => (bool) $broker->segregation_of_funds],
                ['label' => 'Negative Balance Protection', 'active' => (bool) $broker->negative_balance_protection],
            ],
        ];
    }

    /** @return array<int, array{icon: string, text: string}> */
    private function riskFactors(Broker $broker): array
    {
        $factors = [];

        if ($broker->is_scam) {
            $factors[] = ['icon' => '⚠', 'text' => 'Flagged in BrokersCourt scam database'];
        }

        if (! $broker->isRegulated()) {
            $factors[] = ['icon' => '⚠', 'text' => 'No verified regulation on file'];
        } elseif ((int) ($broker->regulatory_tier ?? 0) > 1) {
            $factors[] = ['icon' => '⚠', 'text' => 'No Tier-1 regulation'];
        }

        if (! $broker->year_founded || ((int) date('Y') - (int) $broker->year_founded) < 3) {
            $factors[] = ['icon' => '⚠', 'text' => 'Limited company history'];
        }

        if ($broker->trust_score !== null && (int) $broker->trust_score < 55) {
            $factors[] = ['icon' => '⚠', 'text' => 'Below-average trust score'];
        }

        if ((int) ($broker->pending_reports_count ?? 0) > 0) {
            $factors[] = ['icon' => '⚠', 'text' => 'Community reports on file'];
        }

        $verdict = strtolower(trim((string) ($broker->verdict ?? '')));
        if (str_contains($verdict, 'avoid') || str_contains($verdict, 'caution') || str_contains($verdict, 'risk')) {
            $factors[] = ['icon' => '⚠', 'text' => 'Editorial caution noted in broker review'];
        }

        if ($broker->scam_reason && ! $broker->is_scam) {
            $factors[] = ['icon' => '⚠', 'text' => 'Admin safety notes recorded'];
        }

        return $factors;
    }

    /** @return array<int, array{icon: string, text: string}> */
    private function safetyFactors(Broker $broker): array
    {
        $factors = [];

        if ($broker->isRegulated()) {
            $factors[] = ['icon' => '✓', 'text' => 'Regulated broker'];
        }

        if ($broker->year_founded && ((int) date('Y') - (int) $broker->year_founded) >= 5) {
            $factors[] = ['icon' => '✓', 'text' => 'Established company'];
        }

        if ((int) ($broker->regulatory_tier ?? 0) === 1) {
            $factors[] = ['icon' => '✓', 'text' => 'Tier-1 regulatory oversight'];
        }

        if ($broker->trust_score !== null && (int) $broker->trust_score >= 75) {
            $factors[] = ['icon' => '✓', 'text' => 'Strong trust score'];
        }

        if ((float) ($broker->rating ?? 0) >= 8.0) {
            $factors[] = ['icon' => '✓', 'text' => 'High editorial rating'];
        }

        if ($broker->investor_protection) {
            $factors[] = ['icon' => '✓', 'text' => 'Investor protection in place'];
        }

        return $factors;
    }

    private function warningMessage(Broker $broker, string $riskLevel): ?string
    {
        if ($broker->is_scam) {
            return 'This broker has multiple risk indicators on file. Trader caution is strongly recommended before depositing funds.';
        }

        if ($riskLevel === 'high') {
            return 'This broker has multiple risk indicators. Review regulation, protection features, and community reports before investing.';
        }

        if ($riskLevel === 'medium') {
            return 'Some risk indicators were detected. Compare regulation and protection features before opening an account.';
        }

        return null;
    }
}
