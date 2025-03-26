<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'data' => $this->collection->transform(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'link' => $task->link,
                    'user' => [
                        "name" => $task->user->name,
                        "title" => "admin"
                    ],
                    'committee' => [
                        "name" => $task->committee->name,
                        "id" => $task->committee->id
                    ]


                ];
            }),
        ];
        return parent::toArray($request);
    }
}
