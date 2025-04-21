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

        $request->validate([
            'bio' => 'string|max:255|nullable',
            'phone' => 'string|max:255|nullable',
            'linkedin' => 'string|max:255|nullable',
        ]);

        $profile = $user->profile;

        if ($request->file('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            $path = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            if(!$path){
                return response()->json(['message' => 'Image upload failed'], 500);
    }
            $profile->update(['image' => $path]);

        }

        if ($profile && $profile instanceof \App\Models\Profile) {
            $profile->update([
                'bio' => $request->bio,
                'phone' => $request->phone,
                'linkedin' => $request->linkedin,
            ]);
        }

        return response()->json(['user' => $user], 200);
    }
}
