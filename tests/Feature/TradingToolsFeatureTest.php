<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Broker;
use App\Models\TradingTool;
use App\Services\TradingCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TradingToolsFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function makeBroker(string $name, array $overrides = []): Broker
    {
        return Broker::create(array_merge([
            'name' => $name,
            'slug' => strtolower(str_replace(' ', '-', $name)),
            'url' => 'https://example.com',
            'country' => 'Cyprus',
            'rating' => 4.4,
            'is_scam' => false,
            'logo' => 'uploads/logos/test.png',
            'regulation' => ['CySEC'],
            'minimum_deposit' => 100,
            'spreads' => 'From 0.2 pips',
            'commission' => '$6 per lot',
            'leverage' => '1:500',
            'platforms' => ['MetaTrader 5'],
        ], $overrides));
    }

    public function test_hub_lists_existing_tools_by_category_and_keeps_urls(): void
    {
        $this->get(route('calculators.index'))
            ->assertOk()
            ->assertSee('Forex Trading Tools', false)
            ->assertSee('Trading Calculators', false)
            ->assertSee('Technical Analysis Tools', false)
            ->assertSee('Market Tools', false)
            ->assertSee(route('calculators.show', ['slug' => 'pip-calculator']), false)
            ->assertSee(route('calculators.show', ['slug' => 'trading-cost-calculator']), false)
            ->assertSee(route('trading.tools.show', ['slug' => 'live-market-widgets']), false)
            ->assertDontSee('Leverage Calculator', false)
            ->assertDontSee('ATR Calculator', false);
    }

    public function test_existing_calculator_urls_still_work(): void
    {
        $this->get('/calculators/pip-calculator')
            ->assertOk()
            ->assertSee('<h1', false)
            ->assertSee('Pip Calculator', false)
            ->assertSee('How to use this calculator', false)
            ->assertSee('FAQPage', false);

        $this->get('/trading-tools')
            ->assertRedirect(route('calculators.index'));

        $this->get('/calculators?tool=pip')
            ->assertRedirect(route('calculators.show', ['slug' => 'pip-calculator']));
    }

    public function test_trading_cost_calculator_uses_real_broker_data_and_marks_gaps(): void
    {
        $this->makeBroker('Alpha Costs');
        $this->makeBroker('No Commission Desk', ['commission' => null, 'spreads' => 'Variable']);

        $html = $this->get(route('calculators.show', ['slug' => 'trading-cost-calculator']))
            ->assertOk()
            ->assertSee('Trading Cost Calculator', false)
            ->assertSee('Alpha Costs', false)
            ->assertSee('From 0.2 pips', false)
            ->assertSee('$6 per lot', false)
            ->getContent();

        $this->assertStringContainsString('Unavailable', $html);
        $this->assertStringNotContainsString('Invented Broker', $html);
        $this->assertStringContainsString('cost-broker-search', $html);
        $this->assertStringContainsString('Search your broker', $html);
    }

    public function test_trading_cost_calculator_broker_search_returns_matching_database_brokers(): void
    {
        $match = $this->makeBroker('Zulu Prime');
        $this->makeBroker('No Commission Desk', ['commission' => null, 'spreads' => 'Variable']);
        $this->makeBroker('Hidden Scam Desk', ['is_scam' => true]);

        $this->get(route('calculators.broker_search', ['q' => 'Zulu']))
            ->assertOk()
            ->assertJsonCount(1, 'brokers')
            ->assertJsonPath('brokers.0.id', $match->id)
            ->assertJsonPath('brokers.0.name', 'Zulu Prime');

        $this->get(route('calculators.broker_search', ['q' => 'zuluprime']))
            ->assertOk()
            ->assertJsonPath('brokers.0.name', 'Zulu Prime');

        $this->get(route('calculators.broker_search', ['q' => 'Invented Broker']))
            ->assertOk()
            ->assertJsonCount(0, 'brokers');

        $this->get(route('calculators.broker_search', ['q' => 'Scam']))
            ->assertOk()
            ->assertJsonCount(0, 'brokers');
    }

    public function test_hidden_tool_is_not_on_hub(): void
    {
        TradingTool::query()->where('slug', 'pip')->update(['is_active' => false]);

        $this->get(route('calculators.index'))
            ->assertOk()
            ->assertDontSee(route('calculators.show', ['slug' => 'pip-calculator']), false);

        $this->get(route('calculators.show', ['slug' => 'pip-calculator']))
            ->assertNotFound();
    }

    public function test_calculate_endpoints_preserve_existing_and_new_tools(): void
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $this->postJson(route('calculators.calculate'), [
            'tool' => 'pip',
            'pair' => 'EUR/USD',
            'lots' => 1,
            'account_currency' => 'USD',
        ])->assertOk()->assertJsonPath('ok', true);

        $this->postJson(route('calculators.calculate'), [
            'tool' => 'cost',
            'pair' => 'EUR/USD',
            'lots' => 1,
            'spread_pips' => 0.8,
            'commission_per_lot' => 7,
            'nights' => 0,
            'account_currency' => 'USD',
        ])->assertOk()->assertJsonPath('result.spread_available', true);

        $this->postJson(route('calculators.calculate'), [
            'tool' => 'cost',
            'pair' => 'EUR/USD',
            'lots' => 1,
        ])->assertStatus(422)->assertJsonFragment(['error' => 'Enter at least a spread, commission, or swap value. Missing figures are not invented.']);
    }

    public function test_admin_can_update_category_seo_faqs_and_relations(): void
    {
        $admin = Admin::create([
            'name' => 'Tools Admin',
            'email' => 'tools-admin@test.com',
            'password' => Hash::make('password'),
            'photo' => '',
            'token' => 'tools-token',
        ]);

        $pip = TradingTool::query()->where('slug', 'pip')->firstOrFail();
        $position = TradingTool::query()->where('slug', 'position')->firstOrFail();
        $broker = $this->makeBroker('Related Desk');

        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $this->actingAs($admin, 'admin')
            ->get(route('admin_trading_tools_edit', $pip->id))
            ->assertOk()
            ->assertSee('SEO', false)
            ->assertSee('Related tools', false);

        $this->actingAs($admin, 'admin')
            ->post(route('admin_trading_tools_update', $pip->id), [
                'name' => 'Pip Value Calculator',
                'icon' => 'fas fa-exchange-alt',
                'short_description' => 'Admin short copy',
                'description' => 'Admin description',
                'sort_order' => 1,
                'is_active' => '1',
                'category' => 'trading_calculators',
                'seo_title' => 'Admin Pip Title',
                'meta_description' => 'Admin pip meta description',
                'introduction' => 'Admin introduction',
                'how_to_use' => 'Admin how to',
                'formula' => 'Admin formula',
                'example' => 'Admin example',
                'related_tool_ids' => [$position->id],
                'related_broker_ids' => [$broker->id],
                'faqs' => [
                    ['question' => 'Admin pip question?', 'answer' => 'Admin pip answer.'],
                ],
            ])
            ->assertRedirect(route('admin_trading_tools_index'));

        $pip->refresh();
        $this->assertSame('Pip Value Calculator', $pip->name);
        $this->assertSame('Admin Pip Title', $pip->seo_title);
        $this->assertSame([$position->id], $pip->relatedToolIdList());
        $this->assertSame([$broker->id], $pip->relatedBrokerIdList());
        $this->assertSame('Admin pip question?', $pip->normalizedFaqs()[0]['question']);

        $this->get(route('calculators.show', ['slug' => 'pip-calculator']))
            ->assertOk()
            ->assertSee('Admin Pip Title', false)
            ->assertSee('Admin introduction', false)
            ->assertSee('Admin pip question?', false)
            ->assertSee('Related Desk', false)
            ->assertSee('Position Size', false);
    }

    public function test_cost_calculator_math(): void
    {
        $result = TradingCalculator::calculate('cost', [
            'pair' => 'EUR/USD',
            'lots' => 1,
            'spread_pips' => 1,
            'commission_per_lot' => 6,
            'swap_per_lot' => 2,
            'nights' => 2,
            'account_currency' => 'USD',
            'price' => 1.1,
        ]);

        $this->assertFalse(isset($result['error']));
        $this->assertSame(6.0, $result['commission_cost']);
        $this->assertSame(4.0, $result['swap_cost']);
        $this->assertTrue($result['spread_available']);
        $this->assertTrue($result['total_complete']);
    }
}
