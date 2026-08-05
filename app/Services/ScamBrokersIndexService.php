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
        $reason = strtolower((string) $broker->scam_reason);

        if ($regulators === [] && ! $broker->investor_protection) {
            $tags[] = 'no-regulation';
        }

        if (
            str_contains($reason, 'withdraw')
            || str_contains($reason, 'payout')
            || str_contains($reason, 'cash out')
        ) {
            $tags[] = 'withdrawal-issues';
        }

        if (
            str_contains($reason, 'fake')
            || str_contains($reason, 'clone')
            || str_contains($reason, 'licen')
            || str_contains($reason, 'license')
        ) {
            $tags[] = 'fake-licence';
        }

        if (
            str_contains($reason, 'guarant')
            || str_contains($reason, 'profit')
            || str_contains($reason, 'return')
        ) {
            $tags[] = 'guaranteed-profits';
        }

        if (
            str_contains($reason, 'complaint')
            || str_contains($reason, 'report')
            || str_contains($reason, 'victim')
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
