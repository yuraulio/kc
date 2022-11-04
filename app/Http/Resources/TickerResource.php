<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TickerResource extends JsonResource
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
            'active' => $this->status,
            'from_date' => $this->from_date,
            'until_date' => $this->until_date,
            'created_at' => Carbon::parse($this->created_at)->toFormattedDateString()
        ];
    }
}
