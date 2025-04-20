<?php

namespace App\Http\Controllers;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        return response()->json([
            'profile' => $user->profile
        ]);

    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'bio' => 'string',
            'phone' => 'string',
            'linkedin' => 'string',
            'image' => 'image|mimes:jpg,jpeg,png,gif'
        ]);

        if ($request->hasFile('image')) {
            $path = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            $user->profile->update(['image' => $path]);
        }

        $user->profile->update($request->except('image'));

        return response()->json([
            'profile' => $user->profile
        ]);

        
    }
}
