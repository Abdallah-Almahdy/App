<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\collection;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class CollectionController extends Controller
{

    public function index()
    {

        return response()->json([
            'data' => collection::all()
        ]);
    }

    public function store(Request $request)
    {

        collection::validate($request);

        $path = Cloudinary::upload($request->file('image')
            ->getRealPath())
            ->getSecurePath();

        $collection = collection::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $path,
            'total' => $request->total,
        ]);

        return response()->json([
            'message' => 'Collection created successfully',
            'data' => $collection
        ], 201);
    }
    public function show($id)
    {

        $collection = collection::find($id);
        if (!$collection) {
            return response()->json([
                'message' => 'Collection not found'
            ], 404);
        }

        return response()->json([
            'data' => $collection
        ]);
    }

    public function update(Request $request, $id)
    {

        $collection = collection::find($id);
        if (!$collection) {
            return response()->json([
                'message' => 'Collection not found'
            ], 404);
        }

        collection::validate($request);

        if ($request->hasFile('image'))
        {
            $path = Cloudinary::upload($request->file('image')
                ->getRealPath())
                ->getSecurePath();
            $collection->image = $path;
            cloudinary()->destroy(Admin::getPublicIdFromUrl($collection->image));
        }

        $collection->name = $request->name;
        $collection->description = $request->description;
        $collection->total = $request->total;
        $collection->save();

        return response()->json([
            'message' => 'Collection updated successfully',
            'data' => $collection
        ]);
    }

    public function destroy($id)
    {

        $collection = collection::find($id);
        if (!$collection) {
            return response()->json([
                'message' => 'Collection not found'
            ], 404);
        }

        cloudinary()->destroy(Admin::getPublicIdFromUrl($collection->image));
        $collection->delete();

        return response()->json([
            'message' => 'Collection deleted successfully'
        ]);
    }

}
