<?php

namespace App\Http\Controllers;

use App\Http\Resources\MaterialCollection;
use App\Http\Resources\MaterialResource;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new MaterialCollection(Material::all() );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Material::validate($request);

        $materail = Material::create($request->all());

        return new MaterialResource($materail );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $materail = Material::find($id);
         if(!$materail){
            return response()->json([

                "success" => false,
                "not found!"
            ]);
         }

         return new MaterialResource($materail);

    }



    public function update(Request $request, string $id)
    {

        Material::validate($request);

        $materail = Material::find($id);
        if (!$materail) {
            return response()->json([

                "success" => false,
                "not found!"
            ]);
        }

        $materail->update($request->all());
        $materail->save();

        return response()->json([

            "success" => true,
            "message" => "Material updated successfully",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $materail = Material::find($id);
        if (!$materail) {
            return response()->json([

                "success" => false,
                "not found!"
            ]);
        }

        $materail->delete();

        return response()->json([
            'success' => true,
            'message' => 'Material deleted successfully',

        ]);
    }
}
