<?php

namespace App\Http\Controllers;

use App\Services\GameChallengeService;
use App\Models\GameChallenge;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class GameChallengeController extends Controller
{
    public function __construct(
        private GameChallengeService $gameChallengeService
    ) {}

    public function store(Request $request): RedirectResponse
    {
        try {
            // Validate request
            $validated = $request->validate([
                'challenged_id' => 'required|exists:users,id',
                'game_type' => 'required|string',
            ]);

            $this->gameChallengeService->createChallenge(Auth::user(), $validated);

            return Redirect::back()->with([
                'success' => 'Game challenge sent successfully!',
                'challenges' => $this->gameChallengeService->getUserChallenges(Auth::user()),
            ]);
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, GameChallenge $gameChallenge): RedirectResponse
    {
        try {
            // Validate request
            $validated = $request->validate([
                'status' => 'required|in:accepted,declined',
            ]);

            $this->gameChallengeService->respondToChallenge(
                $gameChallenge,
                Auth::user(),
                $validated['status']
            );

            return Redirect::back()->with('success', 'Game challenge updated successfully!');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    public function markAsRead(GameChallenge $gameChallenge): RedirectResponse
    {
        try {
            $gameChallenge->update(['read' => true]);
            return Redirect::back();
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }
}
