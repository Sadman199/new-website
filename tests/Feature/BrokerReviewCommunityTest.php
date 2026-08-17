<?php

namespace Tests\Feature;

use App\Models\AccountOption;
use App\Models\Broker;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BrokerReviewCommunityTest extends TestCase
{
    use RefreshDatabase;

    private Broker $broker;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->broker = Broker::create([
            'name' => 'Review Broker',
            'slug' => 'review-broker',
            'url' => 'https://example.com',
            'country' => 'Cyprus',
            'regulation' => ['FCA'],
            'demo_account_available' => true,
            'markets' => ['forex'],
            'is_scam' => false,
        ]);

        $this->createAccountOption('Standard', true, 1);
        $this->createAccountOption('Raw ECN', true, 2);
        $this->createAccountOption('Hidden VIP', false, 3);

        $this->user = User::create([
            'name' => 'Trader One',
            'email' => 'trader@example.com',
            'password' => Hash::make('password'),
            'country' => 'Bangladesh',
        ]);
    }

    private function createAccountOption(string $type, bool $active, int $sortOrder): void
    {
        AccountOption::create([
            'broker_id' => $this->broker->id,
            'account_type' => $type,
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
            'is_active' => $active,
            'sort_order' => $sortOrder,
        ]);
    }

    public function test_store_review_with_dimension_ratings_and_valid_account_type(): void
    {
        $response = $this->actingAs($this->user)->post(route('reviews.store', $this->broker), [
            'rating_cost' => 5,
            'rating_platforms' => 4,
            'rating_customer_support' => 4,
            'length_of_use' => '1_3_years',
            'account_type' => 'Standard',
            'description' => str_repeat('Solid broker experience with fair spreads. ', 2),
            'country' => 'Bangladesh',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $review = Review::query()->roots()->where('user_id', $this->user->id)->first();

        $this->assertNotNull($review);
        $this->assertSame(0, (int) $review->status);
        $this->assertSame(4, (int) $review->rating);
        $this->assertSame(5, (int) $review->rating_cost);
        $this->assertSame('Standard', $review->account_type);
        $this->assertSame('1_3_years', $review->length_of_use);
    }

    public function test_store_rejects_inactive_or_unknown_account_type(): void
    {
        $this->actingAs($this->user)->post(route('reviews.store', $this->broker), [
            'rating_cost' => 5,
            'rating_platforms' => 5,
            'rating_customer_support' => 5,
            'length_of_use' => '0_6_months',
            'account_type' => 'Hidden VIP',
            'description' => str_repeat('Trying inactive account type value here. ', 2),
        ])->assertSessionHasErrors('account_type');

        $this->actingAs($this->user)->post(route('reviews.store', $this->broker), [
            'rating_cost' => 5,
            'rating_platforms' => 5,
            'rating_customer_support' => 5,
            'length_of_use' => '0_6_months',
            'account_type' => 'Does Not Exist',
            'description' => str_repeat('Trying invalid account type value here. ', 2),
        ])->assertSessionHasErrors('account_type');
    }

    public function test_broker_detail_filters_and_lists_only_active_account_types(): void
    {
        $root = $this->broker->reviews()->create([
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'description' => 'Approved review body with enough detail for display.',
            'rating' => 5,
            'rating_cost' => 5,
            'rating_platforms' => 5,
            'rating_customer_support' => 5,
            'length_of_use' => 'over_3_years',
            'account_type' => 'Standard',
            'country' => 'Bangladesh',
            'status' => 1,
        ]);

        $replyUser = User::create([
            'name' => 'Reply User',
            'email' => 'reply@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->broker->reviews()->create([
            'parent_id' => $root->id,
            'user_id' => $replyUser->id,
            'name' => $replyUser->name,
            'email' => $replyUser->email,
            'description' => 'Approved reply body.',
            'rating' => 0,
            'country' => 'N/A',
            'status' => 1,
        ]);

        $response = $this->get(route('broker_detail', ['slug' => $this->broker->slug . '-review']));

        $response->assertOk();
        $response->assertSee('Overall User Rating', false);
        $response->assertSee('Rate and Review', false);
        $response->assertSee('Community Comments', false);
        $response->assertSee('Standard', false);
        $response->assertSee('Raw ECN', false);
        $response->assertDontSee('Hidden VIP', false);
        $response->assertSee('Approved review body with enough detail for display.', false);
        $response->assertSee('Approved reply body.', false);
        $response->assertSee('brReviewLoginModal', false);
        $response->assertSee('Reply', false);
    }

    public function test_score_and_length_filters_apply_on_broker_detail(): void
    {
        $this->broker->reviews()->create([
            'user_id' => $this->user->id,
            'name' => 'High',
            'email' => 'high@example.com',
            'description' => 'Outstanding score review text that should appear.',
            'rating' => 5,
            'length_of_use' => 'over_3_years',
            'account_type' => 'Standard',
            'country' => 'US',
            'status' => 1,
        ]);

        $other = User::create([
            'name' => 'Low',
            'email' => 'low@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->broker->reviews()->create([
            'user_id' => $other->id,
            'name' => 'Low',
            'email' => $other->email,
            'description' => 'Poor score review text that should be filtered out.',
            'rating' => 2,
            'length_of_use' => 'not_used',
            'account_type' => 'Raw ECN',
            'country' => 'US',
            'status' => 1,
        ]);

        $this->get(route('broker_detail', [
            'slug' => $this->broker->slug . '-review',
            'score' => 'outstanding',
        ]))
            ->assertOk()
            ->assertSee('Outstanding score review text that should appear.', false)
            ->assertDontSee('Poor score review text that should be filtered out.', false);

        $this->get(route('broker_detail', [
            'slug' => $this->broker->slug . '-review',
            'length' => 'not_used',
        ]))
            ->assertOk()
            ->assertSee('Poor score review text that should be filtered out.', false)
            ->assertDontSee('Outstanding score review text that should appear.', false);
    }

    public function test_guest_cannot_store_review_and_modal_markup_is_present(): void
    {
        $this->post(route('reviews.store', $this->broker), [
            'rating_cost' => 5,
            'rating_platforms' => 5,
            'rating_customer_support' => 5,
            'length_of_use' => '0_6_months',
            'account_type' => 'Standard',
            'description' => str_repeat('Guest attempt should redirect to login page. ', 2),
        ])->assertRedirect();

        $this->get(route('broker_detail', ['slug' => $this->broker->slug . '-review']))
            ->assertOk()
            ->assertSee('Sign in to continue', false)
            ->assertSee('brReviewLoginModal', false);
    }

    public function test_reply_is_pending_one_level_only(): void
    {
        $root = $this->broker->reviews()->create([
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'description' => 'Root review available for replies after approval.',
            'rating' => 4,
            'country' => 'Bangladesh',
            'status' => 1,
        ]);

        $replier = User::create([
            'name' => 'Reply User',
            'email' => 'reply2@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($replier)->post(route('reviews.replies.store', [$this->broker, $root]), [
            'description' => 'This is a moderated reply.',
        ])->assertRedirect();

        $reply = Review::query()->where('parent_id', $root->id)->first();
        $this->assertNotNull($reply);
        $this->assertSame(0, (int) $reply->status);
        $this->assertTrue($reply->isReply());

        $this->actingAs($replier)->post(route('reviews.replies.store', [$this->broker, $reply]), [
            'description' => 'Nested reply should fail.',
        ])->assertStatus(422);
    }
}
