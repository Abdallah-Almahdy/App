<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\product;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => product::all()
        ]);
    }

    public function show($id)
    {
        $product = product::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'data' => $product
        ]);
    }

    public function store(Request $request)
    {

        product::validate($request);

        if ($request->hasFile('image')) {
            $path = Cloudinary::upload($request->file('image')
                ->getRealPath())
                ->getSecurePath();
        } else {
            $path = null;
        }


        $product = product::create([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $path,
            'link' => $request->link,
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = product::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        product::validate($request);

        if ($request->hasFile('image')) {
            $path = Cloudinary::upload($request->file('image')
                ->getRealPath())
                ->getSecurePath();

            $publicId =  Admin::getPublicIdFromUrl($product->image);
            Cloudinary::destroy($publicId);
        } else {
            $path = $product->image;
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $path,
            'link' => $request->link,
        ]);

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    public function destroy($id)
    {

        $product = product::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $publicId = Admin::getPublicIdFromUrl($product->image);
        Cloudinary::destroy($publicId);

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
