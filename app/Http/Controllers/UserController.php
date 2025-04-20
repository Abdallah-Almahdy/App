<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Notifications\appNotifcation;
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

        $token = $user->createToken('Token')->plainTextToken;
        $user->notify(new appNotifcation('Hello!', 'This is a test push notification asd .'));

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
        if (auth()->logout()) {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Logged out'], 200);
        }
        return response()->json(['unauthenticated'], 401);
    }
}
