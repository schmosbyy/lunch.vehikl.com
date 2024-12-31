<?php

namespace App\Mail;

use App\Models\Rsvp;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RsvpCancellation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Rsvp $rsvp
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Lunch RSVP Cancellation',
            from: 'noreply@lunch.vehikl.com'
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.rsvp-cancellation',
            with: [
                'name' => $this->rsvp->user->name,
                'date' => $this->rsvp->lunch_date,
            ]
        );
    }
}
