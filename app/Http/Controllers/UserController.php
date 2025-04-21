<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Notifications\AppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'fcm_token' => 'required',

        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'fcm_token' => $request->fcm_token,
        ]);

        // Create an empty profile for the user
        $user->profile()->create();

        $token = $user->createToken('Token')->plainTextToken;
        $user->notify(new AppNotification('🎉 Welcome to Aiche! 🎉!', "Hello $user->name, Welcome to Aiche! We're excited to have you on board.
                                            Whether you're here to explore, learn,
                                            or connect, we've got everything you need to get started.
                                            Your journey starts now, and we're here to help you every step of the way! If you need any assistance,
                                            don’t hesitate to reach out.
                                            Enjoy exploring and make the most out of your experience with us! 🌟"));

        return response()->json(['token' => $token, 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $user = Admin::where('email', $request->email)->first();
        }



        if (!$user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($request->fcm_token) {

            $user->fcm_token = $request->fcm_token;
            $user->save();
        }

        $token = $user->createToken('Token')->plainTextToken;
        return response()->json(
            ['token' => $token, 'user' => $user],
            200
        );
    }


    public function logout(Request $request)
    {
        // Delete the current access token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }

}
