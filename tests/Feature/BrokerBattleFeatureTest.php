<?php

namespace Tests\Feature;

use App\Models\Broker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrokerBattleFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_battle_page_renders_dynamic_pair(): void
    {
        $this->createBroker('XM', 'xm', [
            'rating' => 4.5,
            'trust_score' => 88,
            'regulatory_tier' => 1,
            'minimum_deposit' => 5,
            'category_scores' => ['fees' => 8.5, 'safety' => 9.0],
        ]);
        $this->createBroker('Vantage', 'vantage', [
            'rating' => 4.2,
            'trust_score' => 80,
            'regulatory_tier' => 2,
            'minimum_deposit' => 50,
            'category_scores' => ['fees' => 7.5, 'safety' => 8.0],
        ]);

        $response = $this->get('/broker-battle/vantage-vs-xm');

        $response->assertOk();
        $response->assertSee('Broker Battle', false);
        $response->assertSee('XM', false);
        $response->assertSee('Vantage', false);
        $response->assertSee('Category battle', false);
        $response->assertSee('Share this battle', false);
        $response->assertSee('Start another battle', false);
        $response->assertSee('Vantage vs XM '.date('Y').' – Broker Battle | BrokersCourt', false);
    }

    public function test_battle_url_is_canonicalized_alphabetically(): void
    {
        $this->createBroker('XM', 'xm');
        $this->createBroker('Vantage', 'vantage');

        $this->get('/broker-battle/xm-vs-vantage')
            ->assertStatus(301)
            ->assertRedirect(route('brokers.battle', [
                'broker1_slug' => 'vantage',
                'broker2_slug' => 'xm',
            ]));
    }

    public function test_identical_brokers_are_rejected(): void
    {
        $this->createBroker('XM', 'xm');

        $this->get('/broker-battle/xm-vs-xm')->assertNotFound();
    }

    public function test_unknown_broker_returns_not_found(): void
    {
        $this->createBroker('XM', 'xm');

        $this->get('/broker-battle/missing-broker-vs-xm')->assertNotFound();
    }

    public function test_comparison_page_includes_battle_mode_hook(): void
    {
        $this->createBroker('XM', 'xm');
        $this->createBroker('Vantage', 'vantage');

        $response = $this->get(route('broker.comparison'));

        $response->assertOk();
        $response->assertSee('id="bcBattleModeLink"', false);
        $response->assertSee('battleBase', false);
        $response->assertSee('/broker-battle', false);
    }

    /** @param array<string, mixed> $overrides */
    private function createBroker(string $name, string $slug, array $overrides = []): Broker
    {
        return Broker::create(array_merge([
            'name' => $name,
            'slug' => $slug,
            'url' => 'https://example.com/'.$slug,
            'country' => 'Cyprus',
            'is_scam' => false,
            'rating' => 4.0,
            'trust_score' => 75,
            'regulatory_tier' => 1,
            'minimum_deposit' => 10,
            'spreads' => 'From 0.6 pips',
            'leverage' => '1:500',
            'commission' => 'None',
            'platforms' => ['MetaTrader 4'],
            'markets' => ['forex'],
            'regulation' => ['CySEC'],
            'category_scores' => [
                'fees' => 7.5,
                'safety' => 8.0,
            ],
        ], $overrides));
    }
}
