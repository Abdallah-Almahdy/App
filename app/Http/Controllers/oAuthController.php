<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OAuthController extends Controller
{
    public function loginWithGoogle(Request $request)
    {
        $idToken = $request->input('id_token');

        // 1. Verify ID token with Google
        $response = Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $idToken
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Invalid ID token'], 401);
        }

        $googleUser = $response->json();

        // 2. Validate audience (optional but recommended)
        if ($googleUser['aud'] !== env('GOOGLE_CLIENT_ID')) {
            return response()->json(['error' => 'Invalid Client ID'], 403);
        }

        // 3. Create or update user
        $user = User::updateOrCreate(
            ['email' => $googleUser['email']],
            [
                'name' => $googleUser['name'],
                'fcm_token' => $request->input('fcm_token', null),
            ]
        );

        // 4. Create token
        $token = $user->createToken('AndroidApp')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }
}
