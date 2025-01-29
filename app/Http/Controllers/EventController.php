<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    public function index()
    {
        return new EventCollection(Event::all());
    }

    public function store(Request $request)
    {
        try {

            Event::validate($request);
            $event = Event::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'date' => $request->input('date'),
            ]);

            Event::storeImages($request, $event->id);

            return new EventResource($event);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors()], 422);
        }
    }

    public function show($id)
    {
        try {
            $event = Event::findOrFail($id);
            return new EventResource($event);
        } catch (\Exception $e) {
            return response()->json([
                "massage" =>  "Not Found"
            ], 404);
        }
    }

    public function update(Request $request,$id)
    {

        Event::validate($request);
        $event = Event::find($id);
        abort_if(!$event,404, 'Blog not found');
        
        $event->update($request->all());
        $event->save();

        return response()->json([
            "message" => "Event updated successfully"
        ], 200);
    }

    public function destroy($id)
    {
        try {

            $event = Event::findOrFail($id);
            if ($event->images) {
                foreach ($event->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }
            $event->delete();
            return response()->json([
                'message' => 'Event deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()]);
        }
    }
}
