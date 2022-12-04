<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CountdownResource extends JsonResource
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
            'content' => $this->content,
            'published' => $this->published,
            'published_from' => $this->published_from,
            'published_to' => $this->published_from,
            'countdown_from' => $this->countdown_from,
            'button_status' => $this->button_status,
            'button_title' => $this->button_title,
            'delivery' => $this->delivery,
            'category' => $this->category,
            'created_at' => Carbon::parse($this->created_at)->toFormattedDateString(),

        ];
    }
}
