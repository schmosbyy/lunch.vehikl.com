<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

Route::post('/form-submitted', function (Request $request) {
    try {
        Log::info('Form submission received', ['payload' => $request->all()]);

        if (!$request->has('message') || $request->message !== 'Form submitted') {
            return response()->json(['success' => false, 'error' => 'Invalid submission'], 400);
        }

        if (!$request->has('email')) {
            return response()->json(['success' => false, 'error' => 'Missing email'], 400);
        }

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            Log::error('User not found for email', ['email' => $request->email]);
            return response()->json(['success' => false, 'error' => 'User not found'], 404);
        }

        // Use transaction to prevent race conditions
        \DB::transaction(function () use ($user) {
            // Delete any existing out-of-town RSVP for next Friday
            $user->rsvps()
                ->where('date', Carbon::parse('next friday')->toDateString())
                ->where('status', 'out_of_town')
                ->delete();
            
            // Create new out-of-town RSVP
            $user->rsvps()->create([
                'status' => 'out_of_town',
                'date' => Carbon::parse('next friday')->toDateString(),
            ]);
        });
        
        Log::info('Out of town RSVP created successfully', ['user' => $user->email]);
        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        Log::error('Error processing form submission', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['success' => false, 'error' => 'Server error'], 500);
    }
});
