<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class collectionOrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "data" => $this->collection->transform(function ($collectionOrder) {
                return [
                    'id' => $collectionOrder->id,
                    'name' => $collectionOrder->name,
                    'email' => $collectionOrder->email,
                    'phone' => $collectionOrder->phone,
                    'collection' => new collectionsResource($collectionOrder->collection),
                ];
            })
        ];
        return parent::toArray($request);
    }
}
