<?php

namespace App\Http\Controllers;

use App\Mail\RsvpConfirmation;
use App\Mail\RsvpCancellation;
use App\Mail\RideCancellationNotification;
use App\Models\Rsvp;
use App\Models\RideRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RsvpInvite;
use App\Models\GameChallenge;

class RsvpController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $nextFriday = now()->next('Friday');

        // Check if user already has an out of town status for this date
        $existingRsvp = Rsvp::where('user_id', $user->id)
            ->where('lunch_date', $nextFriday->toDateString())
            ->first();

        if ($existingRsvp && $existingRsvp->status === 'out_of_town') {
            return redirect()->route('home')->with('error', 'You have already marked yourself as out of town for this date.');
        }

        $rsvp = Rsvp::updateOrCreate(
            [
                'user_id' => $user->id,
                'lunch_date' => $nextFriday->toDateString(),
            ],
            [
                'status' => 'attending',
            ]
        );

        // Send confirmation email
        Mail::to($user->email)->queue(new RsvpConfirmation($rsvp));

        return redirect()->route('home')->with('success', 'Your RSVP has been confirmed.');
    }

    public function markOutOfTown(Request $request)
    {
        $user = auth()->user();
        $nextFriday = now()->next('Friday');

        // Check if user already has an RSVP for this date
        $existingRsvp = Rsvp::where('user_id', $user->id)
            ->where('lunch_date', $nextFriday->toDateString())
            ->first();

        if ($existingRsvp && $existingRsvp->status === 'attending') {
            return redirect()->route('home')->with('error', 'You have already RSVPed for this date. Please cancel your RSVP first.');
        }

        Rsvp::updateOrCreate(
            [
                'user_id' => $user->id,
                'lunch_date' => $nextFriday->toDateString(),
            ],
            [
                'status' => 'out_of_town',
            ]
        );

        return redirect()->route('home')->with('success', 'You have been marked as out of town.');
    }

    public function resend(): RedirectResponse
    {
        $user = Auth::user();
        $nextFriday = Carbon::now()->next('Friday');

        try {
            $rsvp = Rsvp::where('user_id', $user->id)
                ->where('lunch_date', $nextFriday->format('Y-m-d'))
                ->first();

            if (!$rsvp) {
                return redirect()->route('home')->with('error', 'No RSVP found for the upcoming lunch.');
            }

            Mail::to($user->email)->queue(new RsvpConfirmation($rsvp));

            return redirect()->route('home')->with('success', 'Confirmation email has been resent. Please check your inbox.');
        } catch (\Exception $e) {
            Log::error('Failed to resend RSVP confirmation email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('home')->with('error', 'Failed to resend confirmation email. Please try again.');
        }
    }

    public function destroy()
    {
        $user = auth()->user();
        $nextFriday = Carbon::now()->next(Carbon::FRIDAY);

        $rsvp = Rsvp::where('user_id', $user->id)
            ->where('lunch_date', $nextFriday->format('Y-m-d'))
            ->first();

        if ($rsvp) {
            try {
                // Find any ride requests where the user is either the requester or helper
                $rideRequests = RideRequest::with(['user', 'helper'])
                    ->where(function ($query) use ($user) {
                        $query->where('user_id', $user->id)
                            ->orWhere('helper_id', $user->id);
                    })
                    ->whereDate('lunch_date', $nextFriday->format('Y-m-d'))
                    ->get();

                // Send cancellation emails for each affected ride request
                foreach ($rideRequests as $rideRequest) {
                    if ($rideRequest->helper_id) {
                        // If user is the requester, notify the helper
                        if ($rideRequest->user_id === $user->id && $rideRequest->helper) {
                            Mail::to($rideRequest->helper->email)
                                ->queue(new RideCancellationNotification($rideRequest, $user));
                        }
                        // If user is the helper, notify the requester
                        elseif ($rideRequest->helper_id === $user->id) {
                            Mail::to($rideRequest->user->email)
                                ->queue(new RideCancellationNotification($rideRequest, $user));
                        }
                    }
                }

                // Delete all game challenges where the user is either the challenger or challenged
                GameChallenge::where(function ($query) use ($user) {
                    $query->where('challenger_id', $user->id)
                        ->orWhere('challenged_id', $user->id);
                })->delete();

                // Delete any ride requests made by the user
                RideRequest::where('user_id', $user->id)
                    ->whereDate('lunch_date', $nextFriday->format('Y-m-d'))
                    ->delete();

                // Remove user as helper from any ride requests
                RideRequest::where('helper_id', $user->id)
                    ->whereDate('lunch_date', $nextFriday->format('Y-m-d'))
                    ->update(['helper_id' => null]);

                // Delete the RSVP
                $rsvp->delete();

                // Send cancellation email
                Mail::to($user->email)->queue(new RsvpCancellation($rsvp));
                
                return redirect()->route('home')->with('success', 'Your RSVP has been cancelled.');
            } catch (\Exception $e) {
                Log::error('Failed to cancel RSVP', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                
                return redirect()->route('home')->with('error', 'Failed to cancel RSVP. Please try again.');
            }
        }

        return redirect()->route('home')->with('error', 'No RSVP found to cancel.');
    }

    public function accept(Request $request, $invitation, $user)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'This invitation link has expired.');
        }

        // If user is logged in, verify it's the correct user
        if (auth()->check() && auth()->id() !== (int)$user) {
            return view('rsvp.error', [
                'message' => 'You are logged in as a different user. Please log out and try again with the correct account.'
            ]);
        }

        try {
            $invite = RsvpInvite::findOrFail($invitation);

            // Create user if they don't exist
            $rsvpUser = User::firstOrCreate(
                ['github_id' => $invite->invitee_github_id],
                [
                    'name' => $invite->invitee_github_username,
                    'email' => "{$invite->invitee_github_username}@github.com",
                    'password' => bcrypt(str()->random(32)),
                    'github_nickname' => $invite->invitee_github_username,
                    'github_avatar' => "https://github.com/{$invite->invitee_github_username}.png",
                    'github_token' => null,
                    'github_organizations' => null
                ]
            );

            $invite->update(['is_accepted' => true]);

            // Create or update RSVP with the correct lunch date
            $rsvp = Rsvp::updateOrCreate(
                [
                    'user_id' => $rsvpUser->id,
                    'lunch_date' => $invite->lunch_date->format('Y-m-d'),
                ],
                [
                    'status' => 'attending',
                ]
            );

            if (auth()->check()) {
                return view('rsvp.accepted-authenticated', [
                    'rsvp' => $rsvp,
                    'user' => $rsvpUser
                ]);
            }

            return view('rsvp.accepted', [
                'rsvp' => $rsvp,
                'user' => $rsvpUser
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to accept RSVP invitation', [
                'error' => $e->getMessage(),
                'invitation_id' => $invitation,
                'user_id' => $user,
                'trace' => $e->getTraceAsString()
            ]);
            abort(404, 'Invitation not found.');
        }
    }

    public function outOfTown(Request $request)
    {
        $user = $request->user();
        $nextFriday = Carbon::now()->next(Carbon::FRIDAY);

        // Create or update RSVP with out_of_town status
        $rsvp = Rsvp::updateOrCreate(
            [
                'user_id' => $user->id,
                'lunch_date' => $nextFriday->toDateString(),
            ],
            [
                'status' => 'out_of_town',
            ]
        );

        // Return JSON response for XHR requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'success']);
        }

        return redirect()->back();
    }
}
