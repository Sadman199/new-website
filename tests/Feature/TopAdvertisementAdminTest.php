<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\TopAdvertisement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TopAdvertisementAdminTest extends TestCase
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

    public function test_top_ad_page_creates_row_when_missing(): void
    {
        $this->assertDatabaseCount('top_advertisements', 0);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin_top_ad_show'));

        $response->assertOk();
        $response->assertSee('Update Top Advertisement');
        $this->assertDatabaseHas('top_advertisements', [
            'id' => 1,
            'top_ad_status' => 'Hide',
        ]);
    }

    public function test_top_ad_can_be_updated(): void
    {
        TopAdvertisement::create([
            'top_ad' => '',
            'top_ad_url' => null,
            'top_ad_status' => 'Hide',
        ]);

        // Ensure id=1 exists (create may not force id=1 on empty table, but first insert is usually 1)
        $row = TopAdvertisement::query()->first();
        $this->assertNotNull($row);

        \DB::table('top_advertisements')->where('id', $row->id)->update(['id' => 1]);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin_top_ad_update'), [
                'top_ad_url' => 'https://example.com',
                'top_ad_status' => 'Show',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('top_advertisements', [
            'id' => 1,
            'top_ad_url' => 'https://example.com',
            'top_ad_status' => 'Show',
        ]);
    }
}
