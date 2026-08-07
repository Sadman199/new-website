<?php

namespace App\Support;

use App\Models\PropFirm;

class PropFirmPresenter
{
    public function __construct(protected PropFirm $firm)
    {
    }

    public static function make(PropFirm $firm): self
    {
        return new self($firm);
    }

    public function logoUrl(): ?string
    {
        return $this->firm->logo ? asset($this->firm->logo) : null;
    }

    public function coverUrl(): ?string
    {
        return $this->firm->cover_image ? asset($this->firm->cover_image) : null;
    }

    public function trustLabel(): string
    {
        $score = $this->firm->trust_score;

        if ($score === null) {
            return 'Unrated';
        }

        if ($score >= 9) {
            return 'Excellent';
        }

        if ($score >= 8) {
            return 'Very Good';
        }

        if ($score >= 7) {
            return 'Good';
        }

        return 'Fair';
    }

    public function fundingRange(): string
    {
        $parts = array_filter([
            $this->firm->min_fee !== null ? '$' . number_format((float) $this->firm->min_fee, 0) : null,
            $this->firm->max_fee !== null ? '$' . number_format((float) $this->firm->max_fee, 0) : null,
        ]);

        if ($parts === []) {
            return '—';
        }

        return count($parts) === 2 ? implode(' – ', $parts) : $parts[0];
    }

    /** @return list<array{label: string, value: string}> */
    public function heroStats(): array
    {
        return array_values(array_filter([
            ['label' => 'Max funding', 'value' => $this->firm->max_funding ?: '—'],
            ['label' => 'Profit split', 'value' => $this->firm->profit_split ?: '—'],
            ['label' => 'Trust score', 'value' => $this->firm->trust_score !== null ? number_format((float) $this->firm->trust_score, 1) . '/10' : '—'],
            ['label' => 'Founded', 'value' => $this->firm->founded_year ? (string) $this->firm->founded_year : '—'],
        ], fn ($row) => $row['value'] !== '—'));
    }
}
