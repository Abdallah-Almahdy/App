<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Baner;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class BanerController extends Controller
{

    public function index()
    {
        return response()->json([
            'baners' => Baner::all(),
        ]);
    }

    public function show($id)
    {
        $baner = Baner::find($id);

        if (!$baner) {
            return response()->json([
                'message' => 'Baner not found',
            ], 404);
        }

        return response()->json([
            'baner' => $baner,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif',
            'link' => 'required|string|max:255|url',
            'title' => 'required|string|max:255',
            'type' => 'string|max:255|in:facebook,instagram,twitter,linkedin',
        ]);

        $path = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
        if (!$path) {
            return response()->json(['message' => 'Image upload failed'], 500);
        }

        $baner = Baner::create([
            'image' => $path,
            'link' => $request->link,
            'title' => $request->title,
            'type' => $request->type,
        ]);

        return response()->json([
            'baner' => $baner,
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $baner = Baner::find($id);

        if (!$baner) {
            return response()->json([
                'message' => 'Baner not found',
            ], 404);
        }
        $request->validate([
            'image' => 'image|mimes:jpg,jpeg,png,gif',
            'link' => 'string|max:255|url',
            'title' => 'string|max:255',
            'type' => 'string|max:255|in:facebook,instagram,twitter,linkedin',
        ]);
        if ($request->file('image')) {
            $path = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            if (!$path) {
                return response()->json(['message' => 'Image upload failed'], 500);
            }
            $baner->image = $path;
            $image = Admin::getPublicIdFromUrl($baner->image);
            Cloudinary::destroy($image);
        }

        $baner::update([
            'link' => $request->link,
            'title' => $request->title,
            'type' => $request->type,
        ]);

        return response()->json([
            'baner' => $baner,
        ]);
    }

    public function destroy($id)
    {
        $baner = Baner::find($id);

        if (!$baner) {
            return response()->json([
                'message' => 'Baner not found',
            ], 404);
        }

        $baner->delete();

        return response()->json([
            'message' => 'Baner deleted successfully',
        ]);
    }
}
