<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class collectionsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ["data"=> $this->collection->transform(function($collection){
            return [
                'id' => $collection->id,
                'name' => $collection->name,
                'description' => $collection->description,
                'image' => $collection->image,
                'total' => $collection->total,
                'products' => $collection->products,
            ];
        })
    ];
        return parent::toArray($request);
    }
}
