<?php

namespace Database\Seeders;

use App\Models\AccountOption;
use App\Models\Admin;
use App\Models\Author;
use App\Models\Broker;
use App\Models\Category;
use App\Models\Faq;
use App\Models\ForexBonus;
use App\Models\Language;
use App\Models\Post;
use App\Models\Review;
use App\Models\SubCategory;
use App\Models\Tag;
use Database\Seeders\Support\PlaceholderImageFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    protected int $languageId;

    protected int $adminId;

    /** @var array<int, Author> */
    protected array $authors = [];

    /** @var array<int, Broker> */
    protected array $brokers = [];

    /** @var array<int, SubCategory> */
    protected array $subCategories = [];

    protected int $writerId;

    protected int $editorId;

    protected int $factCheckerId;

    public function run(): void
    {
        $this->command?->info('Seeding demo data for BrokersCourt…');

        $this->clearDemoTables();
        PlaceholderImageFactory::ensureDirectories();

        $this->seedLanguageAndAdmin();
        $this->seedAuthors();
        $this->seedBlogContent();
        $this->seedBrokers();
        $this->seedForexBonuses();

        $this->command?->info('Done: 20 brokers, 20 categories, 20 subcategories, 20 posts, 20 bonuses, 5 authors.');
    }

    protected function clearDemoTables(): void
    {
        Schema::disableForeignKeyConstraints();

        Tag::truncate();
        Post::truncate();
        SubCategory::truncate();
        Category::truncate();
        Review::truncate();
        Faq::truncate();
        AccountOption::truncate();
        ForexBonus::truncate();
        Broker::truncate();
        Author::truncate();

        Schema::enableForeignKeyConstraints();
    }

    protected function seedLanguageAndAdmin(): void
    {
        $this->languageId = Language::firstOrCreate(
            ['short_name' => 'en'],
            ['name' => 'English', 'is_default' => 'Yes']
        )->id;

        $admin = Admin::firstOrCreate(
            ['email' => 'admin@brokerscourt.com'],
            [
                'name' => 'BrokersCourt Admin',
                'password' => Hash::make('Admin@Brokers2026'),
                'photo' => 'default-admin.png',
                'token' => '',
            ]
        );

        $this->adminId = $admin->id;
    }

    protected function seedAuthors(): void
    {
        $definitions = [
            [
                'name' => 'Sarah Mitchell',
                'email' => 'sarah.mitchell@brokerscourt.com',
                'can_write' => true,
                'can_edit' => false,
                'can_fact_check' => false,
                'bio' => 'Senior forex analyst with 12+ years covering global brokers, regulation, and trading costs.',
                'color' => '#2563eb',
            ],
            [
                'name' => 'James Chen',
                'email' => 'james.chen@brokerscourt.com',
                'can_write' => false,
                'can_edit' => true,
                'can_fact_check' => false,
                'bio' => 'Markets editor focused on platform usability, account structures, and broker comparisons.',
                'color' => '#059669',
            ],
            [
                'name' => 'Dr. Elena Vasquez',
                'email' => 'elena.vasquez@brokerscourt.com',
                'can_write' => false,
                'can_edit' => false,
                'can_fact_check' => true,
                'bio' => 'Compliance researcher who verifies regulatory claims, fee tables, and safety disclosures.',
                'color' => '#7c3aed',
            ],
            [
                'name' => 'Marcus Okonkwo',
                'email' => 'marcus.okonkwo@brokerscourt.com',
                'can_write' => true,
                'can_edit' => true,
                'can_fact_check' => false,
                'bio' => 'Former institutional trader writing about execution quality, liquidity, and advanced strategies.',
                'color' => '#dc2626',
            ],
            [
                'name' => 'Priya Sharma',
                'email' => 'priya.sharma@brokerscourt.com',
                'can_write' => true,
                'can_edit' => false,
                'can_fact_check' => true,
                'bio' => 'Asia-Pacific markets correspondent covering copy trading, crypto CFDs, and regional regulation.',
                'color' => '#d97706',
            ],
        ];

        foreach ($definitions as $def) {
            $slug = Str::slug($def['name']);
            $this->authors[] = Author::create([
                'name' => $def['name'],
                'email' => $def['email'],
                'password' => Hash::make('password'),
                'photo' => PlaceholderImageFactory::authorPhoto($slug, $def['name']),
                'token' => Str::random(32),
                'can_write' => $def['can_write'],
                'can_edit' => $def['can_edit'],
                'can_fact_check' => $def['can_fact_check'],
                'bio' => $def['bio'],
            ]);
        }

        $this->writerId = $this->authors[0]->id;
        $this->editorId = $this->authors[1]->id;
        $this->factCheckerId = $this->authors[2]->id;
    }

    protected function seedBlogContent(): void
    {
        /** @var array{categories: array, subcategories: array, posts: array} $blog */
        $blog = require database_path('seeders/data/demo_blog.php');

        foreach ($blog['categories'] as $index => $cat) {
            Category::create([
                'category_name' => $cat['name'],
                'slug' => $cat['slug'],
                'show_on_menu' => 'Show',
                'category_order' => (string) ($index + 1),
                'language_id' => $this->languageId,
            ]);
        }

        $categories = Category::orderBy('id')->get();

        foreach ($blog['subcategories'] as $index => $sub) {
            $this->subCategories[] = SubCategory::create([
                'sub_category_name' => $sub['name'],
                'slug' => $sub['slug'],
                'show_on_menu' => 'Show',
                'show_on_home' => $index < 6 ? 'Show' : 'Hide',
                'sub_category_order' => (string) ($index + 1),
                'category_id' => $categories[$index]->id,
                'language_id' => $this->languageId,
            ]);
        }

        foreach ($blog['posts'] as $index => $postDef) {
            $author = $this->authors[$index % count($this->authors)];
            $subCategory = $this->subCategories[$index];

            $body = $this->buildPostBody($postDef['title'], $postDef['excerpt']);

            $post = Post::create([
                'sub_category_id' => $subCategory->id,
                'post_title' => $postDef['title'],
                'slug' => $postDef['slug'],
                'post_detail' => $body,
                'post_photo' => PlaceholderImageFactory::postPhoto($postDef['slug'], Str::limit($postDef['title'], 24, '')),
                'visitors' => random_int(120, 4500),
                'author_id' => $author->id,
                'admin_id' => 0,
                'is_share' => 1,
                'is_comment' => 1,
                'language_id' => $this->languageId,
                'meta_title' => $postDef['title'] . ' | BrokersCourt',
                'meta_description' => $postDef['excerpt'],
                'meta_keywords' => implode(', ', $postDef['tags']),
                'author' => $author->name,
                'written_by_author_id' => $author->id,
                'edited_by_author_id' => $this->editorId,
                'fact_checked_by_author_id' => $this->factCheckerId,
            ]);

            foreach ($postDef['tags'] as $tagName) {
                Tag::create([
                    'post_id' => $post->id,
                    'tag_name' => $tagName,
                ]);
            }
        }
    }

    protected function buildPostBody(string $title, string $excerpt): string
    {
        return <<<HTML
<p><strong>{$excerpt}</strong></p>
<p>At BrokersCourt we publish independent research to help traders compare brokers, understand market drivers, and improve decision-making. This article is part of our editorial series designed for both beginners and experienced market participants.</p>
<h2>Key takeaways</h2>
<ul>
<li>Always verify regulation and fee schedules before opening a live account.</li>
<li>Use demo accounts to test execution quality during your preferred session.</li>
<li>Combine technical and fundamental context rather than relying on a single signal.</li>
<li>Apply strict risk management — most professionals risk 1% or less per trade.</li>
</ul>
<h2>What traders should watch next</h2>
<p>Monitor upcoming economic releases, central bank commentary, and liquidity conditions around major sessions. Spreads often widen ahead of high-impact news, so plan entries accordingly and avoid over-leveraging during volatile windows.</p>
<h2>Bottom line</h2>
<p>{$title} remains a relevant topic for active traders in the current environment. Compare brokers on BrokersCourt to find platforms that match your strategy, region, and budget — then validate everything on a demo account before committing capital.</p>
HTML;
    }

    protected function seedBrokers(): void
    {
        /** @var array<int, array<string, mixed>> $definitions */
        $definitions = require database_path('seeders/data/demo_brokers.php');

        $reviewPool = [
            ['name' => 'Michael R.', 'country' => 'United Kingdom', 'rating' => 5, 'text' => 'Execution is fast and spreads stay tight during London session. Withdrawals reached my bank within 24 hours.'],
            ['name' => 'Aisha K.', 'country' => 'UAE', 'rating' => 4, 'text' => 'Solid platform and good swap-free options. Customer support resolved my verification issue quickly.'],
            ['name' => 'Carlos M.', 'country' => 'Brazil', 'rating' => 4, 'text' => 'Competitive pricing on majors and stable MT5 performance. Would like more educational content in Portuguese.'],
            ['name' => 'Priya S.', 'country' => 'India', 'rating' => 3, 'text' => 'Deposits are instant but weekend support responses can be slow. Overall acceptable for scalping.'],
        ];

        foreach ($definitions as $index => $def) {
            $name = $def['name'];
            $slug = $def['slug'];
            $rating = $def['rating'];
            $scores = $this->categoryScoresFromRating($rating, $def['broker_categories'] ?? []);

            $broker = Broker::create([
                'name' => $name,
                'slug' => $slug,
                'title' => $name . ' Review ' . date('Y'),
                'url' => $def['url'],
                'country' => $def['country'],
                'year_founded' => $def['year_founded'],
                'rating' => $rating,
                'trust_score' => min(99, (int) round($rating * 20)),
                'regulatory_tier' => $def['regulatory_tier'],
                'short_description' => $name . ' is a well-known broker offering competitive trading conditions, multi-platform access, and broad market coverage for retail traders.',
                'description' => "<p>Our {$name} review evaluates fees, safety, platforms, and real-world trading conditions based on verified data and hands-on testing.</p><p>We assess regulation, deposit and withdrawal reliability, execution quality, and the overall value proposition for both beginners and experienced traders.</p>",
                'pros' => '<li>Strong regulatory footprint with multiple licenses</li><li>Competitive trading costs on major currency pairs</li><li>Modern platforms with mobile and web support</li><li>Fast deposit processing across popular payment methods</li>',
                'cons' => '<li>Product availability varies by region and entity</li><li>Advanced research tools may lag dedicated platform brokers</li><li>Leverage caps apply for retail clients in regulated jurisdictions</li>',
                'verdict' => "{$name} is a capable choice for traders who prioritize regulation and platform stability. Compare account types carefully to match your strategy and fee sensitivity.",
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
                'broker_categories' => $def['broker_categories'],
                'regions' => $def['regions'],
                'instrument_count' => $def['instrument_count'],
                'category_scores' => $scores,
                'account_types' => ['Standard', 'Raw / ECN'],
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
                'social_trading' => in_array('copytrading-brokers', $def['broker_categories'], true) || in_array('social-trading-brokers', $def['broker_categories'], true),
                'economic_calendar' => true,
                'vps_hosting' => $index % 3 === 0,
                'account_managers' => $index < 5,
                'news_and_analysis' => 'Daily market commentary and economic calendar integration.',
                'research_tools' => 'Technical indicators, sentiment data, and economic calendar.',
                'educational_resources' => 'Webinars, video tutorials, and beginner guides.',
                'top_feature' => $def['top_feature'],
                'capitalization' => $def['capitalization'],
                'insurance' => 'Client funds held in segregated accounts with tier-1 banking partners.',
                'featured_broker' => $index < 6,
                'top_broker' => $index < 10 ? $index + 1 : 0,
                'banner_image_1' => PlaceholderImageFactory::brokerBanner($slug, $name, 1),
                'banner_image_2' => PlaceholderImageFactory::brokerBanner($slug, $name, 2),
                'meta_title' => $name . ' Review — Fees, Regulation & Verdict',
                'meta_description' => Str::limit("Read our {$name} review covering spreads, regulation, platforms, and trading costs.", 155),
                'meta_keyword' => $name . ', forex broker review, regulation, spreads',
                'written_by_author_id' => $this->writerId,
                'edited_by_author_id' => $this->editorId,
                'fact_checked_by_author_id' => $this->factCheckerId,
            ]);

            $broker->logo = PlaceholderImageFactory::brokerLogo($slug, $name);
            $broker->save();

            $this->brokers[] = $broker;

            $lev = $this->parseLeverage($def['leverage']);
            $spread = (float) preg_replace('/[^0-9.]/', '', $def['spreads']) ?: 0.6;
            $minDep = (float) $def['minimum_deposit'];

            foreach ($this->accountTemplates($name, $slug, $minDep, $spread, $lev) as $sort => $account) {
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

            foreach ($this->faqTemplates($name) as $faq) {
                Faq::create([
                    'broker_id' => $broker->id,
                    'language_id' => $this->languageId,
                    'faq_title' => $faq['title'],
                    'faq_detail' => $faq['detail'],
                ]);
            }

            foreach ($reviewPool as $review) {
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
        }
    }

    protected function parseLeverage(string $leverage): int
    {
        if (preg_match('/1\s*:\s*(\d+)/i', $leverage, $matches)) {
            return (int) $matches[1];
        }

        return (int) preg_replace('/\D/', '', $leverage) ?: 500;
    }

    /** @return array<string, float> */
    protected function categoryScoresFromRating(float $rating, array $categories = []): array
    {
        $base = round($rating * 2, 1);

        $scores = [
            'fees' => min(10, $base + 0.3),
            'safety' => min(10, $base + 0.5),
            'platforms' => min(10, $base),
            'deposit_withdrawal' => min(10, $base + 0.2),
            'customer_support' => min(10, $base - 0.2),
            'education' => min(10, $base - 0.4),
            'research' => min(10, $base - 0.3),
            'account_opening' => min(10, $base + 0.4),
            'products' => min(10, $base + 0.1),
        ];

        if (in_array('scalping-brokers', $categories, true)) {
            $scores['scalping'] = min(5.0, round($rating + ($scores['fees'] >= 9 ? 0.2 : 0), 1));
        }

        return $scores;
    }

    /** @return array<int, array<string, mixed>> */
    protected function accountTemplates(string $name, string $slug, float $minDep, float $spread, int $lev): array
    {
        return [
            [
                'account_type' => 'Standard',
                'slug' => $slug . '-standard',
                'min_deposit' => $minDep,
                'max_leverage' => $lev,
                'max_leverage_numeric' => $lev,
                'leverage_label' => '1:' . $lev,
                'spread_type' => 'variable',
                'spread_value' => $spread,
                'spread_from_pips' => $spread,
                'commission' => 0,
                'commission_label' => 'No commission',
                'execution_model' => 'stp',
                'swap_free' => true,
                'description' => 'Commission-free account suited for beginners and swing traders.',
            ],
            [
                'account_type' => 'Raw / ECN',
                'slug' => $slug . '-raw',
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
    }

    /** @return array<int, array{title: string, detail: string}> */
    protected function faqTemplates(string $name): array
    {
        return [
            [
                'title' => 'Is ' . $name . ' regulated?',
                'detail' => $name . ' holds licenses from multiple regulators. Always confirm which entity serves your country before opening an account.',
            ],
            [
                'title' => 'What is the minimum deposit at ' . $name . '?',
                'detail' => 'Minimum deposit depends on account type. Standard accounts typically start low; professional ECN accounts may require a higher balance.',
            ],
            [
                'title' => 'How long do withdrawals take?',
                'detail' => 'E-wallet withdrawals are often processed within 24 hours. Bank transfers may take 1–5 business days depending on your region and bank.',
            ],
        ];
    }

    protected function seedForexBonuses(): void
    {
        $promoTypes = [
            'Forex Deposit Bonus',
            'Forex No Deposit Bonus',
            'Forex Live Contest',
            'Forex Demo Contest',
            'Forex Cashback Rebate',
            'Crypto Bonus Promotion',
        ];

        $bonusTemplates = [
            ['suffix' => 'Welcome Deposit Bonus', 'prize' => 'Up to 50% deposit bonus', 'pct' => 50, 'amount' => 500],
            ['suffix' => 'No Deposit Starter Bonus', 'prize' => '$30 no-deposit bonus', 'pct' => null, 'amount' => 30],
            ['suffix' => 'Monthly Trading Contest', 'prize' => '$10,000 prize pool', 'pct' => null, 'amount' => 10000],
            ['suffix' => 'Demo Trading Championship', 'prize' => 'Win a funded account', 'pct' => null, 'amount' => 5000],
            ['suffix' => 'Cashback Rebate Program', 'prize' => 'Up to 15% spread rebate', 'pct' => 15, 'amount' => null],
            ['suffix' => 'Crypto Deposit Promo', 'prize' => '20% bonus on crypto deposits', 'pct' => 20, 'amount' => 1000],
        ];

        for ($i = 0; $i < 20; $i++) {
            $broker = $this->brokers[$i];
            $promoType = $promoTypes[$i % count($promoTypes)];
            $template = $bonusTemplates[$i % count($bonusTemplates)];
            $slug = Str::slug($broker->slug . '-' . $template['suffix']);

            ForexBonus::create([
                'title' => $broker->name . ' ' . $template['suffix'],
                'slug' => $slug,
                'broker_id' => $broker->id,
                'publish_date' => now()->subDays(30 - $i)->toDateString(),
                'author_name' => $this->authors[$i % count($this->authors)]->name,
                'promo_type' => $promoType,
                'description' => '<p>Take advantage of the latest promotion from ' . $broker->name . '. This offer is designed for new and existing clients who meet the eligibility criteria.</p>',
                'feature_image' => PlaceholderImageFactory::bonusImage($slug, Str::limit($template['suffix'], 18, '')),
                'link' => $broker->url,
                'participate' => 'Register and verify your account with ' . $broker->name,
                'how_to_participate' => '<ol><li>Open a live account</li><li>Complete identity verification</li><li>Make a qualifying deposit or opt in via client portal</li><li>Meet turnover requirements before withdrawal</li></ol>',
                'details' => '<li>Promotion valid for eligible account types</li><li>Standard broker terms apply</li><li>Regional restrictions may apply</li>',
                'general_terms' => 'Bonus subject to broker terms and withdrawal conditions. Trading involves risk.',
                'prize' => $template['prize'],
                'eligibility_criteria' => 'New live accounts only. Minimum deposit may apply. Not available in all jurisdictions.',
                'expiry_date' => now()->addMonths(2 + ($i % 4))->toDateString(),
                'min_deposit' => $broker->minimum_deposit,
                'bonus_amount' => $template['amount'],
                'bonus_percentage' => $template['pct'],
                'bonus_category' => $promoType,
                'promotion_status' => $i % 7 === 0 ? 'limited-time' : 'ongoing',
                'is_featured' => $i < 8,
                'meta_title' => $broker->name . ' ' . $template['suffix'] . ' | BrokersCourt',
                'meta_description' => 'Get the ' . $template['suffix'] . ' from ' . $broker->name . '. See eligibility, terms, and how to claim.',
                'meta_keywords' => $broker->name . ', forex bonus, ' . $promoType,
                'written_by_author_id' => $this->writerId,
                'edited_by_author_id' => $this->editorId,
                'fact_checked_by_author_id' => $this->factCheckerId,
            ]);
        }
    }
}
