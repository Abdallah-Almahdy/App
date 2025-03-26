<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommitteeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($committee) {
                return [
                    'id' => $committee->id,
                    'name' => $committee->name,
                    'description' => $committee->description,
                    'img' => $committee->img,
                ];
            }),
        ];
        return parent::toArray($request);
    }
}
