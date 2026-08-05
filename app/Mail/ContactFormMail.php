<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $messageContent;

    public function __construct($name, $email, $messageContent)
    {
        $this->name = $name;
        $this->email = $email;
        $this->messageContent = $messageContent;
    }

    public function build()
    {
        return $this->from('info@brokerscourt.com', 'BrokersCourt Website')
                    ->replyTo($this->email, $this->name)
                    ->subject('New Contact Form Submission')
                    ->view('emails.contact_html')   // ✅ HTML version
                    ->text('emails.contact_text')   // ✅ Plain text fallback
                    ->with([
                        'name' => $this->name,
                        'email' => $this->email,
                        'messageContent' => $this->messageContent,
                    ]);
    }
}
