<?php

namespace Tests\Feature;

use App\Mail\SubscriptionVerification;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SubscriberFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscribe_page_renders_with_footer_form(): void
    {
        $this->get(route('subscribe.index'))
            ->assertOk()
            ->assertSee('Join the briefing')
            ->assertSee('Join the BrokersCourt briefing')
            ->assertSee(route('subscribe'), false);
    }

    public function test_new_subscription_is_stored_and_verification_email_is_sent(): void
    {
        Mail::fake();

        $response = $this->from(route('subscribe.index'))->post(route('subscribe'), [
            'email' => 'Trader@Example.com',
        ]);

        $response
            ->assertRedirect(route('subscribe.index'))
            ->assertSessionHas('success');

        $subscriber = Subscriber::where('email', 'trader@example.com')->firstOrFail();

        $this->assertSame('Pending', $subscriber->status);
        $this->assertNotEmpty($subscriber->token);
        Mail::assertSent(SubscriptionVerification::class, fn ($mail) => $mail->hasTo('trader@example.com'));
    }

    public function test_verification_link_activates_the_subscriber(): void
    {
        $subscriber = Subscriber::create([
            'email' => 'reader@example.com',
            'status' => 'Pending',
            'token' => 'verification-token',
        ]);

        $this->get(route('subscriber.verify', [
            'token' => $subscriber->token,
            'email' => $subscriber->email,
        ]))
            ->assertRedirect(route('home'))
            ->assertSessionHas('success');

        $this->assertSame('Active', $subscriber->fresh()->status);
    }

    public function test_verification_email_renders_the_registered_route(): void
    {
        $subscriber = Subscriber::create([
            'email' => 'mail-preview@example.com',
            'status' => 'Pending',
            'token' => 'mail-preview-token',
        ]);

        $html = (new SubscriptionVerification($subscriber))->render();

        $this->assertStringContainsString(
            route('subscriber.verify', [
                'token' => $subscriber->token,
                'email' => $subscriber->email,
            ]),
            $html
        );
    }
}
