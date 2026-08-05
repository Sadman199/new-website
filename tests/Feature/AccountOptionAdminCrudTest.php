<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AccountOption;
use App\Models\Broker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountOptionAdminCrudTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected Broker $broker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'photo' => '',
            'token' => 'test-token',
        ]);

        $this->broker = Broker::create([
            'name' => 'Test Broker',
            'slug' => 'test-broker',
            'url' => 'https://example.com',
            'country' => 'Cyprus',
            'regulation' => ['FCA'],
            'demo_account_available' => true,
            'markets' => ['forex', 'crypto'],
        ]);
    }

    public function test_admin_can_view_account_options_index_for_broker(): void
    {
        AccountOption::create([
            'broker_id' => $this->broker->id,
            'account_type' => 'Standard',
            'account_currency' => 'USD',
            'min_deposit' => 100,
            'max_leverage' => 500,
            'max_leverage_numeric' => 500,
            'spread_type' => 'variable',
            'spread_from_pips' => 0.8,
            'spread_value' => 0.8,
            'min_trade_size' => 0.01,
            'max_trade_size' => 100,
            'margin_call_level' => 100,
            'stop_out_level' => 50,
            'max_open_positions' => 100,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin_account_options_index', $this->broker->id));

        $response->assertOk();
        $response->assertSee('Standard');
        $response->assertSee('Account Options');
    }

    public function test_admin_can_create_account_option_without_broker_duplicate_fields(): void
    {
        $payload = [
            'account_type' => 'ECN',
            'account_currency' => 'USD',
            'min_deposit' => 200,
            'max_leverage_numeric' => 1000,
            'leverage_label' => '1:1000',
            'spread_type' => 'raw',
            'spread_from_pips' => 0.1,
            'commission_label' => '$3.50/lot',
            'commission_per_lot' => 3.5,
            'execution_model' => 'ecn',
            'swap_free' => '1',
            'ea_allowed' => '1',
            'hedging_allowed' => '1',
            'is_active' => '1',
            'description' => 'Raw spread account for active traders.',
            'min_trade_size' => 0.01,
            'max_trade_size' => 500,
            'margin_call_level' => 100,
            'stop_out_level' => 50,
            'max_open_positions' => 200,
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin_account_options_store', $this->broker->id), $payload);

        $response->assertRedirect(route('admin_account_options_index', $this->broker->id));

        $this->assertDatabaseHas('account_options', [
            'broker_id' => $this->broker->id,
            'account_type' => 'ECN',
            'max_leverage_numeric' => 1000,
            'execution_model' => 'ecn',
            'swap_free' => 1,
        ]);

        $this->assertFalse(
            \Illuminate\Support\Facades\Schema::hasColumn('account_options', 'is_regulated'),
            'is_regulated should live on brokers, not account_options'
        );
    }

    public function test_admin_can_update_and_delete_account_option(): void
    {
        $option = AccountOption::create([
            'broker_id' => $this->broker->id,
            'account_type' => 'Standard',
            'account_currency' => 'USD',
            'min_deposit' => 50,
            'max_leverage' => 200,
            'spread_type' => 'variable',
            'min_trade_size' => 0.01,
            'max_trade_size' => 50,
            'margin_call_level' => 100,
            'stop_out_level' => 50,
            'max_open_positions' => 50,
            'is_active' => true,
        ]);

        $update = $this->actingAs($this->admin, 'admin')
            ->put(route('admin_account_options_update', [$this->broker->id, $option->id]), [
                'account_type' => 'Standard Pro',
                'account_currency' => 'EUR',
                'min_deposit' => 500,
                'max_leverage_numeric' => 200,
                'is_active' => '1',
            ]);

        $update->assertRedirect(route('admin_account_options_index', $this->broker->id));
        $this->assertDatabaseHas('account_options', [
            'id' => $option->id,
            'account_type' => 'Standard Pro',
            'account_currency' => 'EUR',
        ]);

        $delete = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin_account_options_delete', [$this->broker->id, $option->id]));

        $delete->assertRedirect(route('admin_account_options_index', $this->broker->id));
        $this->assertDatabaseMissing('account_options', ['id' => $option->id]);
    }

    public function test_global_account_options_list_is_accessible(): void
    {
        AccountOption::create([
            'broker_id' => $this->broker->id,
            'account_type' => 'Islamic',
            'account_currency' => 'USD',
            'max_leverage' => 500,
            'spread_type' => 'variable',
            'min_trade_size' => 0.01,
            'max_trade_size' => 100,
            'margin_call_level' => 100,
            'stop_out_level' => 50,
            'max_open_positions' => 100,
            'swap_free' => true,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin_account_options_all'));

        $response->assertOk();
        $response->assertSee('Islamic');
        $response->assertSee('Test Broker');
    }
}
