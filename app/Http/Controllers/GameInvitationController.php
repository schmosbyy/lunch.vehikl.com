<?php

namespace App\Http\Controllers;

use App\Models\GameInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameInvitationController extends Controller
{
    public function accept(Request $request, GameInvitation $invitation, User $user)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'This invitation link has expired.');
        }

        // If user is logged in, verify it's the correct user
        if (Auth::check() && Auth::id() !== $user->id) {
            return view('invitations.error', [
                'message' => 'You are logged in as a different user. Please log out and try again, or log in as the correct user.'
            ]);
        }

        $invitation->update([
            'accepted_at' => now(),
            'status' => 'accepted'
        ]);

        if (Auth::check()) {
            return view('invitations.accepted-authenticated', [
                'invitation' => $invitation,
                'user' => $user
            ]);
        }

        return view('invitations.accepted', [
            'invitation' => $invitation,
            'user' => $user
        ]);
    }
} 