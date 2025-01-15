<?php

namespace App\Services;

use App\Mail\GameChallengeNotification;
use App\Mail\GameChallengeResponse;
use App\Models\GameChallenge;
use App\Models\Rsvp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class GameChallengeService
{
    public function createChallenge(User $challenger, array $data): GameChallenge
    {
        $nextFriday = Carbon::now()->next('Friday');

        // Get the challenged user's RSVP
        $challengedRsvp = Rsvp::where('user_id', $data['challenged_id'])
            ->where('lunch_date', $nextFriday->format('Y-m-d'))
            ->firstOrFail();

        // Check if there's already a challenge for this game
        $this->validateExistingChallenge($challenger, $data, $challengedRsvp);

        // Create a new game challenge
        $challenge = GameChallenge::create([
            'challenger_id' => $challenger->id,
            'challenged_id' => $data['challenged_id'],
            'rsvp_id' => $challengedRsvp->id,
            'game_type' => $data['game_type'],
            'status' => 'pending',
        ]);

        // Send notifications
        $this->sendChallengeNotifications($challenge);

        return $challenge;
    }

    public function respondToChallenge(GameChallenge $challenge, User $user, string $status): GameChallenge
    {
        if ($challenge->challenged_id !== $user->id) {
            throw new \Exception('User is not authorized to respond to this challenge.');
        }

        if (!in_array($status, ['accepted', 'declined'])) {
            throw new \Exception('Invalid challenge response status.');
        }

        $challenge->update(['status' => $status]);

        // Send notification to challenger
        Mail::to($challenge->challenger->email)->queue(
            new GameChallengeResponse($challenge, $challenge->challenger, true)
        );

        // Send notification to challenged user
        Mail::to($challenge->challenged->email)->queue(
            new GameChallengeResponse($challenge, $challenge->challenged, false)
        );

        return $challenge;
    }

    public function getUserChallenges(User $user): array
    {
        $nextFriday = Carbon::now()->next('Friday');

        return [
            'sent' => GameChallenge::with(['challenged', 'challenger'])
                ->where('challenger_id', $user->id)
                ->whereHas('rsvp', function ($query) use ($nextFriday) {
                    $query->where('lunch_date', $nextFriday->format('Y-m-d'));
                })
                ->get(),
            'received' => GameChallenge::with(['challenged', 'challenger'])
                ->where('challenged_id', $user->id)
                ->whereHas('rsvp', function ($query) use ($nextFriday) {
                    $query->where('lunch_date', $nextFriday->format('Y-m-d'));
                })
                ->get(),
        ];
    }

    private function validateExistingChallenge(User $challenger, array $data, Rsvp $challengedRsvp): void
    {
        $existingChallenge = GameChallenge::where('challenger_id', $challenger->id)
            ->where('challenged_id', $data['challenged_id'])
            ->where('rsvp_id', $challengedRsvp->id)
            ->where('game_type', $data['game_type'])
            ->whereIn('status', ['pending', 'accepted'])
            ->first();

        if ($existingChallenge) {
            throw new \Exception("You have already challenged this user to {$data['game_type']}.");
        }
    }

    private function sendChallengeNotifications(GameChallenge $challenge): void
    {
        // Send email to challenger
        Mail::to($challenge->challenger->email)->queue(
            new GameChallengeNotification($challenge, $challenge->challenger, true)
        );

        // Send email to challenged user
        Mail::to($challenge->challenged->email)->queue(
            new GameChallengeNotification($challenge, $challenge->challenged, false)
        );
    }
} 