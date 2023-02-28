<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Invitation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $recipient,
        public ?string $additionalMessage,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Invitation',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.invitation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
