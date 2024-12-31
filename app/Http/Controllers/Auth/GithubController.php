<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\GithubAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Log;

class GithubController extends Controller
{
    public function __construct(
        private readonly GithubAuthService $githubAuthService
    ) {}

    public function redirect(): RedirectResponse
    {
        try {
            return Socialite::driver('github')
                ->scopes(['user:email', 'read:org'])
                ->redirect();
        } catch (Exception $e) {
            Log::error('GitHub redirect failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('home')->with('error', 'Failed to connect to GitHub.');
        }
    }

    public function callback(): RedirectResponse
    {
        try {
            if (request()->has('error')) {
                Log::error('GitHub callback error', [
                    'error' => request()->get('error'),
                    'description' => request()->get('error_description')
                ]);
                throw new Exception(request()->get('error_description', 'Access denied'));
            }

            $githubUser = Socialite::driver('github')->user();
            
            if (empty($githubUser->email)) {
                throw new Exception('No email address provided by GitHub.');
            }

            $user = $this->githubAuthService->createOrUpdateUser($githubUser);
            
            Auth::login($user, true);
            session()->regenerate();

            Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'organizations' => $user->github_organizations
            ]);
            
            return redirect()->intended(route('home'))->with('success', 'Successfully logged in!');
            
        } catch (Exception $e) {
            Log::error('GitHub authentication failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => request()->all()
            ]);
            
            return redirect()->route('home')->with('error', 'Failed to authenticate with GitHub: ' . $e->getMessage());
        }
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'Successfully logged out!');
    }
} 