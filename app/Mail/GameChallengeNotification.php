<?php

namespace App\Mail;

use App\Models\GameChallenge;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GameChallengeNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public GameChallenge $challenge,
        public User $recipient,
        public bool $isChallenger
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->isChallenger 
                ? "You challenged {$this->challenge->challenged->name} to {$this->challenge->game_type}"
                : "{$this->challenge->challenger->name} challenged you to {$this->challenge->game_type}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.game-challenge',
            with: [
                'challenge' => $this->challenge,
                'recipient' => $this->recipient,
                'isChallenger' => $this->isChallenger,
            ],
        );
    }
}
