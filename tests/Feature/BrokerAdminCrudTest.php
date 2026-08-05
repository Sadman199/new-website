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

    public function test_panel_broker_routes_redirect_to_legacy_admin(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.panel.brokers.index'));

        $response->assertRedirect(route('admin_broker_show'));
    }
}
