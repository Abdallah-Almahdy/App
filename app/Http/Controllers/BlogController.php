<?php

namespace App\Http\Controllers;

use App\Http\Resources\BlogCollection;
use App\Http\Resources\BlogResource;
use App\Models\Admin;
use App\Models\Blog;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class BlogController extends Controller
{


    public  function index()
    {

        return new BlogCollection(Blog::all());
    }


    public function show($id)
    {
        $Blog = Blog::find($id);

        if (!$Blog) {
            return response()->json([

                "success" => false,
                'message' => 'not found'
            ], 404); //
        }

        return new BlogResource($Blog);

    }


// change the user_id to the authenticated user
    public function store(Request $request)
    {
        Blog::validate($request);
        $user = auth()->user();


        if($request->file('image')){


            $path = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            if (!$path) {
                return response()->json(['message' => 'Image upload failed'], 500);
            }
        }

        $Blog = Blog::create([
            "title" => $request->title,
            "description" => $request->description,
            "user_id" => $user->id,
            'image' => $path

        ]);

        return new BlogResource($Blog);
    }

    public function update(Request $request, $id)
    {

        Blog::validate($request);

        $Blog = Blog::find($id);
        abort_if(!$Blog, 404, 'Blog not found');

        if($request->file('image')){

            $path = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            if (!$path) {
                return response()->json(['message' => 'Image upload failed'], 500);
            }
            $publicId = Admin::getPublicIdFromUrl($Blog->image);
            Cloudinary::destroy($publicId);
        }

        $Blog->update([
            "title" => $request->title,
            "description" => $request->description,
            'image' => $path
        ]);
        $Blog->save;

        return response()->json([
            "success" => true,
            "message" => "Blog updated successfully"
        ]);
    }

    public function destroy($id)
    {
        $Blog = Blog::find($id);
        abort_if(!$Blog, 404, 'Blog not found');

        $Blog->delete();

        return response()->json([
            "success" => true,
            "message" => "Blog updated successfully"
        ]);
    }
}
