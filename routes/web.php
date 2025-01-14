<?php

use App\Http\Controllers\Auth\GithubController;
use App\Http\Controllers\HomeController;
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

Route::get('/home', [HomeController::class,'index'])->name('home');

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
    Route::put('/game-challenge/{gameChallenge}/read', [GameChallengeController::class, 'markAsRead'])->name('game.read');

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
