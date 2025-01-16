<?php

// In SubscriptionVerification.php

namespace App\Mail;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriber;

    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }


    public function build()
{
    return $this->subject('Welcome to BrokersCourt!')
                ->view('emails.subscription_verification')
                ->with([
                    'url' => route('subscriber_verify', [
                        'token' => $this->subscriber->token,
                        'email' => $this->subscriber->email,
                    ]),
                ]);
}

}
