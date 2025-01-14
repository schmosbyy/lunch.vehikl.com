<?php

namespace App\Console\Commands;

use App\Models\Rsvp;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetRsvpStatuses extends Command
{
    protected $signature = 'rsvp:reset';
    protected $description = 'Reset all RSVP statuses for the current Friday to prepare for next week';

    public function handle()
    {
        $currentFriday = Carbon::now()->startOfDay();
        
        try {
            // Delete all RSVPs for the current Friday
            $count = Rsvp::where('lunch_date', $currentFriday->format('Y-m-d'))->delete();
            
            Log::info('Successfully reset RSVP statuses', [
                'date' => $currentFriday->format('Y-m-d'),
                'count' => $count
            ]);
            
            $this->info("Successfully reset {$count} RSVP(s) for {$currentFriday->format('Y-m-d')}");
        } catch (\Exception $e) {
            Log::error('Failed to reset RSVP statuses', [
                'date' => $currentFriday->format('Y-m-d'),
                'error' => $e->getMessage()
            ]);
            
            $this->error("Failed to reset RSVPs: {$e->getMessage()}");
        }
    }
}
