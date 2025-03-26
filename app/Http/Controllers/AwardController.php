<?php

namespace App\Http\Controllers;

use App\Http\Resources\AwardCollection;
use App\Http\Resources\AwardResource;
use App\Models\Award;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    public function index()
    {
        $awards = Award::all();
        return new AwardCollection($awards);
    }


    public function store(Request $request){

        $award = Award::validate($request);
        $award = Award::create($award);
        return new AwardResource($award);

    }
    public function show($id)
    {
        $award = Award::find($id);
        if(!$award){
            return response()->json(['message' => 'Award not found'], 404);
        }
        return new AwardResource($award);
    }

    public function update(Request $request, $id)
    {
        $award = Award::find($id);
        if(!$award){
            return response()->json(['message' => 'Award not found'], 404);
        }
        $awardData = Award::validate($request);
        $award->update($awardData);
        $award->save();
        return new AwardResource($award);
    }

    public function destroy($id)
    {
        $award = Award::find($id);
        if(!$award){
            return response()->json(['message' => 'Award not found'], 404);
        }
        $award->delete();
        return response()->json(['message' => 'Award deleted successfully'], 200);
    }
}
