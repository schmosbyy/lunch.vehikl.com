<?php

namespace App\Http\Controllers;


use App\Models\GameChallenge;
use App\Models\RideRequest;
use App\Services\GithubOrganizationService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct(

    ) {

    }

    public function index(): \Inertia\Response
    {
        $nextFriday = Carbon::now()->next('Friday');
        $user = auth()->user();
        $rsvp = null;
        $rsvpList = [];
        $challenges = null;
        $organizationMembers = [];
        $rideRequests = [];

        if ($user) {
            $rsvp = $user->rsvps()
                ->where('lunch_date', $nextFriday->format('Y-m-d'))
                ->first();

            // Get user's game challenges
            $challenges = [
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

            // Get organization members if user is part of Vehikl
            if ($user->github_organizations && collect($user->github_organizations)->contains('login', 'vehikl')) {
                $organizationMembers = app(GithubOrganizationService::class)
                    ->getOrganizationMembers($user->github_token);
            }

            // Get ride requests for next Friday
            $rideRequests = RideRequest::with(['user', 'helper'])
                ->whereDate('lunch_date', $nextFriday->format('Y-m-d'))
                ->get();

            Log::info('Ride requests found:', [
                'count' => $rideRequests->count(),
                'date' => $nextFriday->format('Y-m-d'),
                'requests' => $rideRequests->toArray()
            ]);

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

            Log::info('Mapped ride requests:', [
                'requests' => $rideRequests->toArray()
            ]);
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
            'nextFriday' => [
                'date' => $nextFriday->format('Y-m-d'),
                'formatted' => $nextFriday->format('l, F j, Y'),
            ],
            'hasRsvped' => !is_null($rsvp),
            'rsvpList' => $rsvpList,
            'challenges' => $challenges,
            'games' => config('games.available_games'),
            'organizationMembers' => $organizationMembers,
            'rideRequests' => $rideRequests,
        ]);
    }

}
