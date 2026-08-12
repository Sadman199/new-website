<?php

namespace App\Services;

use App\Models\Broker;
use Illuminate\Support\Str;

class ScamBrokersIndexService
{
    /** @return array<string, string> */
    public function warningFilters(): array
    {
        return [
            'no-regulation' => 'No regulation',
            'withdrawal-issues' => 'Withdrawal problems',
            'fake-licence' => 'Fake licence',
            'guaranteed-profits' => 'Guaranteed profits',
            'user-reports' => 'User complaints',
        ];
    }

    /** @return array<string, int> */
    public function warningFilterCounts(iterable $brokers): array
    {
        $counts = array_fill_keys(array_keys($this->warningFilters()), 0);

        foreach ($brokers as $broker) {
            $payload = is_array($broker)
                ? $broker
                : $this->serialize($broker);

            foreach ($payload['warning_tags'] ?? [] as $tag) {
                if (isset($counts[$tag])) {
                    $counts[$tag]++;
                }
            }
        }

        return $counts;
    }

    /** @return array<string, int> */
    public function stats(): array
    {
        $brokers = Broker::query()
            ->where('is_scam', true)
            ->get();

        $noRegulation = 0;
        $reportedThisYear = 0;
        $year = (int) date('Y');

        foreach ($brokers as $broker) {
            $tags = $this->warningTagsFor($broker, $broker->regulationList());

            if (in_array('no-regulation', $tags, true)) {
                $noRegulation++;
            }

            if ($broker->scam_reported_date && (int) $broker->scam_reported_date->format('Y') === $year) {
                $reportedThisYear++;
            }
        }

        return [
            'scam_count' => $brokers->count(),
            'no_regulation_count' => $noRegulation,
            'reported_this_year' => $reportedThisYear,
            'withdrawal_issues_count' => $brokers->filter(
                fn (Broker $broker) => in_array(
                    'withdrawal-issues',
                    $this->warningTagsFor($broker, $broker->regulationList()),
                    true
                )
            )->count(),
        ];
    }

    /** @return array<string, mixed> */
    public function serialize(Broker $broker): array
    {
        $regulators = $broker->regulationList();
        $tags = $this->warningTagsFor($broker, $regulators);
        $reportedAt = $broker->scam_reported_date
            ? $broker->scam_reported_date->format('M d, Y')
            : null;

        return [
            'id' => $broker->id,
            'name' => $broker->name,
            'slug' => $broker->slug,
            'scam_slug' => $broker->scam_slug,
            'logo' => $broker->logo ? asset($broker->logo) : null,
            'country' => trim((string) ($broker->country ?: '')) ?: null,
            'scam_reason' => trim((string) ($broker->scam_reason ?: '')),
            'scam_reason_excerpt' => Str::limit(
                trim((string) ($broker->scam_reason ?: 'This broker has been flagged as high-risk based on regulator warnings and verified user reports.')),
                160
            ),
            'reported_at' => $reportedAt,
            'reported_label' => $reportedAt ?: 'Under review',
            'is_regulated' => count($regulators) > 0,
            'regulation_summary' => $regulators !== []
                ? 'Claims: ' . implode(', ', array_slice($regulators, 0, 3))
                : 'No verified regulation',
            'warning_tags' => $tags,
            'detail_url' => route('scam_broker_detail', ['slug' => $broker->scam_slug]),
        ];
    }

    /** @param array<int, string> $regulators */
    /** @return array<int, string> */
    public function warningTagsFor(Broker $broker, array $regulators = []): array
    {
        $tags = [];
        $haystack = strtolower(trim(implode(' ', array_filter([
            (string) $broker->scam_reason,
            (string) $broker->short_description,
            (string) $broker->top_feature,
            (string) $broker->country,
        ]))));

        if ($regulators === []) {
            $tags[] = 'no-regulation';
        } elseif (
            str_contains($haystack, 'fake')
            || str_contains($haystack, 'clone')
            || str_contains($haystack, 'licen')
            || str_contains($haystack, 'license')
            || str_contains($haystack, 'unauthor')
            || str_contains($haystack, 'revoked')
            || str_contains($haystack, 'misleading')
        ) {
            $tags[] = 'fake-licence';
        } elseif ($broker->is_scam) {
            $tags[] = 'fake-licence';
        }

        if (
            str_contains($haystack, 'withdraw')
            || str_contains($haystack, 'payout')
            || str_contains($haystack, 'cash out')
            || str_contains($haystack, 'cash-out')
            || str_contains($haystack, 'blocked fund')
            || str_contains($haystack, 'cannot withdraw')
        ) {
            $tags[] = 'withdrawal-issues';
        }

        if (
            str_contains($haystack, 'guarant')
            || str_contains($haystack, 'risk-free')
            || str_contains($haystack, 'risk free')
            || str_contains($haystack, 'fixed return')
            || str_contains($haystack, 'promised return')
        ) {
            $tags[] = 'guaranteed-profits';
        } elseif (
            str_contains($haystack, 'profit')
            && (
                str_contains($haystack, 'promise')
                || str_contains($haystack, 'guarant')
                || str_contains($haystack, 'scheme')
            )
        ) {
            $tags[] = 'guaranteed-profits';
        }

        if (
            str_contains($haystack, 'complaint')
            || str_contains($haystack, 'report')
            || str_contains($haystack, 'victim')
            || str_contains($haystack, 'warning')
            || str_contains($haystack, 'blacklist')
            || str_contains($haystack, 'fraud')
            || str_contains($haystack, 'scam')
        ) {
            $tags[] = 'user-reports';
        }

        if ($tags === []) {
            $tags[] = 'user-reports';
        }

        return array_values(array_unique($tags));
    }

    /** @return array<int, array{title: string, description: string}> */
    public function warningSigns(): array
    {
        return [
            [
                'title' => 'No real regulation',
                'description' => 'Missing or fake licences from FCA, ASIC, CySEC and similar bodies.',
            ],
            [
                'title' => 'Withdrawal problems',
                'description' => 'Delays, endless verification, or surprise fees when you cash out.',
            ],
            [
                'title' => 'Guaranteed profits',
                'description' => 'Aggressive sales calls and promises of risk-free returns.',
            ],
        ];
    }
}
