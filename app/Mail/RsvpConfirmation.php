<?php

namespace App\Mail;

use App\Models\Rsvp;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RsvpConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Rsvp $rsvp
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Lunch RSVP Confirmation',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.rsvp-confirmation',
            with: [
                'userName' => $this->rsvp->user->name,
                'lunchDate' => $this->rsvp->lunch_date->format('l, F j, Y'),
                'status' => $this->rsvp->status === 'attending' ? 'will be attending' : 'will not be attending',
            ],
        );
    }
}
