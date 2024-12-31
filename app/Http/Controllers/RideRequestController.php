<?php

namespace App\Http\Controllers;

use App\Mail\RideOfferNotification;
use App\Models\RideRequest;
use App\Models\Rsvp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class RideRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $nextFriday = Carbon::now()->next('Friday');
        
        // Check if user has RSVPed for this Friday
        $hasRsvped = Rsvp::where('user_id', Auth::id())
            ->where('lunch_date', $nextFriday->format('Y-m-d'))
            ->where('status', 'attending')
            ->exists();

        if (!$hasRsvped) {
            throw ValidationException::withMessages([
                'rsvp' => ['You must RSVP for lunch before requesting a ride.'],
            ]);
        }

        // Check if user already has a ride request for this date
        $existingRequest = RideRequest::where('user_id', Auth::id())
            ->whereDate('lunch_date', $nextFriday->format('Y-m-d'))
            ->exists();

        if ($existingRequest) {
            throw ValidationException::withMessages([
                'request' => ['You already have a ride request for this Friday.'],
            ]);
        }

        $rideRequest = RideRequest::create([
            'user_id' => Auth::id(),
            'lunch_date' => $nextFriday,
            'location' => $request->location,
            'notes' => $request->notes,
        ]);

        return back();
    }

    public function offerRide(RideRequest $rideRequest)
    {
        // Check if user has RSVPed for this lunch
        $hasRsvped = Rsvp::where('user_id', Auth::id())
            ->whereDate('lunch_date', $rideRequest->lunch_date)
            ->where('status', 'attending')
            ->exists();

        if (!$hasRsvped) {
            throw ValidationException::withMessages([
                'rsvp' => ['You must RSVP for lunch before offering a ride.'],
            ]);
        }

        $rideRequest->update([
            'helper_id' => Auth::id(),
        ]);

        // Load the relationships for the email
        $rideRequest->load(['user', 'helper']);

        // Send email only to the user who requested the ride
        Mail::to($rideRequest->user->email)
            ->queue(new RideOfferNotification($rideRequest));

        return back();
    }

    public function cancelOffer(RideRequest $rideRequest)
    {
        if ($rideRequest->helper_id === Auth::id()) {
            $rideRequest->update([
                'helper_id' => null,
            ]);
        }

        return back();
    }

    public function destroy(RideRequest $rideRequest)
    {
        if ($rideRequest->user_id === Auth::id()) {
            $rideRequest->delete();
        }

        return back();
    }
}
