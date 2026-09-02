<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Broker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BrokerAdminCrudTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $this->admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'photo' => '',
            'token' => 'test-token',
        ]);
    }

    public function test_admin_can_view_broker_list(): void
    {
        Broker::create([
            'name' => 'Test Broker',
            'slug' => 'test-broker',
            'url' => 'https://example.com',
            'country' => 'Cyprus',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin_broker_show'));

        $response->assertOk();
        $response->assertSee('Test Broker');
    }

    public function test_admin_can_create_broker_with_new_fields(): void
    {
        $payload = [
            'name' => 'Exness Clone',
            'slug' => 'exness-clone',
            'title' => 'Exness Clone Review',
            'url' => 'https://example.com',
            'country' => 'Cyprus',
            'year_founded' => 2008,
            'commission' => '$3.50 per lot',
            'fee_level' => 'low',
            'withdrawal_fee' => 'Free',
            'demo_link' => 'https://example.com/demo',
            'demo_duration' => 'Unlimited',
            'demo_account_available' => '1',
            'investor_protection' => '1',
            'trust_score' => 85,
            'regulatory_tier' => 1,
            'negative_balance_protection' => '1',
            'markets' => ['forex', 'crypto'],
            'instrument_count' => 120,
            'platforms' => ['MetaTrader 4', 'MetaTrader 5'],
            'regulation' => ['FCA', 'CySEC'],
            'broker_categories' => ['low-spread-brokers', 'mt4-brokers'],
            'regions' => ['asia', 'global'],
            'associated_countries' => ['india', 'singapore'],
            'category_scores' => [
                'fees' => 4.5,
                'safety' => 4.8,
            ],
            'verdict' => 'Solid broker for experienced traders.',
            'rating' => 4.5,
            'minimum_deposit' => 10,
            'account_types_combined' => 'Standard, Raw ECN',
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin_broker_store'), $payload);

        $broker = Broker::where('slug', 'exness-clone')->first();

        $this->assertNotNull($broker);
        $response->assertRedirect(route('admin_broker_edit', $broker->id));

        $this->assertDatabaseHas('brokers', [
            'slug' => 'exness-clone',
            'year_founded' => 2008,
            'commission' => '$3.50 per lot',
            'fee_level' => 'low',
            'trust_score' => 85,
            'instrument_count' => 120,
        ]);

        $this->assertTrue($broker->demo_account_available);
        $this->assertTrue($broker->investor_protection);
        $this->assertTrue($broker->negative_balance_protection);
        $this->assertEquals(['forex', 'crypto'], $broker->markets);
        $this->assertEquals(['FCA', 'CySEC'], $broker->regulation);
        $this->assertEquals(['low-spread-brokers', 'mt4-brokers'], $broker->broker_categories);
        $this->assertEquals(['asia', 'global'], $broker->regions);
        $this->assertEquals(['india', 'singapore'], $broker->associated_countries);
        $this->assertEquals(['Standard', 'Raw ECN'], $broker->account_types);
        $this->assertEquals(4.5, $broker->category_scores['fees']);
    }

    public function test_admin_can_update_broker(): void
    {
        $broker = Broker::create([
            'name' => 'Update Me',
            'slug' => 'update-me',
            'url' => 'https://example.com',
            'country' => 'UK',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin_broker_update', $broker->id), [
                'name' => 'Updated Broker',
                'slug' => 'update-me',
                'country' => 'UK',
                'verdict' => 'Updated verdict.',
                'trust_score' => 72,
                'account_types_combined' => 'Standard, ECN',
                'broker_categories' => ['scalping-brokers'],
                'regions' => ['united-kingdom', 'global'],
            ]);

        $response->assertRedirect(route('admin_broker_edit', $broker->id));

        $broker->refresh();
        $this->assertSame('Updated Broker', $broker->name);
        $this->assertSame('Updated verdict.', $broker->verdict);
        $this->assertSame(72, $broker->trust_score);
        $this->assertEquals(['Standard', 'ECN'], $broker->account_types);
        $this->assertEquals(['scalping-brokers'], $broker->broker_categories);
        $this->assertEquals(['united-kingdom', 'global'], $broker->regions);
    }

    public function test_admin_can_delete_broker(): void
    {
        $broker = Broker::create([
            'name' => 'Delete Me',
            'slug' => 'delete-me',
            'url' => 'https://example.com',
            'country' => 'UK',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin_broker_delete', $broker->id));

        $response->assertRedirect(route('admin_broker_show'));
        $this->assertDatabaseMissing('brokers', ['id' => $broker->id]);
    }

    public function test_broker_name_is_required(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin_broker_store'), [
                'country' => 'UK',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_invalid_broker_category_is_rejected(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin_broker_store'), [
                'name' => 'Invalid Category Broker',
                'country' => 'UK',
                'broker_categories' => ['not-a-real-category'],
            ]);

        $response->assertSessionHasErrors('broker_categories.0');
    }

    public function test_invalid_region_is_rejected(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin_broker_store'), [
                'name' => 'Invalid Region Broker',
                'country' => 'UK',
                'regions' => ['atlantis'],
            ]);

        $response->assertSessionHasErrors('regions.0');
    }

    public function test_admin_can_create_broker_with_capitalization_and_empty_url(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin_broker_store'), [
                'name' => 'Capital Broker',
                'slug' => 'capital-broker',
                'country' => 'Cyprus',
                'url' => '',
                'capitalization' => '2500000.75',
            ]);

        $broker = Broker::where('slug', 'capital-broker')->first();

        $this->assertNotNull($broker);
        $response->assertRedirect(route('admin_broker_edit', ['id' => $broker->id]));
        $this->assertNotSame('', $broker->url);
        $this->assertEquals(2500000.75, (float) $broker->capitalization);
    }

    public function test_non_numeric_capitalization_is_rejected(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->from(route('admin_broker_create'))
            ->post(route('admin_broker_store'), [
                'name' => 'Bad Capital Broker',
                'country' => 'UK',
                'capitalization' => 'ten million',
            ]);

        $response->assertRedirect(route('admin_broker_create'));
        $response->assertSessionHasErrors('capitalization');
        $this->assertDatabaseMissing('brokers', ['name' => 'Bad Capital Broker']);
    }

    public function test_panel_broker_routes_redirect_to_legacy_admin(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.panel.brokers.index'));

        $response->assertRedirect(route('admin_broker_show'));
    }

    public function test_admin_can_view_broker_details(): void
    {
        $broker = Broker::create([
            'name' => 'Detail Broker',
            'slug' => 'detail-broker',
            'url' => 'https://example.com',
            'country' => 'Cyprus',
            'rating' => 4.2,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin_broker_view', $broker->id));

        $response->assertOk();
        $response->assertSee('Detail Broker');
        $response->assertSee('Overview');
        $response->assertSee('Cyprus');
    }

    public function test_admin_can_filter_featured_brokers(): void
    {
        Broker::create([
            'name' => 'Featured One',
            'slug' => 'featured-one',
            'url' => 'https://example.com',
            'country' => 'UK',
            'featured_broker' => true,
        ]);
        Broker::create([
            'name' => 'Plain One',
            'slug' => 'plain-one',
            'url' => 'https://example.com',
            'country' => 'UK',
            'featured_broker' => false,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin_broker_show', ['status' => 'featured']));

        $response->assertOk();
        $response->assertSee('Featured One');
        $response->assertDontSee('Plain One');
    }

    public function test_admin_can_search_brokers_by_country(): void
    {
        Broker::create([
            'name' => 'Cyprus Desk',
            'slug' => 'cyprus-desk',
            'url' => 'https://example.com',
            'country' => 'Cyprus',
        ]);
        Broker::create([
            'name' => 'UK Desk',
            'slug' => 'uk-desk',
            'url' => 'https://example.com',
            'country' => 'United Kingdom',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin_broker_show', ['q' => 'Cyprus']));

        $response->assertOk();
        $response->assertSee('Cyprus Desk');
        $response->assertDontSee('UK Desk');
    }

    public function test_create_form_includes_category_description_fields(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin_broker_create'));

        $response->assertOk();
        $response->assertSee('name="category_descriptions[low-spread-brokers]"', false);
        $response->assertSee('name="region_descriptions[asia]"', false);
        $response->assertSee('name="country_descriptions[bangladesh]"', false);
    }

    public function test_admin_can_save_taxonomy_descriptions_with_a_broker(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin_broker_store'), [
                'name' => 'Described Broker',
                'slug' => 'described-broker',
                'country' => 'Cyprus',
                'url' => 'https://example.com',
                'broker_categories' => ['low-spread-brokers'],
                'regions' => ['asia'],
                'associated_countries' => ['bangladesh'],
                'category_descriptions' => [
                    'low-spread-brokers' => 'Tight spreads on major FX pairs.',
                ],
                'region_descriptions' => [
                    'asia' => 'Brokers commonly used by traders in Asia.',
                ],
                'country_descriptions' => [
                    'bangladesh' => 'Brokers available to traders in Bangladesh.',
                ],
            ]);

        $broker = Broker::where('slug', 'described-broker')->first();
        $this->assertNotNull($broker);
        $response->assertRedirect(route('admin_broker_edit', $broker->id));

        $this->assertDatabaseHas('broker_taxonomy_terms', [
            'type' => 'category',
            'slug' => 'low-spread-brokers',
            'description' => 'Tight spreads on major FX pairs.',
        ]);
        $this->assertDatabaseHas('broker_taxonomy_terms', [
            'type' => 'region',
            'slug' => 'asia',
            'description' => 'Brokers commonly used by traders in Asia.',
        ]);
        $this->assertDatabaseHas('broker_taxonomy_terms', [
            'type' => 'country',
            'slug' => 'bangladesh',
            'description' => 'Brokers available to traders in Bangladesh.',
        ]);
    }

    public function test_unselected_taxonomy_description_is_not_wiped(): void
    {
        \App\Models\BrokerTaxonomyTerm::create([
            'type' => 'category',
            'slug' => 'mt5-brokers',
            'description' => 'Keep this MetaTrader 5 copy.',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin_broker_store'), [
                'name' => 'Other Broker',
                'slug' => 'other-broker',
                'country' => 'UK',
                'url' => 'https://example.com',
                'broker_categories' => ['low-spread-brokers'],
                'category_descriptions' => [
                    'low-spread-brokers' => 'New low spread copy.',
                ],
            ]);

        $this->assertDatabaseHas('broker_taxonomy_terms', [
            'type' => 'category',
            'slug' => 'mt5-brokers',
            'description' => 'Keep this MetaTrader 5 copy.',
        ]);
        $this->assertDatabaseHas('broker_taxonomy_terms', [
            'type' => 'category',
            'slug' => 'low-spread-brokers',
            'description' => 'New low spread copy.',
        ]);
    }

    public function test_long_taxonomy_html_description_can_be_saved(): void
    {
        $html = '<p>'.str_repeat('Low spread brokers offer tight pricing on majors. ', 500).'</p>';

        $this->assertGreaterThan(20000, strlen($html));

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin_broker_store'), [
                'name' => 'Long Copy Broker',
                'slug' => 'long-copy-broker',
                'country' => 'Cyprus',
                'url' => 'https://example.com',
                'broker_categories' => ['low-spread-brokers'],
                'category_descriptions' => [
                    'low-spread-brokers' => $html,
                ],
            ]);

        $broker = Broker::where('slug', 'long-copy-broker')->first();
        $this->assertNotNull($broker);
        $response->assertRedirect(route('admin_broker_edit', $broker->id));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('broker_taxonomy_terms', [
            'type' => 'category',
            'slug' => 'low-spread-brokers',
            'description' => $html,
        ]);
    }

    public function test_empty_taxonomy_description_is_removed(): void
    {
        \App\Models\BrokerTaxonomyTerm::create([
            'type' => 'category',
            'slug' => 'scalping-brokers',
            'description' => 'Temporary scalping copy.',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin_broker_store'), [
                'name' => 'Clear Copy Broker',
                'slug' => 'clear-copy-broker',
                'country' => 'UK',
                'url' => 'https://example.com',
                'broker_categories' => ['scalping-brokers'],
                'category_descriptions' => [
                    'scalping-brokers' => '',
                ],
            ]);

        $this->assertDatabaseMissing('broker_taxonomy_terms', [
            'type' => 'category',
            'slug' => 'scalping-brokers',
        ]);
    }

    public function test_taxonomy_description_is_used_on_the_guide_page(): void
    {
        \App\Models\BrokerTaxonomyTerm::create([
            'type' => 'category',
            'slug' => 'low-spread-brokers',
            'description' => 'Custom low-spread listing copy from admin.',
        ]);

        $guide = \App\Support\BestBrokerGuideDefinition::forSlug('low-spread-brokers');

        $this->assertSame('Custom low-spread listing copy from admin.', $guide['description']);
        $this->assertNotSame($guide['description'], $guide['lead']);
    }
}
