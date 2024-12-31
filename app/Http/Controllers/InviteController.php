<?php

namespace App\Http\Controllers;

use App\Mail\RsvpInvitation;
use App\Models\RsvpInvite;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class InviteController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'github_id' => 'required|integer',
            'github_username' => 'required|string',
        ]);

        try {
            $nextFriday = Carbon::now()->next('Friday');
            
            // Create the invitation record
            $invitation = RsvpInvite::create([
                'inviter_id' => auth()->id(),
                'invitee_github_id' => $request->github_id,
                'invitee_github_username' => $request->github_username,
                'lunch_date' => $nextFriday->format('Y-m-d'),
            ]);

            Mail::to($request->github_username . '@github.com')
                ->queue(new RsvpInvitation(
                    inviterName: auth()->user()->name,
                    inviteeUsername: $request->github_username,
                    lunchDate: $nextFriday->toDateString(),
                    lunchDateFormatted: $nextFriday->format('l, F j, Y'),
                    invitationId: $invitation->id,
                    userId: $request->github_id
                ));

            Log::info('RSVP invitation sent', [
                'inviter' => auth()->user()->name,
                'invitee' => $request->github_username,
                'lunch_date' => $nextFriday->toDateString()
            ]);

            return back()->with('success', 'Invitation sent successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to send RSVP invitation', [
                'error' => $e->getMessage(),
                'invitee' => $request->github_username
            ]);

            return back()->with('error', 'Failed to send invitation. Please try again.');
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        try {
            RsvpInvite::where('inviter_id', auth()->id())
                ->where('invitee_github_id', $request->github_id)
                ->where('lunch_date', Carbon::now()->next('Friday')->format('Y-m-d'))
                ->delete();

            return back()->with('success', 'Invitation cancelled.');
        } catch (\Exception $e) {
            Log::error('Failed to cancel invitation', [
                'error' => $e->getMessage(),
                'invitee_id' => $request->github_id
            ]);

            return back()->with('error', 'Failed to cancel invitation. Please try again.');
        }
    }
} 