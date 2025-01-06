<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleFormController extends Controller
{
    public function submit(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        // Retrieve the OAuth token from the session
        $accessToken = session('google_token');
        if (!$accessToken) {
            return response()->json(['message' => 'User is not authenticated.'], 401);
        }

        // Configure Google Client
        $client = new Client();
        $client->setAccessToken($accessToken);

        // Verify the token works
        $tokenInfo = $client->verifyIdToken();
        if (!$tokenInfo) {
            return response()->json(['message' => 'Invalid or expired token.'], 401);
        }

        // Prepare form data
        $formData = [
            'entry.1000027' => $validatedData['name'], // Replace with actual entry ID
            'entry.1000057' => $validatedData['email'], // Replace with actual entry ID
        ];

        // Submit the Google Form
        $formId = '1FAIpQLSdZQCkmwCXexF_F-7r54F05HU8VcHea7OLppKlThWt-KdMTmA';
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->asForm()->post("https://docs.google.com/forms/d/e/$formId/formResponse", $formData);

        if ($response->successful()) {
            return response()->json(['message' => 'Form submitted successfully!'], 200);
        }

        return response()->json([
            'message' => 'Failed to submit form.',
            'error' => $response->body(),
        ], 500);
    }
}
