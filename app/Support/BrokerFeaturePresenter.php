<?php

namespace App\Support;

use App\Models\Broker;

class BrokerFeaturePresenter
{
    /** @return array<int, array{icon: string, label: string}> */
    public static function chips(Broker $broker, int $limit = 6): array
    {
        $chips = [];

        if ($broker->isRegulated()) {
            $regulators = $broker->regulationList();
            $chips[] = [
                'icon' => 'fa-shield-alt',
                'label' => $regulators !== []
                    ? implode(', ', array_slice($regulators, 0, 2))
                    : 'Regulated',
            ];
        }

        if ($broker->minimum_deposit !== null) {
            $chips[] = [
                'icon' => 'fa-wallet',
                'label' => 'Min $' . number_format((float) $broker->minimum_deposit, 0),
            ];
        }

        if (filled($broker->spreads)) {
            $chips[] = [
                'icon' => 'fa-chart-line',
                'label' => strip_tags((string) $broker->spreads),
            ];
        }

        if (filled($broker->leverage)) {
            $chips[] = [
                'icon' => 'fa-bolt',
                'label' => strip_tags((string) $broker->leverage),
            ];
        }

        $platforms = $broker->platformList();
        if ($platforms !== []) {
            $chips[] = [
                'icon' => 'fa-desktop',
                'label' => implode(', ', array_slice($platforms, 0, 2)),
            ];
        }

        if ($broker->demo_account_available) {
            $chips[] = ['icon' => 'fa-flask', 'label' => 'Demo account'];
        }

        if ($broker->social_trading) {
            $chips[] = ['icon' => 'fa-users', 'label' => 'Social trading'];
        }

        if ($broker->vps_hosting) {
            $chips[] = ['icon' => 'fa-server', 'label' => 'VPS hosting'];
        }

        if ($broker->investor_protection) {
            $chips[] = ['icon' => 'fa-umbrella', 'label' => 'Investor protection'];
        }

        if ($broker->negative_balance_protection) {
            $chips[] = ['icon' => 'fa-lock', 'label' => 'Negative balance protection'];
        }

        if ($broker->economic_calendar) {
            $chips[] = ['icon' => 'fa-calendar-alt', 'label' => 'Economic calendar'];
        }

        if ($broker->account_managers) {
            $chips[] = ['icon' => 'fa-headset', 'label' => 'Account managers'];
        }

        return array_slice($chips, 0, $limit);
    }

    /** @return array<string, mixed> */
    public static function toSpotlightPayload(Broker $broker): array
    {
        $allFeatures = self::chips($broker, 20);
        $visitUrl = $broker->open_live ?: $broker->visit_site ?: $broker->url;

        return [
            'name' => $broker->name,
            'slug' => $broker->slug,
            'logo' => $broker->logo
                ? asset($broker->logo)
                : asset('images/default-broker.png'),
            'rating' => $broker->rating !== null ? round((float) $broker->rating, 1) : null,
            'country' => $broker->country,
            'top_feature' => trim((string) ($broker->top_feature ?: '')),
            'review_url' => route('broker_detail', $broker->slug),
            'visit_url' => $visitUrl ?: null,
            'features' => array_slice($allFeatures, 0, 6),
            'feature_count' => count($allFeatures),
        ];
    }
}
