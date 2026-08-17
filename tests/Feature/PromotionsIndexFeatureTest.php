<?php

namespace Tests\Feature;

use App\Models\Broker;
use App\Models\ForexBonus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PromotionsIndexFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_promotions_hub_renders_dynamic_guide_faq_and_top_brokers(): void
    {
        $broker = Broker::create([
            'name' => 'Dynamic Promo Broker',
            'slug' => 'dynamic-promo-broker',
            'rating' => 4.8,
            'is_scam' => false,
            'url' => 'https://example.com',
            'open_live' => 'https://example.com/live',
            'country' => 'United Kingdom',
            'regulation' => ['FCA'],
        ]);

        $this->createPromotion($broker, [
            'title' => 'Dynamic 50% Welcome Offer',
            'promo_type' => 'Forex Deposit Bonus',
            'bonus_percentage' => 50,
            'bonus_type_details' => 'A dynamic deposit match generated from the current promotion record.',
            'min_deposit' => 100,
            'wagering_requirement' => '30x bonus',
            'max_credit' => 500,
            'eligible_clients' => 'new',
        ]);

        $this->createPromotion($broker, [
            'title' => 'Dynamic Demo Challenge',
            'slug' => 'dynamic-demo-challenge',
            'promo_type' => 'Forex Demo Contest',
            'bonus_type_details' => 'A practice contest using live promotion data.',
            'volume_requirement' => 'Ranked by ROI',
            'prize' => '$2,000 prize pool',
        ]);

        $response = $this->get(route('promotions.index'));

        $response->assertOk()
            ->assertSee('What’s Inside', false)
            ->assertSee('What is a Forex Promotion?', false)
            ->assertSee('Types of Forex Promotions', false)
            ->assertSee('Promotion Types at a Glance', false)
            ->assertSee('How to Evaluate Any Forex Promotion?', false)
            ->assertSee('Common Mistakes With Forex Promotions', false)
            ->assertSee('Regulation and Forex Promotions', false)
            ->assertSee('Current Promotions Available on BrokersCourt', false)
            ->assertSee('Top Rated Brokers', false)
            ->assertSee('Broker Promos FAQ', false)
            ->assertSee('Dynamic 50% Welcome Offer', false)
            ->assertSee('Dynamic Demo Challenge', false)
            ->assertSee('30x bonus', false)
            ->assertSee('$500', false)
            ->assertSee('Dynamic Promo Broker', false)
            ->assertSee('FAQPage', false);
    }

    public function test_expired_promotions_do_not_inflate_dynamic_guide_counts(): void
    {
        $broker = Broker::create([
            'name' => 'Expired Promo Broker',
            'slug' => 'expired-promo-broker',
            'rating' => 4.0,
            'is_scam' => false,
            'url' => 'https://example.com',
            'country' => 'Australia',
            'regulation' => ['ASIC'],
        ]);

        $this->createPromotion($broker, [
            'title' => 'Expired Offer Must Not Appear',
            'promotion_status' => 'expired',
            'expiry_date' => now()->subDay()->toDateString(),
        ]);

        $this->get(route('promotions.index'))
            ->assertOk()
            ->assertDontSee('Expired Offer Must Not Appear', false)
            ->assertSee('There are currently 0 active offers', false);
    }

    private function createPromotion(Broker $broker, array $overrides = []): ForexBonus
    {
        return ForexBonus::create(array_merge([
            'title' => 'Active Promotion',
            'slug' => 'active-promotion',
            'broker_id' => $broker->id,
            'publish_date' => now()->toDateString(),
            'author_name' => 'Editorial Team',
            'promo_type' => 'Forex Deposit Bonus',
            'description' => 'A current promotion used to build the dynamic promotions hub.',
            'feature_image' => 'uploads/forex_bonuses/test.jpg',
            'link' => 'https://example.com/promo',
            'participate' => 'Eligible countries vary.',
            'how_to_participate' => 'Register and follow the offer terms.',
            'details' => 'Current promotion details.',
            'general_terms' => 'Terms apply.',
            'prize' => '$500 bonus',
            'promotion_status' => 'ongoing',
            'is_featured' => true,
            'expiry_date' => now()->addMonth()->toDateString(),
        ], $overrides));
    }
}
