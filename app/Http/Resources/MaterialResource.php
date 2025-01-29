<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            "name" => $this->name,
            "department" => $this->department,
            "semester" => $this->semester,
            "link" => $this->link
        ];
        return parent::toArray($request);
    }
}
