<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Committee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function  login(Request $request)
    {
        $request->validate([

            'email' => 'required|email',
            'password' => 'required|max:255'
        ]);

        $user = Admin::where('email', $request->post('email'))->first();

        if ($user && Hash::check($request->post('password'), $user->password)) {

            $token = $user->createToken('userToken')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user
            ], 200);
        }

        return response()->json([
            'Massage' => 'invalid Credential'
        ], 401);
    }

    public function logout($request)
    {
        auth('admin')->logout();
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Admin successfully logged out',
        ]);
    }

public function register(Request $request)
{
        $request->validate([
            'email' => 'required|email|unique:admins',
            'password' => 'required|string|min:6|max:255',
            'title' => 'required',
            'name' => 'required',
        ]);


        $admin = Admin::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'title' => $request->title
    ]);
    // Create an empty profile for the admin
    $admin->profile()->create();

    $token = $admin->createToken('admin-token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'admin' => $admin,
    ]);


}
}
