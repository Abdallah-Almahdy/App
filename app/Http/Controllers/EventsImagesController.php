<?php

namespace App\Http\Controllers;

use App\Models\event_images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class EventsImagesController extends Controller
{

    public function update(Request $request, string $id)
    {

        try {

            $request->validate([
                "image" => 'required|image|mimes:jpg,jpeg,png,gif'
            ]);
            $image = event_images::findOrFail($id);
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {

                Storage::disk('public')->delete($image->image_path);
            }

            $path = $request->file('image')->store('images', 'public');
            $image->image_path = $path;
            $image->save();

            return response()->json([
                "message" => "image Updated successfully"

            ]);
        } catch (\Exception $e) {

            return response()->json([

                "error" => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {

        $image = event_images::find($id);
        if (!$image) {
            return abort(404);
        }
        if ($image->image_path && Storage::disk('public')->exists($image->image_path))
            Storage::disk('public')->delete($image->image_path);

        $image->delete();
        return response()->json(["message" => "image deleted successfully"]);
    }
    
}
