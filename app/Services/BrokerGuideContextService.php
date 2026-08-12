<?php

namespace App\Services;

use App\Models\Broker;

class BrokerGuideContextService
{
    /** @return array<string, mixed> */
    public function forBroker(Broker $broker): array
    {
        $accountOptions = $broker->relationLoaded('accountOptions')
            ? $broker->accountOptions
            : $broker->accountOptions()->get();

        $activeOptions = $accountOptions->filter(fn ($option) => ($option->is_active ?? true) !== false);

        $minDeposits = $activeOptions
            ->pluck('min_deposit')
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->map(fn ($value) => (float) $value);

        $brokerMinDeposit = $broker->minimum_deposit !== null
            ? (float) $broker->minimum_deposit
            : null;

        $effectiveMinDeposit = $minDeposits->isNotEmpty()
            ? $minDeposits->min()
            : $brokerMinDeposit;

        return [
            'account_options' => $activeOptions,
            'has_swap_free' => $activeOptions->contains(fn ($option) => (bool) $option->swap_free),
            'minimum_deposit' => $effectiveMinDeposit,
            'payment_methods' => $broker->payment_methods,
            'deposit_methods' => $broker->deposit_methods,
            'withdrawal_method' => $broker->withdrawal_method,
            'withdrawal_fee' => $broker->withdrawal_fee,
            'demo_available' => (bool) $broker->demo_account_available,
            'demo_link' => $broker->demo_link ?: $broker->open_demo,
            'live_link' => $broker->open_live ?: $broker->visit_site ?: $broker->url,
        ];
    }
}
