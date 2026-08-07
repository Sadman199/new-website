<?php

namespace Database\Seeders;

use App\Models\AccountOption;
use App\Models\Author;
use App\Models\Broker;
use App\Models\Faq;
use App\Models\ForexBonus;
use App\Models\Language;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BrokerReviewDemoSeeder extends Seeder
{
    protected int $writerId;

    protected int $editorId;

    protected int $factCheckerId;

    protected int $languageId;

    public function run(): void
    {
        $this->command?->info('Clearing broker review demo data…');

        Schema::disableForeignKeyConstraints();
        Review::truncate();
        Faq::truncate();
        AccountOption::truncate();
        ForexBonus::truncate();
        Broker::truncate();
        Schema::enableForeignKeyConstraints();

        $this->seedAuthors();
        $this->languageId = Language::firstOrCreate(
            ['short_name' => 'en'],
            ['name' => 'English', 'is_default' => 'Yes']
        )->id;

        foreach ($this->brokerDefinitions() as $index => $definition) {
            $this->seedBroker($definition, $index);
        }

        $this->command?->info('Seeded 10 brokers with account options, FAQs, reviews, and editorial credits.');
    }

    protected function seedAuthors(): void
    {
        $writer = Author::updateOrCreate(
            ['email' => 'sarah.mitchell@brokerscourt.com'],
            [
                'name' => 'Sarah Mitchell',
                'password' => Hash::make('password'),
                'photo' => '',
                'token' => Str::random(32),
                'can_write' => true,
                'can_edit' => false,
                'can_fact_check' => false,
                'bio' => 'Senior forex analyst with 12+ years covering global brokers, regulation, and trading costs.',
                'twitter_url' => 'https://twitter.com/brokerscourt',
                'linkedin_url' => 'https://www.linkedin.com/company/brokerscourt',
            ]
        );

        $editor = Author::updateOrCreate(
            ['email' => 'james.chen@brokerscourt.com'],
            [
                'name' => 'James Chen',
                'password' => Hash::make('password'),
                'photo' => '',
                'token' => Str::random(32),
                'can_write' => false,
                'can_edit' => true,
                'can_fact_check' => false,
                'bio' => 'Markets editor focused on platform usability, account structures, and broker comparisons.',
                'linkedin_url' => 'https://www.linkedin.com/company/brokerscourt',
            ]
        );

        $factChecker = Author::updateOrCreate(
            ['email' => 'elena.vasquez@brokerscourt.com'],
            [
                'name' => 'Dr. Elena Vasquez',
                'password' => Hash::make('password'),
                'photo' => '',
                'token' => Str::random(32),
                'can_write' => false,
                'can_edit' => false,
                'can_fact_check' => true,
                'bio' => 'Compliance researcher who verifies regulatory claims, fee tables, and safety disclosures.',
                'linkedin_url' => 'https://www.linkedin.com/company/brokerscourt',
                'twitter_url' => 'https://twitter.com/brokerscourt',
            ]
        );

        $this->writerId = $writer->id;
        $this->editorId = $editor->id;
        $this->factCheckerId = $factChecker->id;
    }

    protected function seedBroker(array $def, int $index): void
    {
        $broker = Broker::create(array_merge($this->baseBrokerPayload($def, $index), [
            'written_by_author_id' => $this->writerId,
            'edited_by_author_id' => $this->editorId,
            'fact_checked_by_author_id' => $this->factCheckerId,
        ]));

        foreach ($def['accounts'] as $sort => $account) {
            AccountOption::create(array_merge([
                'broker_id' => $broker->id,
                'sort_order' => $sort + 1,
                'is_active' => true,
                'account_currency' => 'USD',
                'min_trade_size' => 0.01,
                'max_trade_size' => 100,
                'margin_call_level' => 60,
                'stop_out_level' => 30,
                'max_open_positions' => 200,
                'ea_allowed' => true,
                'hedging_allowed' => true,
            ], $account));
        }

        foreach ($def['faqs'] as $faq) {
            Faq::create([
                'broker_id' => $broker->id,
                'language_id' => $this->languageId,
                'faq_title' => $faq['title'],
                'faq_detail' => $faq['detail'],
            ]);
        }

        foreach ($def['reviews'] as $review) {
            Review::create([
                'broker_id' => $broker->id,
                'name' => $review['name'],
                'email' => Str::slug($review['name']) . '@example.com',
                'country' => $review['country'],
                'description' => $review['text'],
                'rating' => $review['rating'],
                'status' => 1,
            ]);
        }

        if (! empty($def['bonus'])) {
            ForexBonus::create(array_merge([
                'broker_id' => $broker->id,
                'slug' => Str::slug($def['name'] . '-welcome-bonus'),
                'publish_date' => now()->subDays(10)->toDateString(),
                'author_name' => 'Sarah Mitchell',
                'promo_type' => 'Forex Deposit Bonus',
                'feature_image' => 'uploads/forex_bonuses/demo-bonus.jpg',
                'participate' => 'Register and verify your account',
                'how_to_participate' => 'Open a live account and make a qualifying deposit',
                'details' => '<li>Fast crediting</li><li>Standard terms apply</li>',
                'general_terms' => 'Bonus subject to broker terms and withdrawal conditions.',
                'promotion_status' => 'ongoing',
                'written_by_author_id' => $this->writerId,
                'edited_by_author_id' => $this->editorId,
                'fact_checked_by_author_id' => $this->factCheckerId,
            ], $def['bonus']));
        }
    }

    protected function baseBrokerPayload(array $def, int $index): array
    {
        $rating = $def['rating'];
        $scores = $def['category_scores'];

        return [
            'name' => $def['name'],
            'slug' => $def['slug'],
            'title' => $def['name'] . ' Review ' . date('Y'),
            'url' => $def['url'],
            'country' => $def['country'],
            'year_founded' => $def['year_founded'],
            'rating' => $rating,
            'trust_score' => (int) round($rating * 18),
            'regulatory_tier' => $def['regulatory_tier'],
            'short_description' => $def['short_description'],
            'description' => $def['description'],
            'pros' => $def['pros'],
            'cons' => $def['cons'],
            'verdict' => $def['verdict'],
            'minimum_deposit' => $def['minimum_deposit'],
            'spreads' => $def['spreads'],
            'leverage' => $def['leverage'],
            'commission' => $def['commission'],
            'fee_level' => $def['fee_level'],
            'regulation' => $def['regulation'],
            'regulated_jurisdictions' => $def['regulated_jurisdictions'],
            'regulatory_licenses' => $def['regulatory_licenses'],
            'platforms' => $def['platforms'],
            'markets' => $def['markets'],
            'instrument_count' => $def['instrument_count'],
            'account_types' => collect($def['accounts'])->pluck('account_type')->all(),
            'category_scores' => $scores,
            'languages' => 'English, Spanish, Arabic, Chinese',
            'pricing' => $def['pricing'],
            'deposit_methods' => 'Bank wire, Credit/Debit card, Skrill, Neteller, Crypto',
            'withdrawal_method' => 'Bank wire, E-wallets, Card',
            'withdrawal_fee' => $def['withdrawal_fee'],
            'payment_methods' => 'Visa, Mastercard, Skrill, Neteller, Bank transfer',
            'customer_support' => '24/7 Live chat, Email, Phone',
            'mobile_trading' => 'iOS & Android apps',
            'web_trader' => 'Browser-based platform',
            'demo_account_available' => true,
            'demo_link' => $def['url'] . '/demo',
            'demo_duration' => 'Unlimited',
            'open_live' => $def['url'] . '/register',
            'visit_site' => $def['url'],
            'investor_protection' => true,
            'segregation_of_funds' => true,
            'negative_balance_protection' => true,
            'social_trading' => $index % 2 === 0,
            'economic_calendar' => true,
            'vps_hosting' => $index % 3 === 0,
            'account_managers' => $index < 3,
            'news_and_analysis' => 'Daily market commentary and economic calendar integration.',
            'research_tools' => 'Technical indicators, sentiment data, and economic calendar.',
            'educational_resources' => 'Webinars, video tutorials, and beginner guides.',
            'top_feature' => $def['top_feature'],
            'capitalization' => $def['capitalization'],
            'insurance' => 'Client funds held in segregated accounts with tier-1 banking partners.',
            'featured_broker' => $index < 3,
            'meta_title' => $def['name'] . ' Review — Fees, Regulation & Verdict',
            'meta_description' => Str::limit(strip_tags($def['short_description']), 155),
            'meta_keyword' => $def['name'] . ', forex broker review, regulation, spreads',
        ];
    }

    /** @return array<int, array<string, mixed>> */
    protected function brokerDefinitions(): array
    {
        $reviewPool = [
            ['name' => 'Michael R.', 'country' => 'United Kingdom', 'rating' => 5, 'text' => 'Execution is fast and spreads stay tight during London session. Withdrawals reached my bank within 24 hours.'],
            ['name' => 'Aisha K.', 'country' => 'UAE', 'rating' => 4, 'text' => 'Solid platform and good swap-free options. Customer support resolved my verification issue quickly.'],
            ['name' => 'Carlos M.', 'country' => 'Brazil', 'rating' => 4, 'text' => 'Competitive pricing on majors and stable MT5 performance. Would like more educational content in Portuguese.'],
            ['name' => 'Priya S.', 'country' => 'India', 'rating' => 3, 'text' => 'Deposits are instant but weekend support responses can be slow. Overall acceptable for scalping.'],
            ['name' => 'Thomas W.', 'country' => 'Germany', 'rating' => 5, 'text' => 'Transparent fee structure and reliable regulation documentation. One of the better brokers I have tested live.'],
        ];

        $brokers = [
            ['name' => 'Exness', 'slug' => 'exness', 'url' => 'https://www.exness.com', 'country' => 'Cyprus', 'year_founded' => 2008, 'rating' => 8.4, 'regulatory_tier' => 2, 'minimum_deposit' => 10, 'spreads' => 'From 0.0 pips', 'leverage' => '1:2000', 'commission' => 'From $0/lot', 'fee_level' => 'low', 'withdrawal_fee' => 'Free', 'pricing' => 'Raw spreads from 0.0 pips on professional accounts', 'capitalization' => 500000000, 'regulation' => ['CySEC', 'FCA', 'FSCA'], 'regulated_jurisdictions' => 'EU, UK, South Africa', 'regulatory_licenses' => 'CySEC 178/12, FCA 730729', 'platforms' => ['MetaTrader 4', 'MetaTrader 5', 'Exness Terminal'], 'markets' => ['forex', 'crypto', 'indices', 'commodities'], 'instrument_count' => 200, 'top_feature' => 'Instant withdrawals and flexible leverage', 'short_description' => 'Exness is a multi-regulated broker known for tight spreads, fast withdrawals, and flexible account types for active traders.', 'category_scores' => ['fees' => 8.8, 'safety' => 8.2, 'platforms' => 8.5, 'deposit_withdrawal' => 9.2, 'customer_support' => 7.8, 'education' => 7.0, 'research' => 7.2, 'account_opening' => 8.9, 'products' => 8.4]],
            ['name' => 'IC Markets', 'slug' => 'ic-markets', 'url' => 'https://www.icmarkets.com', 'country' => 'Australia', 'year_founded' => 2007, 'rating' => 8.7, 'regulatory_tier' => 1, 'minimum_deposit' => 200, 'spreads' => 'From 0.0 pips', 'leverage' => '1:500', 'commission' => '$3.50/lot per side', 'fee_level' => 'low', 'withdrawal_fee' => 'Free', 'pricing' => 'Raw spreads + commission ECN model', 'capitalization' => 300000000, 'regulation' => ['ASIC', 'CySEC'], 'regulated_jurisdictions' => 'Australia, EU', 'regulatory_licenses' => 'ASIC 335692, CySEC 362/18', 'platforms' => ['MetaTrader 4', 'MetaTrader 5', 'cTrader'], 'markets' => ['forex', 'indices', 'commodities', 'crypto'], 'instrument_count' => 225, 'top_feature' => 'Institutional-grade ECN execution', 'short_description' => 'IC Markets targets serious traders with raw spreads, deep liquidity, and cTrader/MT support.', 'category_scores' => ['fees' => 9.1, 'safety' => 8.8, 'platforms' => 9.0, 'deposit_withdrawal' => 8.5, 'customer_support' => 8.0, 'education' => 7.5, 'research' => 7.8, 'account_opening' => 8.2, 'products' => 8.6]],
            ['name' => 'Pepperstone', 'slug' => 'pepperstone', 'url' => 'https://www.pepperstone.com', 'country' => 'Australia', 'year_founded' => 2010, 'rating' => 8.5, 'regulatory_tier' => 1, 'minimum_deposit' => 0, 'spreads' => 'From 0.0 pips', 'leverage' => '1:500', 'commission' => '$3.50/lot', 'fee_level' => 'low', 'withdrawal_fee' => 'Free', 'pricing' => 'Razor raw spread accounts available', 'capitalization' => 250000000, 'regulation' => ['ASIC', 'FCA', 'CySEC'], 'regulated_jurisdictions' => 'Australia, UK, EU', 'regulatory_licenses' => 'ASIC 414530, FCA 684312', 'platforms' => ['MetaTrader 4', 'MetaTrader 5', 'cTrader', 'TradingView'], 'markets' => ['forex', 'indices', 'commodities', 'crypto'], 'instrument_count' => 1200, 'top_feature' => 'TradingView integration', 'short_description' => 'Pepperstone combines multi-regulation with modern platform choice including TradingView connectivity.', 'category_scores' => ['fees' => 8.9, 'safety' => 8.7, 'platforms' => 9.1, 'deposit_withdrawal' => 8.6, 'customer_support' => 8.3, 'education' => 8.0, 'research' => 8.1, 'account_opening' => 8.8, 'products' => 8.9]],
            ['name' => 'XM', 'slug' => 'xm', 'url' => 'https://www.xm.com', 'country' => 'Cyprus', 'year_founded' => 2009, 'rating' => 8.1, 'regulatory_tier' => 2, 'minimum_deposit' => 5, 'spreads' => 'From 0.6 pips', 'leverage' => '1:1000', 'commission' => 'None on Standard', 'fee_level' => 'medium', 'withdrawal_fee' => 'Free', 'pricing' => 'Commission-free Standard and Zero accounts', 'capitalization' => 400000000, 'regulation' => ['CySEC', 'ASIC', 'IFSC'], 'regulated_jurisdictions' => 'EU, Australia, Belize', 'regulatory_licenses' => 'CySEC 120/10', 'platforms' => ['MetaTrader 4', 'MetaTrader 5'], 'markets' => ['forex', 'indices', 'commodities', 'stocks'], 'instrument_count' => 1000, 'top_feature' => 'Low minimum deposit and micro lots', 'short_description' => 'XM appeals to beginners with low entry barriers, generous education, and micro account support.', 'category_scores' => ['fees' => 7.8, 'safety' => 8.0, 'platforms' => 8.0, 'deposit_withdrawal' => 8.4, 'customer_support' => 8.5, 'education' => 8.8, 'research' => 7.6, 'account_opening' => 9.0, 'products' => 8.2]],
            ['name' => 'FBS', 'slug' => 'fbs', 'url' => 'https://fbs.com', 'country' => 'Belize', 'year_founded' => 2009, 'rating' => 7.6, 'regulatory_tier' => 3, 'minimum_deposit' => 5, 'spreads' => 'From 0.5 pips', 'leverage' => '1:3000', 'commission' => 'None on Standard', 'fee_level' => 'medium', 'withdrawal_fee' => 'Varies by method', 'pricing' => 'Standard fixed-spread and ECN options', 'capitalization' => 150000000, 'regulation' => ['CySEC', 'FSC'], 'regulated_jurisdictions' => 'EU, Belize', 'regulatory_licenses' => 'CySEC 331/17', 'platforms' => ['MetaTrader 4', 'MetaTrader 5', 'FBS Trader'], 'markets' => ['forex', 'metals', 'indices', 'crypto'], 'instrument_count' => 550, 'top_feature' => 'Promotions and cent accounts', 'short_description' => 'FBS offers accessible accounts, frequent promotions, and high leverage for retail traders.', 'category_scores' => ['fees' => 7.5, 'safety' => 7.2, 'platforms' => 7.8, 'deposit_withdrawal' => 8.0, 'customer_support' => 8.2, 'education' => 8.5, 'research' => 7.0, 'account_opening' => 8.7, 'products' => 7.6]],
            ['name' => 'AvaTrade', 'slug' => 'avatrade', 'url' => 'https://www.avatrade.com', 'country' => 'Ireland', 'year_founded' => 2006, 'rating' => 8.0, 'regulatory_tier' => 1, 'minimum_deposit' => 100, 'spreads' => 'From 0.9 pips', 'leverage' => '1:400', 'commission' => 'Built into spread', 'fee_level' => 'medium', 'withdrawal_fee' => 'Free', 'pricing' => 'Fixed spread retail pricing', 'capitalization' => 600000000, 'regulation' => ['CBI', 'ASIC', 'FSCA'], 'regulated_jurisdictions' => 'Ireland, Australia, South Africa', 'regulatory_licenses' => 'CBI C53877', 'platforms' => ['MetaTrader 4', 'MetaTrader 5', 'AvaTradeGO'], 'markets' => ['forex', 'stocks', 'indices', 'crypto'], 'instrument_count' => 1250, 'top_feature' => 'Multi-asset CFD coverage', 'short_description' => 'AvaTrade is a regulated multi-asset broker with strong mobile trading and copy trading options.', 'category_scores' => ['fees' => 7.6, 'safety' => 8.5, 'platforms' => 8.2, 'deposit_withdrawal' => 8.0, 'customer_support' => 8.1, 'education' => 8.4, 'research' => 7.8, 'account_opening' => 8.0, 'products' => 8.8]],
            ['name' => 'FXTM', 'slug' => 'fxtm', 'url' => 'https://www.forextime.com', 'country' => 'Cyprus', 'year_founded' => 2011, 'rating' => 7.9, 'regulatory_tier' => 2, 'minimum_deposit' => 10, 'spreads' => 'From 1.0 pips', 'leverage' => '1:1000', 'commission' => 'From $2/lot', 'fee_level' => 'medium', 'withdrawal_fee' => 'Free', 'pricing' => 'Standard, Cent, and ECN accounts', 'capitalization' => 200000000, 'regulation' => ['CySEC', 'FSCA', 'FSC'], 'regulated_jurisdictions' => 'EU, South Africa, Mauritius', 'regulatory_licenses' => 'CySEC 185/12', 'platforms' => ['MetaTrader 4', 'MetaTrader 5'], 'markets' => ['forex', 'metals', 'indices', 'stocks'], 'instrument_count' => 250, 'top_feature' => 'Local presence in emerging markets', 'short_description' => 'FXTM focuses on emerging markets with localized support and flexible account types.', 'category_scores' => ['fees' => 7.7, 'safety' => 7.9, 'platforms' => 7.9, 'deposit_withdrawal' => 8.1, 'customer_support' => 8.6, 'education' => 8.7, 'research' => 7.5, 'account_opening' => 8.5, 'products' => 7.8]],
            ['name' => 'OANDA', 'slug' => 'oanda', 'url' => 'https://www.oanda.com', 'country' => 'United States', 'year_founded' => 1996, 'rating' => 8.3, 'regulatory_tier' => 1, 'minimum_deposit' => 0, 'spreads' => 'From 0.6 pips', 'leverage' => '1:50', 'commission' => 'Spread-only core pricing', 'fee_level' => 'medium', 'withdrawal_fee' => 'Free', 'pricing' => 'Transparent spread-based pricing', 'capitalization' => 800000000, 'regulation' => ['CFTC', 'FCA', 'ASIC'], 'regulated_jurisdictions' => 'US, UK, Australia', 'regulatory_licenses' => 'NFA 0325821, FCA 542574', 'platforms' => ['MetaTrader 4', 'OANDA Trade'], 'markets' => ['forex', 'indices', 'commodities'], 'instrument_count' => 70, 'top_feature' => 'Long track record and transparency', 'short_description' => 'OANDA is a veteran broker known for transparency, strong regulation, and reliable execution.', 'category_scores' => ['fees' => 7.9, 'safety' => 9.0, 'platforms' => 8.0, 'deposit_withdrawal' => 8.3, 'customer_support' => 8.0, 'education' => 8.2, 'research' => 8.0, 'account_opening' => 8.1, 'products' => 7.5]],
            ['name' => 'IG Markets', 'slug' => 'ig-markets', 'url' => 'https://www.ig.com', 'country' => 'United Kingdom', 'year_founded' => 1974, 'rating' => 8.8, 'regulatory_tier' => 1, 'minimum_deposit' => 250, 'spreads' => 'From 0.6 pips', 'leverage' => '1:200', 'commission' => 'Spread-based / share dealing fees', 'fee_level' => 'medium', 'withdrawal_fee' => 'Free', 'pricing' => 'Premium platform with broad market access', 'capitalization' => 3500000000, 'regulation' => ['FCA', 'ASIC', 'BaFin'], 'regulated_jurisdictions' => 'UK, Australia, Germany', 'regulatory_licenses' => 'FCA 195355', 'platforms' => ['IG Platform', 'MetaTrader 4', 'ProRealTime'], 'markets' => ['forex', 'stocks', 'indices', 'commodities', 'crypto'], 'instrument_count' => 17000, 'top_feature' => 'Massive instrument selection', 'short_description' => 'IG is a premium multi-asset broker with institutional-grade platforms and extensive market coverage.', 'category_scores' => ['fees' => 7.8, 'safety' => 9.2, 'platforms' => 9.3, 'deposit_withdrawal' => 8.4, 'customer_support' => 8.5, 'education' => 9.0, 'research' => 9.1, 'account_opening' => 7.8, 'products' => 9.5]],
            ['name' => 'Plus500', 'slug' => 'plus500', 'url' => 'https://www.plus500.com', 'country' => 'Israel', 'year_founded' => 2008, 'rating' => 7.8, 'regulatory_tier' => 1, 'minimum_deposit' => 100, 'spreads' => 'From 0.8 pips', 'leverage' => '1:300', 'commission' => 'No commission — spread only', 'fee_level' => 'medium', 'withdrawal_fee' => 'Free', 'pricing' => 'All-inclusive spread pricing', 'capitalization' => 1200000000, 'regulation' => ['FCA', 'CySEC', 'ASIC'], 'regulated_jurisdictions' => 'UK, EU, Australia', 'regulatory_licenses' => 'FCA 509909', 'platforms' => ['Plus500 WebTrader'], 'markets' => ['forex', 'indices', 'commodities', 'crypto', 'stocks'], 'instrument_count' => 2800, 'top_feature' => 'Simple intuitive proprietary platform', 'short_description' => 'Plus500 offers a streamlined CFD experience with an easy-to-use proprietary platform.', 'category_scores' => ['fees' => 7.4, 'safety' => 8.6, 'platforms' => 8.4, 'deposit_withdrawal' => 8.2, 'customer_support' => 7.5, 'education' => 6.8, 'research' => 6.5, 'account_opening' => 8.6, 'products' => 8.7]],
        ];

        $accountsTemplate = fn (string $name, float $minDep, float $spread, int $lev, string $exec = 'ecn') => [
            [
                'account_type' => 'Standard',
                'slug' => Str::slug($name . '-standard'),
                'min_deposit' => $minDep,
                'max_leverage' => $lev,
                'max_leverage_numeric' => $lev,
                'leverage_label' => '1:' . $lev,
                'spread_type' => 'variable',
                'spread_value' => $spread,
                'spread_from_pips' => $spread,
                'commission' => 0,
                'commission_label' => 'No commission',
                'execution_model' => $exec === 'ecn' ? 'stp' : 'market_maker',
                'swap_free' => true,
                'description' => 'Commission-free account suited for beginners and swing traders.',
            ],
            [
                'account_type' => 'Raw / ECN',
                'slug' => Str::slug($name . '-raw'),
                'min_deposit' => max($minDep, 200),
                'max_leverage' => $lev,
                'max_leverage_numeric' => $lev,
                'leverage_label' => '1:' . $lev,
                'spread_type' => 'variable',
                'spread_value' => max(0, $spread - 0.4),
                'spread_from_pips' => max(0, $spread - 0.4),
                'commission' => 3.5,
                'commission_per_lot' => 3.5,
                'commission_label' => '$3.50/lot per side',
                'execution_model' => 'ecn',
                'swap_free' => false,
                'bonus_eligibility' => true,
                'description' => 'Raw spreads with low-latency ECN execution for active traders.',
            ],
        ];

        $faqTemplate = fn (string $name) => [
            ['title' => 'Is ' . $name . ' regulated?', 'detail' => $name . ' holds licenses from multiple regulators. Always confirm which entity serves your country before opening an account.'],
            ['title' => 'What is the minimum deposit at ' . $name . '?', 'detail' => 'Minimum deposit depends on account type. Standard accounts typically start low; professional ECN accounts may require a higher balance.'],
            ['title' => 'How long do withdrawals take?', 'detail' => 'E-wallet withdrawals are often processed within 24 hours. Bank transfers may take 1–5 business days depending on your region and bank.'],
        ];

        return array_map(function (array $b) use ($reviewPool, $accountsTemplate, $faqTemplate) {
            $name = $b['name'];
            $b['pros'] = '<li>Strong regulatory footprint with multiple tier-1 and tier-2 licenses</li><li>Competitive trading costs on major currency pairs</li><li>Modern platforms with mobile and web support</li><li>Fast deposit processing across popular payment methods</li>';
            $b['cons'] = '<li>Product availability varies by region and entity</li><li>Advanced research tools may lag dedicated platform brokers</li><li>Leverage caps apply for retail clients in regulated jurisdictions</li>';
            $b['verdict'] = $name . ' is a capable choice for traders who prioritize regulation and platform stability. Compare account types carefully to match your strategy and fee sensitivity.';
            $b['description'] = '<p>Our ' . $name . ' review evaluates fees, safety, platforms, and real-world trading conditions based on verified data and hands-on testing.</p><p>We assess regulation, deposit and withdrawal reliability, execution quality, and the overall value proposition for both beginners and experienced traders.</p>';
            $b['accounts'] = $accountsTemplate($name, (float) $b['minimum_deposit'], (float) preg_replace('/[^0-9.]/', '', $b['spreads']) ?: 0.6, (int) preg_replace('/\D/', '', $b['leverage']) ?: 500);
            $b['faqs'] = $faqTemplate($name);
            $b['reviews'] = array_slice($reviewPool, 0, 4);
            $b['bonus'] = [
                'title' => $name . ' Welcome Deposit Bonus',
                'description' => 'Get a deposit bonus when you open a new live account with ' . $name . '.',
                'link' => $b['url'],
                'prize' => 'Up to 50% deposit bonus',
                'min_deposit' => $b['minimum_deposit'],
                'bonus_amount' => 500,
                'bonus_percentage' => 50,
                'expiry_date' => now()->addMonths(3)->toDateString(),
                'is_featured' => true,
            ];

            return $b;
        }, $brokers);
    }
}
