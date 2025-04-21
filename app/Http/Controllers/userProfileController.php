<?php

namespace App\Http\Controllers;

use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class userProfileController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'image_url' => optional($user->profile)->image,
            'bio' => optional($user->profile)->bio,
            'phone' => optional($user->profile)->phone,
            'linkedIn_link' => optional($user->profile)->linkedin,
            'name' => $user->name,
            'email' => $user->email,
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

        // Get or create profile with explicit polymorphic relationship
        $profile = $user->profile;

        if ($request->file('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpg,jpeg,png,gif',
            ]);

            $path = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            if (!$path) {
                return response()->json(['message' => 'Image upload failed'], 500);
            }

            $profile->update([
                'image' => $path
            ]);
        }

        $profile->update([
            'bio' => $request->bio,
            'phone' => $request->phone,
            'linkedin' => $request->linkedin
        ]);


        return response()->json(['user' => $user], 200);
    }
}
