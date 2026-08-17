<?php

namespace App\Mail;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionVerification extends Mailable
{
    use Queueable, SerializesModels;

    public Subscriber $subscriber;

    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }


    public function build(): self
    {
        return $this
            ->subject('Confirm your BrokersCourt subscription')
            ->view('emails.subscription_verification')
            ->with([
                'url' => route('subscriber.verify', [
                    'token' => $this->subscriber->token,
                    'email' => $this->subscriber->email,
                ]),
            ]);
    }
}
