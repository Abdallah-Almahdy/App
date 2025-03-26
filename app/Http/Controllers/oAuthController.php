<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class oAuthController extends Controller
{


    public function  handleGoogleCallback($request)
    {
        $googleUser = Socialite::driver('google')->stateless()->userFromToken($request->token);


        $user = User::firstOrCreate(
            [
                'email' => $googleUser->getEmail()
            ],

            ['name' => $googleUser->getName()]
        );

        $token = $user->createToken('YourAppName')->plainTextToken;

        return response()->json(['token' => $token,'user' => $user]);
    }
}
