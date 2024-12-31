<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GithubOrganizationService
{
    private const CACHE_TTL = 3600; // 1 hour

    public function getOrganizationMembers(string $token, string $org = 'vehikl'): array
    {
        $cacheKey = "github_org_members_{$org}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($token, $org) {
            try {
                // First, get the list of organization members
                $response = Http::withToken($token)
                    ->get("https://api.github.com/orgs/{$org}/members");

                if (!$response->successful()) {
                    Log::error('Failed to fetch organization members', [
                        'org' => $org,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    return [];
                }

                $members = $response->json();

                // Then, fetch full user details for each member
                $membersWithDetails = array_map(function ($member) use ($token) {
                    try {
                        $userResponse = Http::withToken($token)
                            ->get("https://api.github.com/users/{$member['login']}");

                        if ($userResponse->successful()) {
                            $userDetails = $userResponse->json();
                            return array_merge($member, [
                                'name' => $userDetails['name'] ?? $userDetails['login']
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to fetch user details', [
                            'user' => $member['login'],
                            'error' => $e->getMessage()
                        ]);
                    }
                    
                    // Fallback to using login as name if the user details request fails
                    return array_merge($member, [
                        'name' => $member['login']
                    ]);
                }, $members);

                return $membersWithDetails;
            } catch (\Exception $e) {
                Log::error('Error fetching organization members', [
                    'org' => $org,
                    'error' => $e->getMessage()
                ]);
                return [];
            }
        });
    }

    public function isOrganizationMember(string $token, string $username, string $org = 'vehikl'): bool
    {
        try {
            $response = Http::withToken($token)
                ->get("https://api.github.com/orgs/{$org}/members/{$username}");

            return $response->status() === 204;
        } catch (\Exception $e) {
            Log::error('Error checking organization membership', [
                'org' => $org,
                'username' => $username,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
} 