<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
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
            'rows' => $this->rows,
            'user' => [
                'firstname' => $this->user->firstname,
                'lastname' => $this->user->lastname,
            ],
            'pages' => count($this->pages),
        ];
    }
}
