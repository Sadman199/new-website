<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Broker;
use App\Models\BrokerAlternativeItem;
use App\Models\BrokerAlternativePage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BrokerAlternativesFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function makeBroker(string $name, array $overrides = []): Broker
    {
        return Broker::create(array_merge([
            'name' => $name,
            'slug' => strtolower(str_replace(' ', '-', $name)),
            'url' => 'https://example.com',
            'country' => 'Cyprus',
            'rating' => 4.5,
            'is_scam' => false,
            'logo' => 'uploads/logos/test.png',
            'regulation' => ['CySEC'],
            'minimum_deposit' => 100,
            'spreads' => 'From 0.1 pips',
            'leverage' => '1:500',
            'platforms' => ['MetaTrader 4', 'MetaTrader 5'],
        ], $overrides));
    }

    public function test_landing_lists_only_published_pages(): void
    {
        $live = $this->makeBroker('Live Source');
        $hidden = $this->makeBroker('Hidden Source');

        BrokerAlternativePage::create(['broker_id' => $live->id, 'is_published' => true]);
        BrokerAlternativePage::create(['broker_id' => $hidden->id, 'is_published' => false]);

        $this->get(route('broker.alternatives.index'))
            ->assertOk()
            ->assertSee('Broker alternatives', false)
            ->assertSee('Live Source', false)
            ->assertSee(route('broker.alternatives.show', ['slug' => $live->slug]), false)
            ->assertDontSee(route('broker.alternatives.show', ['slug' => $hidden->slug]), false);
    }

    public function test_unpublished_page_returns_404(): void
    {
        $broker = $this->makeBroker('Draft Source');
        BrokerAlternativePage::create(['broker_id' => $broker->id, 'is_published' => false]);

        $this->get(route('broker.alternatives.show', ['slug' => $broker->slug]))
            ->assertNotFound();
    }

    public function test_review_slug_redirects_to_canonical_broker_slug(): void
    {
        $broker = $this->makeBroker('Canon Source', ['slug' => 'canon-source']);
        BrokerAlternativePage::create(['broker_id' => $broker->id, 'is_published' => true]);

        $this->get('/broker-alternatives/canon-source-review')
            ->assertStatus(301)
            ->assertRedirect(route('broker.alternatives.show', ['slug' => 'canon-source']));
    }

    public function test_admin_picks_render_in_order_and_beat_auto_recommendations(): void
    {
        $source = $this->makeBroker('Curated Source', ['rating' => 4.0]);
        $first = $this->makeBroker('Picked First', ['rating' => 3.0]);
        $second = $this->makeBroker('Picked Second', ['rating' => 3.1]);
        $this->makeBroker('High Rated Extra', ['rating' => 5.0]);

        $page = BrokerAlternativePage::create(['broker_id' => $source->id, 'is_published' => true]);
        BrokerAlternativeItem::create(['page_id' => $page->id, 'alternative_broker_id' => $first->id, 'sort_order' => 1]);
        BrokerAlternativeItem::create(['page_id' => $page->id, 'alternative_broker_id' => $second->id, 'sort_order' => 2]);

        $html = $this->get(route('broker.alternatives.show', ['slug' => $source->slug]))
            ->assertOk()
            ->assertSee('Best Alternatives to '.$source->name, false)
            ->assertSee('Recommended alternatives', false)
            ->assertDontSee('Automatically recommended alternatives', false)
            ->assertSee('Picked First', false)
            ->assertSee('Picked Second', false)
            ->assertDontSee('broker-card__name">High Rated Extra', false)
            ->getContent();

        $this->assertLessThan(strpos($html, 'Picked Second'), strpos($html, 'Picked First'));
    }

    public function test_automatic_recommendations_exclude_self_and_scam_brokers(): void
    {
        $source = $this->makeBroker('Auto Source', ['rating' => 4.2]);
        $good = $this->makeBroker('Auto Peer', ['rating' => 4.8]);
        $this->makeBroker('Scam Peer', ['rating' => 5.0, 'is_scam' => true]);
        BrokerAlternativePage::create(['broker_id' => $source->id, 'is_published' => true]);

        $this->get(route('broker.alternatives.show', ['slug' => $source->slug]))
            ->assertOk()
            ->assertSee('Automatically recommended alternatives', false)
            ->assertSee('Auto Peer', false)
            ->assertDontSee('Scam Peer', false)
            ->assertSee($source->name, false);
    }

    public function test_automatic_recommendations_show_available_brokers_without_padding(): void
    {
        $source = $this->makeBroker('Sparse Source', ['rating' => 4.0]);
        $only = $this->makeBroker('Only Peer', ['rating' => 4.4]);
        BrokerAlternativePage::create(['broker_id' => $source->id, 'is_published' => true]);

        $html = $this->get(route('broker.alternatives.show', ['slug' => $source->slug]))
            ->assertOk()
            ->assertSee('Only Peer', false)
            ->getContent();

        $this->assertSame(1, substr_count($html, 'broker-card__name">Only Peer'));
    }

    public function test_empty_catalog_shows_friendly_fallback(): void
    {
        $source = $this->makeBroker('Solo Source', ['rating' => 4.0]);
        BrokerAlternativePage::create(['broker_id' => $source->id, 'is_published' => true]);

        $this->get(route('broker.alternatives.show', ['slug' => $source->slug]))
            ->assertOk()
            ->assertSee('We could not find enough suitable alternatives', false)
            ->assertSee('Compare brokers', false)
            ->assertDontSee('Automatically recommended alternatives', false);
    }

    public function test_seo_canonical_and_faq_json_ld(): void
    {
        $source = $this->makeBroker('Seo Source');
        $this->makeBroker('Seo Peer', ['rating' => 4.7]);
        BrokerAlternativePage::create([
            'broker_id' => $source->id,
            'is_published' => true,
            'seo_title' => 'Custom SEO title for Seo Source',
            'meta_description' => 'Custom meta description for alternatives.',
            'faqs' => [
                ['question' => 'Why compare Seo Source?', 'answer' => 'To check live regulation and costs.'],
            ],
        ]);

        $url = route('broker.alternatives.show', ['slug' => $source->slug]);

        $this->get($url)
            ->assertOk()
            ->assertSee('<title>Custom SEO title for Seo Source</title>', false)
            ->assertSee('Custom meta description for alternatives.', false)
            ->assertSee('<link rel="canonical" href="'.$url.'">', false)
            ->assertSee('"@type": "FAQPage"', false)
            ->assertSee('Why compare Seo Source?', false);
    }

    public function test_faq_schema_omitted_when_empty(): void
    {
        $source = $this->makeBroker('No Faq Source');
        BrokerAlternativePage::create(['broker_id' => $source->id, 'is_published' => true]);

        $this->get(route('broker.alternatives.show', ['slug' => $source->slug]))
            ->assertOk()
            ->assertDontSee('"@type": "FAQPage"', false);
    }

    public function test_admin_can_publish_attach_and_disable(): void
    {
        $admin = Admin::create([
            'name' => 'Alt Admin',
            'email' => 'alt-admin@test.com',
            'password' => Hash::make('password'),
            'photo' => '',
            'token' => 'alt-token',
        ]);

        $source = $this->makeBroker('Admin Source');
        $peer = $this->makeBroker('Admin Peer');

        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $this->actingAs($admin, 'admin')
            ->get(route('admin_broker_alternatives_show'))
            ->assertOk()
            ->assertSee('ab-page--hub', false);

        $this->actingAs($admin, 'admin')
            ->get(route('admin_broker_alternatives_create'))
            ->assertOk()
            ->assertSee('Add alternatives page', false)
            ->assertSee('ab-layout--form', false)
            ->assertSee('Create page', false)
            ->assertSee('Recommended alternatives', false);

        $this->actingAs($admin, 'admin')
            ->post(route('admin_broker_alternatives_store'), [
                'broker_id' => $source->id,
                'is_published' => '1',
                'seo_title' => 'Admin SEO',
                'alternative_broker_ids' => [$peer->id],
                'faqs' => [
                    ['question' => 'Admin question?', 'answer' => 'Admin answer.'],
                ],
            ])
            ->assertRedirect();

        $page = BrokerAlternativePage::query()->where('broker_id', $source->id)->first();
        $this->assertNotNull($page);
        $this->assertTrue($page->is_published);
        $this->assertSame([$peer->id], $page->items()->pluck('alternative_broker_id')->all());

        $this->get(route('broker.alternatives.show', ['slug' => $source->slug]))
            ->assertOk()
            ->assertSee('Admin Peer', false)
            ->assertSee('Admin question?', false);

        $this->actingAs($admin, 'admin')
            ->post(route('admin_broker_alternatives_toggle', $page->id))
            ->assertRedirect(route('admin_broker_alternatives_show'));

        $this->assertFalse($page->fresh()->is_published);
        $this->get(route('broker.alternatives.show', ['slug' => $source->slug]))
            ->assertNotFound();
    }

    public function test_review_page_shows_alternatives_cta_only_when_published(): void
    {
        $live = $this->makeBroker('Cta Source');
        BrokerAlternativePage::create(['broker_id' => $live->id, 'is_published' => true]);

        $this->get(route('broker_detail', ['slug' => $live->slug.'-review']))
            ->assertOk()
            ->assertSee('Looking for an alternative to <em>Cta Source</em>?', false)
            ->assertSee(route('broker.alternatives.show', ['slug' => $live->slug]), false);

        $draft = $this->makeBroker('Draft Cta Source');
        BrokerAlternativePage::create(['broker_id' => $draft->id, 'is_published' => false]);

        $this->get(route('broker_detail', ['slug' => $draft->slug.'-review']))
            ->assertOk()
            ->assertDontSee('Looking for an alternative to <em>Draft Cta Source</em>?', false);
    }
}
