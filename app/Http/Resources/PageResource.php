<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'content' => $this->content,
            'user' => $this->when(
                $this->user,
                function () {
                    return [
                        'firstname' => $this->user->firstname,
                        'lastname' => $this->user->lastname,
                    ];
                }
            ),
            'published' => $this->published,
            'template' => $this->template,
            'categories' => $this->categories,
            'subcategories' => $this->subcategories,
            'published_from' => $this->published_from,
            'published_to' => $this->published_to,
            'type' => $this->type,
            'slug' => $this->slug,
        ];
    }
}
