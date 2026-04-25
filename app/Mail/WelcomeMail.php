<?php

namespace App\Mail;

use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\UnstructuredHeader;

class WelcomeMail extends BaseMail
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                env('MAIL_FROM_ADDRESS', 'noreply@example.com'),
                env('MAIL_FROM_NAME', 'Your Store')
            ),
            subject: 'Bienvenue sur ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
            with: ['name' => $this->name],
        );
    }

    public function attachments(): array
    {
        return [];
    }

    public function headers(): Headers
    {
        return new Headers(
            null,
            [],
            [
                'X-Custom-Header' => 'Welcome Email',
            ],
        );
    }

    public function build()
    {
        return $this->withSymfonyMessage(function (Email $message) {
            $this->addMailtrapHeaders($message, [
                'user_name' => $this->name,
                'notification_type' => 'welcome_email'
            ]);
        });
    }
}
