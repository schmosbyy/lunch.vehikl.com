<?php

namespace App\Console\Commands;

use App\Models\Rsvp;
use App\Models\RsvpInvite;
use App\Models\RideRequest;
use App\Models\GameChallenge;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetRsvpStatuses extends Command
{
    protected $signature = 'lunch:reset';
    protected $description = 'Reset all lunch-related data including RSVPs, invites, ride requests, and game challenges';

    public function handle()
    {
        $currentFriday = Carbon::now()->startOfDay();
        
        try {
            // Delete all RSVPs for the current Friday
            $rsvpCount = Rsvp::where('lunch_date', $currentFriday->format('Y-m-d'))->delete();
            
            // Delete all RSVP invites for the current Friday
            $inviteCount = RsvpInvite::where('lunch_date', $currentFriday->format('Y-m-d'))->delete();
            
            // Delete all ride requests for the current Friday
            $rideCount = RideRequest::where('lunch_date', $currentFriday->format('Y-m-d'))->delete();
            
            // Delete all game challenges for the current Friday
            $challengeCount = GameChallenge::where('created_at', '>=', $currentFriday)->delete();
            
            Log::info('Successfully reset lunch data', [
                'date' => $currentFriday->format('Y-m-d'),
                'rsvps' => $rsvpCount,
                'invites' => $inviteCount,
                'rides' => $rideCount,
                'challenges' => $challengeCount
            ]);
            
            $this->info("Successfully reset lunch data for {$currentFriday->format('Y-m-d')}:");
            $this->line("- RSVPs: {$rsvpCount}");
            $this->line("- Invites: {$inviteCount}");
            $this->line("- Ride Requests: {$rideCount}");
            $this->line("- Game Challenges: {$challengeCount}");
        } catch (\Exception $e) {
            Log::error('Failed to reset lunch data', [
                'date' => $currentFriday->format('Y-m-d'),
                'error' => $e->getMessage()
            ]);
            
            $this->error("Failed to reset lunch data: {$e->getMessage()}");
        }
    }
}
