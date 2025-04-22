<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BlogCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            "data" => $this->collection->transform(function($Blog){
                return [
                    'id' => $Blog->id,
                    'title' => $Blog->title,
                    "description" => $Blog->description,
                    'image' => $Blog->image,
                    "user" => $Blog->user,
                    'created_at' => $Blog->created_at,
                    'updated_at' => $Blog->updated_at,
                    'user' =>[
                        'name' => $Blog->user->name,
                        'title' => $Blog->user->title,
                        'image' => $Blog->user->profile->image,
                        'bio' => $Blog->user->profile->bio,
                        'phone' => $Blog->user->profile->phone,
                        'linkedin' => $Blog->user->profile->linkedin,
                    ],

                ];

            })
        ];
        return parent::toArray($request);
    }
}
