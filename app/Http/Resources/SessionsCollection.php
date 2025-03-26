<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SessionsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($session) {
                return [
                    'id' => $session->id,
                    'title' => $session->title,
                    'description' => $session->description,
                    'link' => $session->date,
                    'user' => [
                        "name" => $session->user->name,
                        "title" => "admin"
                    ],
                ];
            }),
        ];
        return parent::toArray($request);
    }
}
