<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EventCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
                'data' =>  $this->collection->transform(function($event){
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'start_date' => $event->date,
                    'end_date' => $event->end_date,
                    'place' => $event->place,
                    'formLink' => $event->formLink,
                    'facebookLink' => $event->facebookLink,
                    'category' => $event->category,
                    'status' => $event->status,
                    'images' => $event->images
                ];
            }),
            'links' => [
                'self' => route('events.index'),
                ],
            ];
        return parent::toArray($request);
    }
}
