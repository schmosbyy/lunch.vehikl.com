<?php

use App\Http\Controllers\Auth\GithubController;
use App\Http\Controllers\RsvpController;
use App\Http\Controllers\GameChallengeController;
use App\Http\Controllers\RideRequestController;
use App\Models\GameChallenge;
use App\Models\RideRequest;
use App\Models\Rsvp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Services\GithubOrganizationService;
use App\Http\Controllers\InviteController;

Route::redirect('/', '/home');

Route::get('/home', function () {
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
})->name('home');

// Auth Routes
Route::controller(GithubController::class)->group(function () {
    Route::get('/auth/github', 'redirect')->name('github.login');
    Route::get('/auth/github/callback', 'callback')->name('github.callback');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
});

// RSVP Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/rsvp', [RsvpController::class, 'store'])->name('rsvp.store');
    Route::post('/rsvp/resend', [RsvpController::class, 'resend'])->name('rsvp.resend');
    Route::post('/rsvp/cancel', [RsvpController::class, 'destroy'])->name('rsvp.cancel');
    Route::post('/rsvp/out-of-town', [RsvpController::class, 'markOutOfTown'])->name('rsvp.out-of-town');

    // Game Challenge Routes
    Route::post('/game-challenge', [GameChallengeController::class, 'store'])->name('game.challenge');
    Route::put('/game-challenge/{gameChallenge}', [GameChallengeController::class, 'update'])->name('game.respond');

    // Invite Routes
    Route::post('/invite', [InviteController::class, 'store'])->name('invite.store');
    Route::post('/invite/cancel', [InviteController::class, 'destroy'])->name('invite.cancel');

    // Ride Request Routes
    Route::controller(RideRequestController::class)->group(function () {
        Route::post('/ride-request', 'store')->name('ride-request.store');
        Route::post('/ride-request/{rideRequest}/offer', 'offerRide')->name('ride-request.offer');
        Route::post('/ride-request/{rideRequest}/cancel-offer', 'cancelOffer')->name('ride-request.cancel-offer');
        Route::post('/ride-request/{rideRequest}/cancel', 'destroy')->name('ride-request.cancel');
    });
});

// Public RSVP Routes (no auth required)
Route::get('/rsvp/accept/{invitation}/{user}', [RsvpController::class, 'accept'])
    ->name('rsvp.accept')
    ->middleware('signed');
