<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Author;
use App\Models\Broker;
use App\Models\ForexBonus;
use App\Services\EditorialAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ForexBonusAdminCrudTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    /** @var array<int, string> */
    protected array $uploadedTestFiles = [];

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
    }

    protected function tearDown(): void
    {
        foreach ($this->uploadedTestFiles as $path) {
            if (is_file($path)) {
                unlink($path);
            }
        }

        parent::tearDown();
    }

    public function test_admin_can_create_bonus_linked_to_broker_with_editorial_credits(): void
    {
        Storage::fake('public');

        $broker = Broker::create([
            'name' => 'Test Broker',
            'slug' => 'test-broker',
            'rating' => 4.5,
            'country' => 'United Kingdom',
            'regulation' => ['FCA'],
            'logo' => '',
            'url' => 'https://example.com',
            'open_live' => 'https://example.com/live',
        ]);

        $author = Author::create([
            'name' => 'Bonus Writer',
            'email' => 'writer@test.com',
            'password' => Hash::make('password'),
            'token' => '',
            'can_write' => true,
            'can_edit' => false,
            'can_fact_check' => false,
        ]);

        $image = UploadedFile::fake()->createWithContent(
            'bonus.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9Wl2nWQAAAAASUVORK5CYII=')
        );

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin_forex_bonus_store'), [
            'title' => 'Welcome Bonus',
            'slug' => 'welcome-bonus',
            'broker_id' => $broker->id,
            'publish_date' => '2026-08-01',
            'promo_type' => 'Forex Deposit Bonus',
            'description' => 'Bonus description',
            'feature_image' => $image,
            'link' => 'https://example.com/bonus',
            'participate' => 'Sign up',
            'how_to_participate' => 'Register and deposit',
            'details' => '<li>Fast payout</li>',
            'general_terms' => 'Terms apply',
            'prize' => '$100 Bonus',
            'min_deposit' => 100,
            'bonus_amount' => 100,
            'bonus_percentage' => 50,
            'wagering_requirement' => '30x bonus',
            'max_credit' => 500,
            'eligible_clients' => 'new',
            'volume_requirement' => '5 standard lots',
            'promotion_status' => 'ongoing',
            'written_assignee' => 'author:' . $author->id,
        ]);

        $response->assertRedirect(route('admin_forex_bonus_show'));

        $bonus = ForexBonus::where('slug', 'welcome-bonus')->first();
        $this->assertNotNull($bonus);
        $this->uploadedTestFiles[] = public_path($bonus->feature_image);
        $this->assertSame($broker->id, $bonus->broker_id);
        $this->assertSame($author->id, $bonus->written_by_author_id);
        $this->assertSame('Bonus Writer', $bonus->author_name);
        $this->assertNotNull($bonus->feature_image);
        $this->assertSame('30x bonus', $bonus->wagering_requirement);
        $this->assertSame('500.00', (string) $bonus->max_credit);
        $this->assertSame('new', $bonus->eligible_clients);
        $this->assertSame('5 standard lots', $bonus->volume_requirement);

        $credits = EditorialAssignmentService::creditsFor($bonus);
        $this->assertNotEmpty($credits);
        $this->assertSame('Written', $credits[0]['label']);
    }

    public function test_admin_can_update_bonus_without_reuploading_image(): void
    {
        $broker = Broker::create([
            'name' => 'Update Broker',
            'slug' => 'update-broker',
            'rating' => 4.0,
            'country' => 'Australia',
            'regulation' => ['ASIC'],
            'logo' => '',
            'url' => 'https://example.com',
            'open_live' => 'https://example.com/live',
        ]);

        $bonus = ForexBonus::create([
            'title' => 'Old Title',
            'slug' => 'old-title',
            'broker_id' => $broker->id,
            'publish_date' => '2026-07-01',
            'author_name' => 'Editorial Team',
            'promo_type' => 'Forex Deposit Bonus',
            'description' => 'Desc',
            'feature_image' => 'uploads/forex_bonuses/existing.jpg',
            'link' => 'https://example.com/old',
            'participate' => 'Join',
            'how_to_participate' => 'Deposit',
            'details' => '<li>Item</li>',
            'general_terms' => 'Terms',
            'prize' => '$50',
            'promotion_status' => 'ongoing',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->put(route('admin_forex_bonus_update', $bonus->id), [
            'title' => 'Updated Title',
            'slug' => 'updated-title',
            'broker_id' => $broker->id,
            'publish_date' => '2026-08-02',
            'promo_type' => 'Forex Deposit Bonus',
            'description' => 'Updated description',
            'link' => 'https://example.com/new',
            'participate' => 'Join now',
            'how_to_participate' => 'Deposit funds',
            'details' => '<li>Updated item</li>',
            'general_terms' => 'Updated terms',
            'prize' => '$75',
            'bonus_amount' => 75,
            'wagering_requirement' => '20x deposit',
            'max_credit' => 750,
            'eligible_clients' => 'both',
            'volume_requirement' => '8 standard lots',
            'promotion_status' => 'limited-time',
            'is_featured' => '1',
        ]);

        $response->assertRedirect(route('admin_forex_bonus_show'));

        $bonus->refresh();
        $this->assertSame('Updated Title', $bonus->title);
        $this->assertSame('uploads/forex_bonuses/existing.jpg', $bonus->feature_image);
        $this->assertTrue($bonus->is_featured);
        $this->assertSame('75.00', (string) $bonus->bonus_amount);
        $this->assertSame('20x deposit', $bonus->wagering_requirement);
        $this->assertSame('750.00', (string) $bonus->max_credit);
        $this->assertSame('both', $bonus->eligible_clients);
    }
}
