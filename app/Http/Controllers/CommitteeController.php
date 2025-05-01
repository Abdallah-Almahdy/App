<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommitteeCollection;
use App\Http\Resources\CommitteeResource;
use App\Models\Committee;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommitteeController extends Controller
{
    public function index()
    {
        $committes = Committee::all();

        return new CommitteeCollection($committes);
    }

    public function store(Request $request)
    {
        Committee::validate($request);

        if ($request->hasFile('img')) {
            $path = Cloudinary::upload($request->file('img')->getRealPath())
                ->getSecurePath();
            
        }
        $committee = Committee::create([
            'name' => $request->name,
            'description' => $request->description,
            'img' => $path
        ]);
        return new CommitteeResource($committee);
    }


    public function show($id)
    {
        $committee = Committee::find($id);
        if (!$committee) {
            return response()->json(['message' => 'Committee not found'], 404);
        }
        return new CommitteeResource($committee);
    }

    public function update(Request $request, $id)
    {
        Committee::validate($request);
        $committee = Committee::find($id);
        if (!$committee) {
            return response()->json(['message' => 'Committee not found'], 404);
        }



        if ($request->hasFile('img')) {
            if ($committee->img && Storage::disk('public')->exists($committee->img)) {
                Storage::disk('public')->delete($committee->img);
            }
            $path = $request->file('img')->store('images', 'public');
        }

        $committee->update([
            'name' => $request->name,
            'description' => $request->description,
            'img' => $path
        ]);
        $committee->save();
        return new CommitteeResource($committee);
    }


    public function destroy($id)
    {
        $committee = Committee::find($id);
        if (!$committee) {
            return response()->json(['message' => 'Committee not found'], 404);
        }
        if ($committee->img && Storage::disk('public')->exists($committee->img)) {
            Storage::disk('public')->delete($committee->img);
        }
        $committee->delete();
        return response()->json(['message' => 'Committee deleted successfully'], 200);
    }
}
