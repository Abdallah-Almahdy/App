<?php

namespace App\Http\Controllers;

use App\Http\Resources\collectionOrderResource;
use App\Http\Resources\collectionsCollection;
use App\Http\Resources\collectionsResource;
use App\Models\collectionOrder;
use App\Notifications\AppNotification;
use Illuminate\Http\Request;

class CollectionOrderController extends Controller
{
    public function index()
    {
        return new collectionsCollection(collectionOrder::with('collection')->get());
    }

    public function store(Request $request)
    {
        collectionOrder::validate($request);
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }

        $user->notify(new AppNotification(
            'Collection Order',
            'Your collection order has been created successfully.'
        ));

        $collectionOrder = collectionOrder::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $request->phone,
            'collection_id' => $request->collection_id,
        ]);

        return response()->json([
            'message' => 'Collection order created successfully',
            'data' => new collectionOrderResource($collectionOrder),
        ]);
    }

    public function delete($id)
    {
        $collectionOrder = collectionOrder::find($id);
        if (!$collectionOrder) {
            return response()->json([
                'message' => 'Collection order not found',
            ], 404);
        }
        $collectionOrder->delete();
        return response()->json([
            'message' => 'Collection order deleted successfully',
        ]);
    }
}
