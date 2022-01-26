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
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'user' => $this->when(
                $this->user, function () {
                    return [
                        'firstname' => $this->user->firstname,
                        'lastname' => $this->user->lastname,
                    ];
                }
            ),
            'published' => $this->published,
            'template' => $this->template,
            'categories' => $this->categories,
        ];
    }
}
