<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Carbon\CarbonInterval;
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
            'income' => '€ ' . number_format((float) $this['income'], 2, '.', ''),
            // 'total_event_minutes' => number_format((float)$this['total_event_minutes'] / 3600,2,'.','').' h',
            // 'total_instructor_minutes' => number_format((float)$this['total_instructor_minutes'] / 3600,2,'.','').' h',
            'total_event_minutes' => number_format((float) $this['total_event_minutes'] / 60, 2, '.', '') . ' m',
            'total_instructor_minutes' => number_format((float) $this['total_instructor_minutes'] / 60, 2, '.', '') . ' m',
            'percent' => number_format((float) $this['percent'], 2, '.', '') . ' %',
        ];
    }
}
