<?php

namespace App\Services;

use App\Models\Broker;
use App\Models\BrokerReport;
use Illuminate\Support\Str;

class ScamBrokerDetailService
{
    public function __construct(
        private readonly ScamBrokersIndexService $indexService,
    ) {}

    /** @return array<string, mixed> */
    public function build(Broker $broker): array
    {
        $regulators = $broker->regulationList();
        $platforms = $broker->platformList();
        $warningTags = $this->indexService->warningTagsFor($broker, $regulators);
        $warningFilters = $this->indexService->warningFilters();
        $reportedAt = $broker->scam_reported_date;
        $reason = trim((string) ($broker->scam_reason ?: ''));

        return [
            'broker' => $broker,
            'breadcrumb' => $this->breadcrumb($broker),
            'hero' => [
                'name' => $broker->name,
                'logo' => $broker->logo ? asset($broker->logo) : null,
                'country' => trim((string) ($broker->country ?: '')) ?: null,
                'reported_date' => $reportedAt?->format('M j, Y'),
                'reported_iso' => $reportedAt?->toDateString(),
                'reported_relative' => $reportedAt?->diffForHumans(),
                'reported_label' => $reportedAt ? 'Flagged '.$reportedAt->format('M j, Y') : 'Under editorial review',
                'days_since_report' => $reportedAt ? (int) $reportedAt->diffInDays(now()) : null,
                'warning_tags' => collect($warningTags)
                    ->map(fn (string $tag) => [
                        'key' => $tag,
                        'label' => $warningFilters[$tag] ?? ucfirst(str_replace('-', ' ', $tag)),
                    ])
                    ->values()
                    ->all(),
                'risk_level' => $this->riskLevel($warningTags, $regulators),
                'rating' => $broker->rating !== null ? round((float) $broker->rating, 1) : null,
                'trust_score' => $broker->trust_score,
            ],
            'flag' => [
                'title' => 'Scam flag reason',
                'reason' => $reason !== ''
                    ? $reason
                    : 'This broker has been flagged as high-risk based on regulatory warnings, withdrawal complaints, and patterns consistent with fraudulent forex operations. We advise against depositing funds until these concerns are resolved.',
                'has_custom_reason' => $reason !== '',
            ],
            'snapshot' => $this->snapshotFacts($broker, $regulators, $platforms),
            'warning_signs' => $this->contextualWarningSigns($warningTags),
            'action_steps' => $this->actionSteps($broker->name),
            'reports' => [
                'count' => BrokerReport::query()->where('broker_id', $broker->id)->count(),
            ],
            'links' => [
                'report' => route('contact').'?subject='.urlencode('Report a scam broker'),
                'scam_checker' => route('broker.scam_checker'),
                'regulated' => route('regulated_brokers'),
                'scam_index' => route('scam_brokers'),
            ],
        ];
    }

    /** @return array<int, array{label: string, url: string|null}> */
    private function breadcrumb(Broker $broker): array
    {
        return [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Scam brokers', 'url' => route('scam_brokers')],
            ['label' => $broker->name, 'url' => null],
        ];
    }

    /**
     * @param  array<int, string>  $regulators
     * @param  array<int, string>  $platforms
     * @return array<int, array{key: string, label: string, value: string, status: string}>
     */
    private function snapshotFacts(Broker $broker, array $regulators, array $platforms): array
    {
        $facts = [];

        $facts[] = [
            'key' => 'regulation',
            'label' => 'Regulation claimed',
            'value' => $regulators !== []
                ? implode(', ', array_slice($regulators, 0, 5))
                : 'None disclosed / not verified',
            'status' => $regulators !== [] ? 'warn' : 'danger',
        ];

        if ($broker->country) {
            $facts[] = [
                'key' => 'country',
                'label' => 'Operating country',
                'value' => $broker->country,
                'status' => 'neutral',
            ];
        }

        if ($broker->year_founded) {
            $facts[] = [
                'key' => 'founded',
                'label' => 'Year founded',
                'value' => (string) $broker->year_founded,
                'status' => 'neutral',
            ];
        }

        if ($broker->minimum_deposit !== null) {
            $facts[] = [
                'key' => 'min_deposit',
                'label' => 'Minimum deposit',
                'value' => '$'.rtrim(rtrim(number_format((float) $broker->minimum_deposit, 2), '0'), '.'),
                'status' => 'neutral',
            ];
        }

        if ($broker->leverage) {
            $facts[] = [
                'key' => 'leverage',
                'label' => 'Max leverage',
                'value' => $this->cleanScalar($broker->leverage),
                'status' => 'neutral',
            ];
        }

        if ($platforms !== []) {
            $facts[] = [
                'key' => 'platforms',
                'label' => 'Platforms',
                'value' => implode(', ', array_slice($platforms, 0, 4)),
                'status' => 'neutral',
            ];
        }

        if ($broker->url || $broker->visit_site) {
            $facts[] = [
                'key' => 'website',
                'label' => 'Website on file',
                'value' => parse_url((string) ($broker->visit_site ?: $broker->url), PHP_URL_HOST) ?: 'Listed',
                'status' => 'warn',
            ];
        }

        if ($broker->trust_score !== null) {
            $facts[] = [
                'key' => 'trust',
                'label' => 'Trust score',
                'value' => (string) $broker->trust_score.'/100',
                'status' => (int) $broker->trust_score < 40 ? 'danger' : 'warn',
            ];
        }

        return $facts;
    }

    /** @param array<int, string> $warningTags */
    private function riskLevel(array $warningTags, array $regulators): string
    {
        $score = count($warningTags);

        if ($regulators === []) {
            $score++;
        }

        if (in_array('withdrawal-issues', $warningTags, true)) {
            $score++;
        }

        return match (true) {
            $score >= 4 => 'critical',
            $score >= 2 => 'high',
            default => 'elevated',
        };
    }

    /** @param array<int, string> $warningTags */
    /** @return array<int, array{title: string, description: string}> */
    private function contextualWarningSigns(array $warningTags): array
    {
        $catalog = [
            'no-regulation' => [
                'title' => 'No verifiable regulation',
                'description' => 'The firm does not hold a licence from a recognised tier-1 authority (FCA, ASIC, CySEC, FSCA, etc.).',
            ],
            'withdrawal-issues' => [
                'title' => 'Withdrawal problems reported',
                'description' => 'Traders report delays, blocked payouts, or surprise fees when requesting withdrawals.',
            ],
            'fake-licence' => [
                'title' => 'Misleading or cloned licence',
                'description' => 'Regulatory claims may be false, expired, or copied from a legitimate broker with a similar name.',
            ],
            'guaranteed-profits' => [
                'title' => 'Guaranteed profit claims',
                'description' => 'Account managers or marketing materials promise risk-free or fixed returns — a classic scam pattern.',
            ],
            'user-reports' => [
                'title' => 'Verified user complaints',
                'description' => 'Multiple independent reports align with fraud indicators: frozen accounts, pressure to re-deposit, or ghost support.',
            ],
        ];

        $signs = [];
        foreach ($warningTags as $tag) {
            if (isset($catalog[$tag])) {
                $signs[] = $catalog[$tag];
            }
        }

        if ($signs === []) {
            return array_values($catalog);
        }

        return $signs;
    }

    /** @return array<int, array{title: string, body: string}> */
    private function actionSteps(string $brokerName): array
    {
        return [
            [
                'title' => 'Stop sending money',
                'body' => "Do not deposit further funds with {$brokerName}. Ignore requests for release fees, taxes, or verification payments to unlock a withdrawal.",
            ],
            [
                'title' => 'Document everything',
                'body' => 'Save chat logs, emails, transaction IDs, account screenshots, and the names of any account managers who contacted you.',
            ],
            [
                'title' => 'Request withdrawal in writing',
                'body' => 'Submit a formal withdrawal request and keep proof of delivery. Note any excuses or changing requirements.',
            ],
            [
                'title' => 'Contact your bank or card issuer',
                'body' => 'Ask about chargebacks or fraud disputes — especially for recent card or wire transfers.',
            ],
            [
                'title' => 'Report to us and regulators',
                'body' => 'File a report with BrokersCourt and the relevant financial authority in your jurisdiction to help warn other traders.',
            ],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function relatedBrokers(Broker $broker, int $limit = 4): array
    {
        return Broker::query()
            ->where('is_scam', true)
            ->where('id', '!=', $broker->id)
            ->orderByDesc('scam_reported_date')
            ->take($limit)
            ->get()
            ->map(fn (Broker $item) => $this->indexService->serialize($item))
            ->values()
            ->all();
    }

    public function scamCount(): int
    {
        return Broker::query()->where('is_scam', true)->count();
    }

    private function cleanScalar(mixed $value): string
    {
        if (is_null($value) || $value === '') {
            return '—';
        }

        if (is_array($value)) {
            return implode(', ', array_map('strval', $value));
        }

        $string = is_string($value) ? $value : (string) $value;
        $decoded = json_decode($string, true);
        if (is_array($decoded)) {
            return implode(', ', array_filter(array_map('strval', $decoded)));
        }

        return trim(strip_tags(html_entity_decode($string, ENT_QUOTES)));
    }
}
