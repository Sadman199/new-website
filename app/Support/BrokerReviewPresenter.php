<?php

namespace App\Support;

use App\Models\AccountOption;
use App\Models\Broker;

class BrokerReviewPresenter
{
    /** @return array<int, array<string, mixed>> */
    public static function brokerSections(Broker $broker): array
    {
        $regulations = $broker->regulationList();
        $platforms = $broker->platformList();
        $markets = $broker->marketList();
        $accountTypeLabels = $broker->accountTypeLabelList();
        $categoryScores = is_array($broker->category_scores) ? $broker->category_scores : [];
        $firstAccount = $broker->accountOptions->first();

        $spreadsDisplay = $broker->spreads
            ?: optional($firstAccount)->spread_label
            ?: (optional($firstAccount)->spread_value ? optional($firstAccount)->spread_value . ' pips' : null);

        $commissionDisplay = $broker->commission ?: optional($firstAccount)->commission_display;

        $minDeposit = $broker->minimum_deposit !== null
            ? self::money($broker->minimum_deposit)
            : (optional($firstAccount)->min_deposit !== null ? self::money($firstAccount->min_deposit) : null);

        $leverageDisplay = $broker->leverage ?: optional($firstAccount)->leverage_label;

        $sections = [];

        $sections[] = self::section(
            'brokeroverview',
            $broker->name . ' Overview',
            'Key data on fees, platforms, deposits, and trading conditions',
            null,
            array_filter([
                self::row('Regulation', $regulations ? implode(', ', $regulations) : ($broker->investor_protection ? 'Investor protection in place' : null)),
                self::row('Minimum deposit', $minDeposit),
                self::row('Spreads', $spreadsDisplay),
                self::row('Commission', $commissionDisplay),
                self::row('Leverage', $leverageDisplay),
                self::row('Trading platforms', $platforms ? implode(', ', $platforms) : self::plain($broker->platforms)),
            ]),
            array_filter([
                self::row('Account types', $accountTypeLabels ? implode(', ', $accountTypeLabels) : null),
                self::row('Languages', $broker->languages),
                self::row('Headquarters', $broker->country),
                self::row('Year founded', $broker->year_founded),
                self::row('Customer support', $broker->customer_support, true),
                self::row('Markets', $markets ? implode(', ', array_map('ucfirst', $markets)) : null),
                self::row('Instruments', $broker->instrument_count ? number_format((int) $broker->instrument_count) . '+' : null),
                self::row('Fund security', self::fundSecuritySummary($broker)),
            ])
        );

        $sections[] = self::section(
            'fees',
            'Fees',
            'Spreads, commissions, and non-trading costs',
            self::score($categoryScores, 'fees'),
            array_filter([
                self::row('Minimum deposit', $minDeposit),
                self::row('Spreads', $spreadsDisplay),
                self::row('Commission', $commissionDisplay),
                self::row('Pricing model', $broker->pricing, true),
                self::row('Fee level', self::feeLevelLabel($broker->fee_level)),
                self::row('Withdrawal fee', $broker->withdrawal_fee),
            ]),
            array_filter([
                self::row('Payment methods', $broker->payment_methods, true),
            ])
        );

        $sections[] = self::section(
            'safety',
            'Safety',
            'Regulation, investor protection, and fund security',
            self::score($categoryScores, 'safety'),
            array_filter([
                self::row('Regulation', $regulations ? implode(', ', $regulations) : null),
                self::row('Regulated jurisdictions', $broker->regulated_jurisdictions, true),
                self::row('Licenses', $broker->regulatory_licenses, true),
                self::row('Investor protection', self::boolLabel($broker->investor_protection)),
                self::row('Segregated client funds', self::boolLabel($broker->segregation_of_funds)),
                self::row('Negative balance protection', self::boolLabel($broker->negative_balance_protection)),
            ]),
            array_filter([
                self::row('Insurance / compensation', $broker->insurance, true),
                self::row('Company background', self::companyBackground($broker)),
            ])
        );

        $sections[] = self::section(
            'deposits-withdrawals',
            'Deposit & Withdrawal',
            'Funding methods, fees, and processing options',
            self::score($categoryScores, 'deposit_withdrawal'),
            array_filter([
                self::row('Deposit methods', $broker->deposit_methods, true),
                self::row('Withdrawal methods', $broker->withdrawal_method, true),
                self::row('Withdrawal fee', $broker->withdrawal_fee ?: 'Free'),
                self::row('Payment methods', $broker->payment_methods, true),
            ])
        );

        $sections[] = self::section(
            'platforms',
            'Trading Platforms',
            'Desktop, web, and mobile trading software',
            self::score($categoryScores, 'platforms'),
            array_filter([
                self::row('Platforms', $platforms ? implode(', ', $platforms) : self::plain($broker->platforms)),
                self::row('Mobile trading', $broker->mobile_trading, true),
                self::row('Web trader', $broker->web_trader, true),
                self::row('Charting tools', $broker->charting_tools, true),
                self::row('Social / copy trading', $broker->social_trading, true),
                self::row('VPS hosting', self::availabilityLabel($broker->vps_hosting)),
            ]),
            array_filter([
                self::row('Economic calendar', self::availabilityLabel($broker->economic_calendar)),
                self::row('Dedicated account managers', self::availabilityLabel($broker->account_managers)),
            ])
        );

        $sections[] = self::section(
            'markets-products',
            'Markets & Products',
            'Available instruments and product coverage',
            self::score($categoryScores, 'products'),
            array_filter([
                self::row('Markets', $markets ? implode(', ', array_map('ucfirst', $markets)) : null),
                self::row('Instrument count', $broker->instrument_count ? number_format((int) $broker->instrument_count) . '+' : null),
                self::row('Account types', $accountTypeLabels ? implode(', ', $accountTypeLabels) : null),
                self::row('Standout feature', $broker->top_feature, true),
            ])
        );

        if ($broker->demo_account_available || $broker->demo_link || $broker->open_demo || $broker->demo_duration) {
            $sections[] = self::section(
                'demo-account',
                'Demo Account',
                'Practice trading without risking real money',
                null,
                array_filter([
                    self::row('Demo account', self::availabilityLabel($broker->demo_account_available ?? ($broker->demo_link || $broker->open_demo))),
                    self::row('Demo duration', $broker->demo_duration),
                ])
            );
        }

        $sections[] = self::section(
            'research-education',
            'Research & Education',
            'Analysis tools, news, and learning resources',
            self::score($categoryScores, 'education') ?? self::score($categoryScores, 'research'),
            array_filter([
                self::row('Research tools', $broker->research_tools, true),
                self::row('Educational resources', $broker->educational_resources, true),
                self::row('News & analysis', $broker->news_and_analysis, true),
            ])
        );

        $sections[] = self::section(
            'customer-support',
            'Customer Support',
            'Help channels and service availability',
            self::score($categoryScores, 'customer_support'),
            array_filter([
                self::row('Support channels', $broker->customer_support, true),
                self::row('Languages', $broker->languages),
            ])
        );

        if ($broker->is_scam) {
            $sections[] = self::section(
                'scam-warning',
                'Risk Warning',
                'Important safety information about this broker',
                null,
                array_filter([
                    self::row('Status', 'Flagged as high-risk'),
                    self::row('Reason', $broker->scam_reason, true),
                    self::row('Reported', $broker->scam_reported_date?->format('M j, Y')),
                ])
            );
        }

        return array_values(array_filter($sections, fn ($s) => ! empty($s['preview']) || ! empty($s['more'])));
    }

    /** @return array<int, array{id: string, label: string}> */
    public static function tableOfContents(Broker $broker, iterable $accountOptions): array
    {
        $toc = [
            ['id' => 'gettingstarted', 'label' => 'Overview'],
            ['id' => 'key-stats', 'label' => 'Pros & Cons'],
        ];

        if (strip_tags($broker->description ?? '')) {
            $toc[] = ['id' => 'review-body', 'label' => 'Full Review'];
        }

        foreach (self::brokerSections($broker) as $section) {
            $toc[] = ['id' => $section['id'], 'label' => $section['title']];
        }

        $accountOptions = collect($accountOptions);

        if ($accountOptions->isNotEmpty()) {
            $toc[] = ['id' => 'account-types', 'label' => 'Account Types'];
        }

        if ($broker->relationLoaded('forexBonuses') && $broker->forexBonuses->isNotEmpty()) {
            $toc[] = ['id' => 'broker-promotions', 'label' => 'Promotions'];
        }

        $toc[] = ['id' => 'faqs', 'label' => 'FAQs'];
        $toc[] = ['id' => 'voices', 'label' => 'Comments'];
        $toc[] = ['id' => 'compare', 'label' => 'Compare'];

        return $toc;
    }

    /** @return array<int, array<string, mixed>> */
    public static function accountExpandRows(AccountOption $option): array
    {
        $features = is_array($option->features) ? $option->features : [];

        return array_values(array_filter([
            self::row('Execution model', $option->execution_model),
            self::row('Spread type', $option->spread_type ? ucfirst($option->spread_type) : null),
            self::row('Swap-free / Islamic account', self::boolLabel($option->swap_free)),
            self::row('Expert advisors (EAs)', self::boolLabel($option->ea_allowed)),
            self::row('Hedging', self::boolLabel($option->hedging_allowed)),
            self::row('VPS eligible', self::boolLabel($option->vps_eligible)),
            self::row('Bonus eligible', self::boolLabel($option->bonus_eligibility)),
            self::row('Professional features', self::boolLabel($option->access_to_pro_features)),
            self::row('Min trade size', $option->min_trade_size),
            self::row('Max trade size', $option->max_trade_size),
            self::row('Margin call', $option->margin_call_level !== null ? $option->margin_call_level . '%' : null),
            self::row('Stop out level', $option->stop_out_level !== null ? $option->stop_out_level . '%' : null),
            self::row('Max open positions', $option->max_open_positions),
            self::row('Max daily volume', $option->maximum_daily_trade_volume),
            self::row('Features', $features ? implode(', ', $features) : null),
            self::row('Overview', $option->description, true),
            self::row('Special conditions', $option->special_conditions, true),
            self::row('Exclusive offers', $option->exclusive_offers, true),
        ]));
    }

    /** @param array<int, array{label: string, value: string, html?: bool}> $preview */
    /** @param array<int, array{label: string, value: string, html?: bool}> $more */
    private static function section(
        string $id,
        string $title,
        string $description,
        ?string $score,
        array $preview,
        array $more = []
    ): array {
        $preview = array_values($preview);
        $more = array_values($more);

        if (empty($preview) && empty($more)) {
            return [];
        }

        if (count($preview) > 6) {
            $more = array_merge(array_slice($preview, 6), $more);
            $preview = array_slice($preview, 0, 6);
        }

        return compact('id', 'title', 'description', 'score', 'preview', 'more');
    }

    /** @return array{label: string, value: string, html?: bool}|null */
    private static function row(string $label, mixed $value, bool $allowHtml = false): ?array
    {
        if ($value === null || $value === '' || $value === []) {
            return null;
        }

        if (is_bool($value)) {
            $value = self::boolLabel($value);
        }

        if (is_array($value)) {
            $value = implode(', ', $value);
        }

        $string = trim(strip_tags((string) $value) === '' && $allowHtml ? (string) $value : (string) $value);
        if ($string === '' || $string === '—') {
            return null;
        }

        return [
            'label' => $label,
            'value' => (string) $value,
            'html' => $allowHtml && strip_tags((string) $value) !== (string) $value,
        ];
    }

    private static function boolLabel(?bool $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return $value ? 'Yes' : 'No';
    }

    private static function availabilityLabel(?bool $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return $value ? 'Available' : 'Not available';
    }

    private static function money(mixed $amount): ?string
    {
        if ($amount === null || $amount === '') {
            return null;
        }

        return '$' . number_format((float) $amount, 0);
    }

    private static function plain(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_array($value)) {
            return implode(', ', $value);
        }

        return (string) $value;
    }

    private static function score(array $scores, string $key): ?string
    {
        if (! isset($scores[$key]) || $scores[$key] === '' || $scores[$key] === null) {
            return null;
        }

        return number_format(min(10, max(0, (float) $scores[$key])), 1);
    }

    private static function feeLevelLabel(?string $level): ?string
    {
        if ($level === null || $level === '') {
            return null;
        }

        return match (strtolower($level)) {
            'low' => 'Low',
            'medium' => 'Average',
            'high' => 'High',
            default => ucfirst($level),
        };
    }

    private static function fundSecuritySummary(Broker $broker): ?string
    {
        $parts = [];

        if ($broker->segregation_of_funds) {
            $parts[] = 'Segregated client funds';
        }

        if ($broker->investor_protection) {
            $parts[] = 'Investor protection';
        }

        if ($broker->negative_balance_protection) {
            $parts[] = 'Negative balance protection';
        }

        return $parts ? implode(' · ', $parts) : null;
    }

    private static function companyBackground(Broker $broker): ?string
    {
        $parts = array_filter([
            $broker->country ? 'Based in ' . strip_tags($broker->country) : null,
            $broker->year_founded ? 'Founded in ' . $broker->year_founded : null,
            self::formatCapitalization($broker->capitalization),
        ]);

        return $parts ? implode('. ', $parts) . '.' : null;
    }

    private static function formatCapitalization(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $numeric = (float) preg_replace('/[^\d.]/', '', (string) $value);

        if ($numeric >= 1_000_000_000) {
            return 'Capitalization approx. $' . rtrim(rtrim(number_format($numeric / 1_000_000_000, 1), '0'), '.') . 'B';
        }

        if ($numeric >= 1_000_000) {
            return 'Capitalization approx. $' . rtrim(rtrim(number_format($numeric / 1_000_000, 0), '0'), '.') . 'M';
        }

        return strip_tags((string) $value);
    }
}
