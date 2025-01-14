<?php

namespace App\Http\Controllers;


use App\Models\GameChallenge;
use App\Models\RideRequest;
use App\Services\GithubOrganizationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $nextFriday = Carbon::now()->next('Friday');
        $user = auth()->user();
        $rsvp = null;
        $rsvpList = [];
        $challenges = null;
        $organizationMembers = [];
        $rideRequests = [];
        $notificationChallenges = collect();

        if ($user) {
            $rsvp = $user->rsvps()
                ->where('lunch_date', $nextFriday->format('Y-m-d'))
                ->first();

            // Get user's game challenges for notifications (only pending)
            $notificationChallenges = GameChallenge::with(['challenged', 'challenger'])
                ->where('challenged_id', $user->id)
                ->where('status', 'pending')
                ->whereHas('rsvp', function ($query) use ($nextFriday) {
                    $query->where('lunch_date', $nextFriday->format('Y-m-d'));
                })
                ->get();

            // Get user's game challenges for the list (both pending and accepted)
            $challenges = [
                'sent' => GameChallenge::with(['challenged', 'challenger'])
                    ->where('challenger_id', $user->id)
                    ->whereHas('rsvp', function ($query) use ($nextFriday) {
                        $query->where('lunch_date', $nextFriday->format('Y-m-d'));
                    })
                    ->get(),
                'received' => GameChallenge::with(['challenged', 'challenger'])
                    ->where('challenged_id', $user->id)
                    ->whereIn('status', ['pending', 'accepted'])
                    ->whereHas('rsvp', function ($query) use ($nextFriday) {
                        $query->where('lunch_date', $nextFriday->format('Y-m-d'));
                    })
                    ->get(),
            ];

            // Get organization members if user is part of Vehikl
            if ($user->github_organizations && collect($user->github_organizations)->contains('login', 'vehikl')) {
                $githubMembers = app(GithubOrganizationService::class)
                    ->getOrganizationMembers($user->github_token);

                // Get all users from the database
                $dbUsers = \App\Models\User::query()
                    ->whereNotNull('github_nickname')
                    ->select('id', 'name', 'avatar_url', 'github_nickname')
                    ->get();

                $dbUsers = $dbUsers->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'login' => $user->github_nickname ?? $user->name,
                        'avatar_url' => $user->avatar_url,
                    ];
                })->toArray();

                // Combine and deduplicate members based on GitHub username or name
                $organizationMembers = collect($githubMembers)
                    ->concat($dbUsers)
                    ->filter(function ($member) {
                        return !empty($member['login']) && !empty($member['name']);
                    })
                    ->unique('login')
                    ->values()
                    ->all();
            }

            // Get ride requests for next Friday
            $rideRequests = RideRequest::with(['user', 'helper'])
                ->whereDate('lunch_date', $nextFriday->format('Y-m-d'))
                ->get();

            $rideRequests = $rideRequests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'location' => $request->location,
                    'notes' => $request->notes,
                    'user' => [
                        'id' => $request->user->id,
                        'name' => $request->user->name,
                        'avatar_url' => $request->user->avatar_url,
                    ],
                    'helper' => $request->helper ? [
                        'id' => $request->helper->id,
                        'name' => $request->helper->name,
                        'avatar_url' => $request->helper->avatar_url,
                    ] : null,
                ];
            });
        }

        // Get all RSVPs for next Friday
        $rsvpList = \App\Models\Rsvp::with('user')
            ->where('lunch_date', $nextFriday->format('Y-m-d'))
            ->get()
            ->map(function ($rsvp) {
                return [
                    'id' => $rsvp->id,
                    'status' => $rsvp->status,
                    'user' => [
                        'id' => $rsvp->user->id,
                        'name' => $rsvp->user->name,
                        'avatar_url' => $rsvp->user->avatar_url,
                    ]
                ];
            });

        return Inertia::render('Home', [
            'isLoggedIn' => auth()->check(),
            'user' => $user,
            'rsvp' => $rsvp,
            'rsvpList' => $rsvpList,
            'nextFriday' => [
                'date' => $nextFriday->format('Y-m-d'),
                'formatted' => $nextFriday->format('l, F j, Y'),
            ],
            'hasRsvped' => !is_null($rsvp),
            'challenges' => $challenges,
            'notificationChallenges' => $notificationChallenges,
            'games' => config('games.available_games'),
            'organizationMembers' => $organizationMembers,
            'rideRequests' => $rideRequests,
            'gameChallenges' => $user ? $challenges['received']->map(function ($challenge) {
                $game = collect(config('games.available_games'))->firstWhere('id', $challenge->game_type);
                return [
                    'id' => $challenge->id,
                    'game_type' => $game['name'] ?? $challenge->game_type,
                    'created_at' => $challenge->created_at->diffForHumans(),
                    'read' => $challenge->read,
                    'challenger' => [
                        'name' => $challenge->challenger->name,
                        'avatar_url' => $challenge->challenger->avatar_url,
                    ],
                ];
            }) : []
        ]);
    }

}
