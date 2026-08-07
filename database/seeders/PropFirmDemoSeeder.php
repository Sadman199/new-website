<?php

namespace Database\Seeders;

use App\Models\PropFirm;
use App\Models\PropFirmAttribute;
use App\Models\PropFirmCategory;
use App\Models\PropFirmFaq;
use App\Models\PropFirmModuleSetting;
use App\Models\PropFirmProgram;
use App\Models\PropFirmReview;
use Database\Seeders\Support\PlaceholderImageFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PropFirmDemoSeeder extends Seeder
{
    /** @var array<string, PropFirmCategory> */
    protected array $categories = [];

    /** @var array<string, PropFirmAttribute> */
    protected array $attributes = [];

    public function run(): void
    {
        $this->command?->info('Seeding Prop Firms demo data…');

        PlaceholderImageFactory::ensureDirectories();

        $this->seedSettings();
        $this->seedCategories();
        $this->seedAttributes();
        $this->seedPropFirms();

        $this->command?->info('Done: ' . PropFirmCategory::count() . ' categories, '
            . PropFirmAttribute::count() . ' attributes, '
            . PropFirm::count() . ' prop firms.');
    }

    protected function seedSettings(): void
    {
        $settings = PropFirmModuleSetting::instance();
        $settings->setMany([
            'default_sort_order' => 'trust_score',
            'enable_reviews' => true,
            'enable_faqs' => true,
            'enable_programs' => true,
        ]);
    }

    protected function seedCategories(): void
    {
        foreach ([
            ['name' => 'Forex Prop Firms', 'slug' => 'forex-prop-firms', 'description' => 'Firms focused on FX and CFD evaluation programs.', 'sort_order' => 1],
            ['name' => 'Futures Prop Firms', 'slug' => 'futures-prop-firms', 'description' => 'US and global futures evaluation and funded accounts.', 'sort_order' => 2],
            ['name' => 'Crypto Prop Firms', 'slug' => 'crypto-prop-firms', 'description' => 'Crypto-native funding and digital asset programs.', 'sort_order' => 3],
            ['name' => 'Multi-Asset', 'slug' => 'multi-asset', 'description' => 'Firms offering forex, indices, commodities, and more.', 'sort_order' => 4],
        ] as $row) {
            $this->categories[$row['slug']] = PropFirmCategory::updateOrCreate(
                ['slug' => $row['slug']],
                array_merge($row, ['is_active' => true])
            );
        }
    }

    protected function seedAttributes(): void
    {
        $rows = [
            ['name' => 'Instant Funding', 'slug' => 'instant-funding', 'group' => 'Funding Type', 'sort_order' => 1],
            ['name' => 'One Step', 'slug' => 'one-step', 'group' => 'Funding Type', 'sort_order' => 2],
            ['name' => 'Two Step', 'slug' => 'two-step', 'group' => 'Funding Type', 'sort_order' => 3],
            ['name' => 'MT4', 'slug' => 'mt4', 'group' => 'Platform', 'sort_order' => 10],
            ['name' => 'MT5', 'slug' => 'mt5', 'group' => 'Platform', 'sort_order' => 11],
            ['name' => 'cTrader', 'slug' => 'ctrader', 'group' => 'Platform', 'sort_order' => 12],
            ['name' => 'DXtrade', 'slug' => 'dxtrade', 'group' => 'Platform', 'sort_order' => 13],
            ['name' => 'Crypto Payments', 'slug' => 'crypto-payments', 'group' => 'Features', 'sort_order' => 20],
            ['name' => 'Fast Payout', 'slug' => 'fast-payout', 'group' => 'Features', 'sort_order' => 21],
            ['name' => 'Weekend Holding', 'slug' => 'weekend-holding', 'group' => 'Features', 'sort_order' => 22],
            ['name' => 'News Trading', 'slug' => 'news-trading', 'group' => 'Features', 'sort_order' => 23],
            ['name' => 'EA Allowed', 'slug' => 'ea-allowed', 'group' => 'Features', 'sort_order' => 24],
            ['name' => 'Copy Trading', 'slug' => 'copy-trading', 'group' => 'Features', 'sort_order' => 25],
            ['name' => 'Beginner Friendly', 'slug' => 'beginner-friendly', 'group' => 'Features', 'sort_order' => 26],
        ];

        foreach ($rows as $row) {
            $this->attributes[$row['slug']] = PropFirmAttribute::updateOrCreate(
                ['slug' => $row['slug']],
                array_merge($row, ['is_active' => true])
            );
        }
    }

    protected function seedPropFirms(): void
    {
        foreach ($this->firmDefinitions() as $index => $definition) {
            $this->seedFirm($definition, $index);
        }
    }

    /** @return list<array<string, mixed>> */
    protected function firmDefinitions(): array
    {
        return [
            [
                'slug' => 'ftmo',
                'name' => 'FTMO',
                'category' => 'forex-prop-firms',
                'color' => '#2563eb',
                'founded_year' => 2015,
                'headquarters' => 'Prague, Czech Republic',
                'website' => 'https://ftmo.com',
                'max_funding' => '$200,000',
                'profit_split' => '80/20 up to 90/10',
                'min_fee' => 155,
                'max_fee' => 1080,
                'scaling_available' => true,
                'trust_score' => 9.2,
                'editor_rating' => 4.8,
                'user_rating' => 4.6,
                'overall_rating' => 4.7,
                'is_featured' => true,
                'is_verified' => true,
                'attribute_slugs' => ['two-step', 'mt4', 'mt5', 'ctrader', 'fast-payout', 'ea-allowed', 'beginner-friendly'],
                'programs' => [
                    ['name' => 'Challenge — Two Step', 'account_size' => '$10,000', 'entry_fee' => 155, 'profit_target' => '10% / 5%', 'daily_drawdown' => '5%', 'max_drawdown' => '10%', 'profit_split' => '80/20', 'min_trading_days' => 4, 'news_trading' => false, 'weekend_holding' => true, 'ea_allowed' => true, 'copy_trading' => false, 'hedging' => true, 'refund_available' => false],
                    ['name' => 'Challenge — Two Step', 'account_size' => '$100,000', 'entry_fee' => 540, 'profit_target' => '10% / 5%', 'daily_drawdown' => '5%', 'max_drawdown' => '10%', 'profit_split' => '80/20', 'min_trading_days' => 4, 'news_trading' => false, 'weekend_holding' => true, 'ea_allowed' => true, 'copy_trading' => false, 'hedging' => true, 'refund_available' => false],
                ],
                'reviews' => [
                    ['rating' => 4.5, 'title' => 'Reliable payouts and clear rules', 'content' => 'FTMO has been one of the most consistent prop firms in the industry. Evaluation rules are strict but transparent, and payout processing is reliable once you pass.', 'author' => 'Alex Rivera', 'status' => 'approved'],
                    ['rating' => 4.0, 'title' => 'Challenging but fair evaluation', 'content' => 'The two-step challenge requires discipline. Support responds quickly and the dashboard is easy to navigate.', 'author' => 'Maria Chen', 'status' => 'approved'],
                ],
                'faqs' => [
                    ['question' => 'Does FTMO allow news trading?', 'answer' => 'News trading is restricted during high-impact events on most account types. Check the current rulebook for exact windows.', 'sort_order' => 1],
                    ['question' => 'How long do payouts take?', 'answer' => 'First payout is typically processed within 8 business days after request approval.', 'sort_order' => 2],
                ],
            ],
            [
                'slug' => 'the-funded-trader',
                'name' => 'The Funded Trader',
                'category' => 'multi-asset',
                'color' => '#0f766e',
                'founded_year' => 2021,
                'headquarters' => 'United States',
                'website' => 'https://thefundedtraderprogram.com',
                'max_funding' => '$600,000',
                'profit_split' => '80/20 up to 95/5',
                'min_fee' => 97,
                'max_fee' => 499,
                'scaling_available' => true,
                'trust_score' => 8.5,
                'editor_rating' => 4.5,
                'user_rating' => 4.3,
                'overall_rating' => 4.4,
                'is_featured' => true,
                'is_verified' => true,
                'attribute_slugs' => ['one-step', 'two-step', 'mt5', 'crypto-payments', 'fast-payout', 'news-trading', 'copy-trading'],
                'programs' => [
                    ['name' => 'Royal — One Step', 'account_size' => '$50,000', 'entry_fee' => 289, 'profit_target' => '10%', 'daily_drawdown' => '4%', 'max_drawdown' => '6%', 'profit_split' => '80/20', 'min_trading_days' => 0, 'news_trading' => true, 'weekend_holding' => true, 'ea_allowed' => true, 'copy_trading' => true, 'hedging' => true, 'refund_available' => true],
                    ['name' => 'Standard — Two Step', 'account_size' => '$100,000', 'entry_fee' => 499, 'profit_target' => '8% / 5%', 'daily_drawdown' => '5%', 'max_drawdown' => '10%', 'profit_split' => '80/20', 'min_trading_days' => 3, 'news_trading' => true, 'weekend_holding' => false, 'ea_allowed' => true, 'copy_trading' => false, 'hedging' => true, 'refund_available' => false],
                ],
                'reviews' => [
                    ['rating' => 4.2, 'title' => 'Flexible program options', 'content' => 'TFT offers multiple challenge types including one-step and instant-style paths. Good for traders who want variety.', 'author' => 'James Okonkwo', 'status' => 'approved'],
                ],
                'faqs' => [
                    ['question' => 'Can I hold trades over the weekend?', 'answer' => 'Weekend holding depends on the program type. Royal and Knight programs generally allow it; verify your specific plan.', 'sort_order' => 1],
                ],
            ],
            [
                'slug' => 'apex-trader-funding',
                'name' => 'Apex Trader Funding',
                'category' => 'futures-prop-firms',
                'color' => '#b45309',
                'founded_year' => 2021,
                'headquarters' => 'Austin, Texas, USA',
                'website' => 'https://apextraderfunding.com',
                'max_funding' => '$300,000',
                'profit_split' => '90/10',
                'min_fee' => 147,
                'max_fee' => 657,
                'scaling_available' => true,
                'trust_score' => 8.8,
                'editor_rating' => 4.6,
                'user_rating' => 4.4,
                'overall_rating' => 4.5,
                'is_featured' => false,
                'is_verified' => true,
                'attribute_slugs' => ['one-step', 'fast-payout', 'news-trading', 'beginner-friendly'],
                'programs' => [
                    ['name' => 'Full Size — One Step', 'account_size' => '$50,000', 'entry_fee' => 167, 'profit_target' => '$3,000', 'daily_drawdown' => 'Trailing', 'max_drawdown' => '$2,500', 'profit_split' => '90/10', 'min_trading_days' => 7, 'news_trading' => true, 'weekend_holding' => false, 'ea_allowed' => false, 'copy_trading' => false, 'hedging' => false, 'refund_available' => false],
                    ['name' => 'Swing', 'account_size' => '$100,000', 'entry_fee' => 657, 'profit_target' => '$6,000', 'daily_drawdown' => 'None', 'max_drawdown' => '$3,500', 'profit_split' => '90/10', 'min_trading_days' => 0, 'news_trading' => true, 'weekend_holding' => true, 'ea_allowed' => false, 'copy_trading' => false, 'hedging' => false, 'refund_available' => false],
                ],
                'reviews' => [
                    ['rating' => 4.7, 'title' => 'Best for futures traders', 'content' => 'Apex is a go-to for US futures. The swing account is popular for part-time traders who need overnight holds.', 'author' => 'Daniel Brooks', 'status' => 'approved'],
                    ['rating' => 3.8, 'title' => 'Trailing drawdown takes getting used to', 'content' => 'Rules are futures-specific. Read the trailing threshold carefully before starting evaluation.', 'author' => 'Lisa Park', 'status' => 'pending'],
                ],
                'faqs' => [
                    ['question' => 'What platforms does Apex support?', 'answer' => 'Apex primarily supports Rithmic and Tradovate for futures evaluation and funded accounts.', 'sort_order' => 1],
                    ['question' => 'Is there a minimum trading day requirement?', 'answer' => 'Most plans require at least 7 trading days before the first payout request.', 'sort_order' => 2],
                ],
            ],
            [
                'slug' => 'funding-pips',
                'name' => 'Funding Pips',
                'category' => 'forex-prop-firms',
                'color' => '#7c3aed',
                'founded_year' => 2022,
                'headquarters' => 'Dubai, UAE',
                'website' => 'https://fundingpips.com',
                'max_funding' => '$300,000',
                'profit_split' => '80/20 up to 100/0',
                'min_fee' => 59,
                'max_fee' => 499,
                'scaling_available' => true,
                'trust_score' => 8.0,
                'editor_rating' => 4.2,
                'user_rating' => 4.0,
                'overall_rating' => 4.1,
                'is_featured' => false,
                'is_verified' => false,
                'attribute_slugs' => ['instant-funding', 'one-step', 'two-step', 'mt5', 'ctrader', 'crypto-payments', 'fast-payout', 'ea-allowed'],
                'programs' => [
                    ['name' => 'One Step', 'account_size' => '$25,000', 'entry_fee' => 199, 'profit_target' => '8%', 'daily_drawdown' => '4%', 'max_drawdown' => '6%', 'profit_split' => '80/20', 'min_trading_days' => 0, 'news_trading' => true, 'weekend_holding' => true, 'ea_allowed' => true, 'copy_trading' => true, 'hedging' => true, 'refund_available' => true],
                    ['name' => 'Instant Funding', 'account_size' => '$10,000', 'entry_fee' => 499, 'profit_target' => 'None', 'daily_drawdown' => '3%', 'max_drawdown' => '5%', 'profit_split' => '80/20', 'min_trading_days' => 0, 'news_trading' => true, 'weekend_holding' => true, 'ea_allowed' => true, 'copy_trading' => false, 'hedging' => true, 'refund_available' => false],
                ],
                'reviews' => [
                    ['rating' => 4.0, 'title' => 'Competitive pricing', 'content' => 'Lower entry fees compared to many competitors. Instant funding tier is attractive for experienced traders.', 'author' => 'Omar Hassan', 'status' => 'approved'],
                ],
                'faqs' => [
                    ['question' => 'Do you accept crypto payments?', 'answer' => 'Yes, Funding Pips accepts several cryptocurrency payment methods at checkout.', 'sort_order' => 1],
                ],
            ],
            [
                'slug' => 'e8-markets',
                'name' => 'E8 Markets',
                'category' => 'multi-asset',
                'color' => '#dc2626',
                'founded_year' => 2021,
                'headquarters' => 'United States',
                'website' => 'https://e8markets.com',
                'max_funding' => '$1,000,000',
                'profit_split' => '80/20',
                'min_fee' => 33,
                'max_fee' => 588,
                'scaling_available' => true,
                'trust_score' => 7.8,
                'editor_rating' => 4.0,
                'user_rating' => 3.9,
                'overall_rating' => 4.0,
                'is_featured' => false,
                'is_verified' => true,
                'attribute_slugs' => ['one-step', 'two-step', 'mt5', 'dxtrade', 'ea-allowed', 'copy-trading', 'beginner-friendly'],
                'programs' => [
                    ['name' => 'E8 One', 'account_size' => '$25,000', 'entry_fee' => 138, 'profit_target' => '8%', 'daily_drawdown' => '5%', 'max_drawdown' => '8%', 'profit_split' => '80/20', 'min_trading_days' => 0, 'news_trading' => false, 'weekend_holding' => false, 'ea_allowed' => true, 'copy_trading' => true, 'hedging' => true, 'refund_available' => false],
                    ['name' => 'E8 Track', 'account_size' => '$100,000', 'entry_fee' => 588, 'profit_target' => '8% / 4%', 'daily_drawdown' => '5%', 'max_drawdown' => '8%', 'profit_split' => '80/20', 'min_trading_days' => 0, 'news_trading' => false, 'weekend_holding' => false, 'ea_allowed' => true, 'copy_trading' => false, 'hedging' => true, 'refund_available' => false],
                ],
                'reviews' => [
                    ['rating' => 3.9, 'title' => 'Solid scaling plan', 'content' => 'E8 offers aggressive scaling up to seven figures for consistent performers. Platform choice includes DXtrade.', 'author' => 'Chris Nguyen', 'status' => 'approved'],
                ],
                'faqs' => [
                    ['question' => 'Which platforms are available?', 'answer' => 'E8 supports MT5 and DXtrade depending on account type and region.', 'sort_order' => 1],
                    ['question' => 'Is copy trading allowed?', 'answer' => 'Copy trading is permitted on select one-step programs. Review current terms before enabling EAs or copiers.', 'sort_order' => 2],
                ],
            ],
        ];
    }

    /** @param array<string, mixed> $definition */
    protected function seedFirm(array $definition, int $index): void
    {
        $slug = $definition['slug'];
        $name = $definition['name'];

        $firm = PropFirm::updateOrCreate(
            ['slug' => $slug],
            [
                'prop_firm_category_id' => $this->categories[$definition['category']]->id,
                'name' => $name,
                'logo' => PlaceholderImageFactory::propFirmLogo($slug, $name, $definition['color']),
                'cover_image' => PlaceholderImageFactory::propFirmCover($slug, $name, $definition['color']),
                'description' => "{$name} is a popular proprietary trading firm offering evaluation programs and funded accounts for skilled traders. This demo entry was seeded for admin testing.",
                'website' => $definition['website'],
                'affiliate_link' => $definition['website'] . '/?ref=brokerscourt',
                'founded_year' => $definition['founded_year'],
                'headquarters' => $definition['headquarters'],
                'max_funding' => $definition['max_funding'],
                'profit_split' => $definition['profit_split'],
                'min_fee' => $definition['min_fee'],
                'max_fee' => $definition['max_fee'],
                'scaling_available' => $definition['scaling_available'],
                'trust_score' => $definition['trust_score'],
                'editor_rating' => $definition['editor_rating'],
                'user_rating' => $definition['user_rating'],
                'overall_rating' => $definition['overall_rating'],
                'meta_title' => "{$name} Review — Programs, Fees & Ratings | BrokersCourt",
                'meta_description' => "Compare {$name} funding programs, profit splits, drawdown rules, and trust scores.",
                'meta_keywords' => Str::lower($name) . ', prop firm, funded trader, evaluation',
                'og_image' => PlaceholderImageFactory::propFirmCover($slug, $name, $definition['color']),
                'is_featured' => $definition['is_featured'],
                'is_verified' => $definition['is_verified'],
                'is_active' => true,
                'sort_order' => $index + 1,
            ]
        );

        $attributeIds = collect($definition['attribute_slugs'])
            ->map(fn (string $attrSlug) => $this->attributes[$attrSlug]->id)
            ->all();
        $firm->attributes()->sync($attributeIds);

        $firm->programs()->delete();
        foreach ($definition['programs'] as $i => $program) {
            PropFirmProgram::create(array_merge($program, [
                'prop_firm_id' => $firm->id,
                'sort_order' => $i + 1,
                'is_active' => true,
            ]));
        }

        $firm->reviews()->delete();
        foreach ($definition['reviews'] as $review) {
            PropFirmReview::create(array_merge($review, ['prop_firm_id' => $firm->id]));
        }

        $firm->faqs()->delete();
        foreach ($definition['faqs'] as $faq) {
            PropFirmFaq::create(array_merge($faq, [
                'prop_firm_id' => $firm->id,
                'is_active' => true,
            ]));
        }
    }
}
