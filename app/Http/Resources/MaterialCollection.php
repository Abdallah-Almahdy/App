<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MaterialCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "date" => $this->collection->transform(function ($material) {
                return [
                    "name" => $material->name,
                    "department" => $material->department,
                    "semester" => $material->semester,
                    "link" => $material->link
                ];
            })

        ];
        return parent::toArray($request);
    }
}
