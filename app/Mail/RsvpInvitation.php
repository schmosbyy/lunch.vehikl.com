<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class RsvpInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public string $acceptUrl;

    public function __construct(
        public string $inviterName,
        public string $inviteeUsername,
        public string $lunchDate,
        public string $lunchDateFormatted,
        public int $invitationId,
        public int $userId
    ) {
        $this->acceptUrl = URL::temporarySignedRoute(
            'rsvp.accept',
            now()->addDays(5),
            ['invitation' => $invitationId, 'user' => $userId]
        );
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "You're invited to Vehikl Friday Lunch!",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.rsvp-invitation',
        );
    }
}
