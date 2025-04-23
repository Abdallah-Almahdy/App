<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "success" =>true,
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->date,
            'end_date' => $this->end_date,
            'place' => $this->place,
            'formLink' => $this->formLink,
            'facebookLink' => $this->facebookLink,
            'category' => $this->category,
            'status' => $this->status,
            "image" => $this->images,
        ];
        return parent::toArray($request);
    }
}
