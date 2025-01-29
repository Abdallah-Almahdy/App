<?php

namespace App\Http\Controllers;

use App\Http\Resources\BlogCollection;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{


    public  function index()
    {

        return new BlogCollection(Blog::all());
    }



    public function store(Request $request)
    {
        Blog::validate($request);

        $Blog = Blog::create([
            "title" => $request->title,
            "description" => $request->description,
            "user_id" => 1
        ]);

        return new BlogResource($Blog);
    }

    public function update(Request $request, $id)
    {

        Blog::validate($request);

        $Blog = Blog::find($id);
        abort_if(!$Blog, 404, 'Blog not found');

        $Blog->update([
            "title" => $request->title,
            "description" => $request->description
        ]);

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
