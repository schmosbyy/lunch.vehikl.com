<?php

namespace App\Services;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GithubAuthService
{
    public function createOrUpdateUser($githubUser): User
    {
        try {
            // Fetch user's organizations with error handling
            $response = Http::withToken($githubUser->token)
                ->get('https://api.github.com/user/orgs');

            if (!$response->successful()) {
                Log::error('Failed to fetch GitHub organizations', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                $orgs = [];
            } else {
                $orgs = collect($response->json())->map(function ($org) {
                    return [
                        'id' => $org['id'],
                        'login' => $org['login'],
                        'avatar_url' => $org['avatar_url'] ?? null
                    ];
                })->all();
            }

            // Create or update user with password to satisfy auth requirements
            return User::updateOrCreate(
                ['github_id' => $githubUser->id],
                [
                    'name' => $githubUser->name ?? $githubUser->nickname,
                    'email' => $githubUser->email,
                    'password' => bcrypt(str()->random(32)), // Required for auth
                    'github_nickname' => $githubUser->nickname,
                    'github_token' => $githubUser->token,
                    'github_avatar' => $githubUser->avatar,
                    'github_organizations' => $orgs
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error creating/updating user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getGithubUser(): GithubUser
    {
        return Socialite::driver('github')->user();
    }
} 