<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AwardCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'data' => $this->collection->transform(function ($award) {
                return [
                    'id' => $award->id,
                    'title' => $award->title,
                    'description' => $award->description,
                    'date' => $award->date,
                ];
            }),
        ];
        return parent::toArray($request);
    }
}
