<?php

namespace App\Http\Controllers;

use App\Http\Resources\productOrderCollection;
use App\Http\Resources\productOrderResource;
use App\Models\productOrder;
use App\Notifications\AppNotification;
use Illuminate\Http\Request;

class ProductOrderController extends Controller
{
    public function index()
    {
        return new productOrderCollection(productOrder::with('product')->get());
    }

    public function store(Request $request)
    {
        productOrder::validate($request);
        $user = $request->user();
        $user->notify(new AppNotification(
            'Collection Order',
            'Your collection order has been created successfully.'
        ));

        $productOrder = productOrder::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $request->phone,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'message' => 'Product order created successfully',
            'data' => new productOrderResource($productOrder),
        ]);
    }

    public function delete($id)
    {
        $productOrder = productOrder::find($id);
        if (!$productOrder) {
            return response()->json([
                'message' => 'Product order not found',
            ], 404);
        }
        $productOrder->delete();
        return response()->json([
            'message' => 'Product order deleted successfully',
        ]);
    }
}
