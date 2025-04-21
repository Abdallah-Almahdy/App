<?php

namespace App\Http\Controllers;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class userProfileController extends Controller
{

    public function profile(Request $request)
    {

        return response()->json([
            'image_url' => $request->user()->profile->image,
            'bio' => $request->user()->profile->bio,
            'phone' => $request->user()->profile->phone,
            'linkedIn_link' => $request->user()->profile->linkedin,
            'name' => $request->user()->name,
            'email' => $request->user()->email,
            
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        if ($request->file('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);
            $path = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            $user->profile->update(['image' => $path]);
        }


        $user->profile->update([
            'bio' => $request->bio,
            'phone' => $request->phone,
            'linkedin' => $request->linkedin,
        ])->save();

        return response()->json(['user' => $user], 200);
    }
}
