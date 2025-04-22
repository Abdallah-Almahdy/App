<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            "description" => $this->description,
            'image' => $this->image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => [
                'name' => $this->user->name,
                'title' => $this->user->title,
                'image' => $this->user->profile->image,
                'bio' => $this->user->profile->bio,
                'phone' => $this->user->profile->phone,
                'linkedin' => $this->user->profile->linkedin,
            ],


        ];
        return parent::toArray($request);
    }
}
