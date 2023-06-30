<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResourceRoyalty extends JsonResource
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
            'id' => $this['id'],
            'instructor' => $this['instructor'],
            'title' => $this['title'],
            'income' => '€ '.number_format((float)$this['income'],2,'.',''),
            'created_at' => Carbon::parse($this['created_at'])->toFormattedDateString()
        ];
    }
}
