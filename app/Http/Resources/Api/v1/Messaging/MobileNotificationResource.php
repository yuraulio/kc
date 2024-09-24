<?php

namespace App\Http\Resources\Api\v1\Messaging;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobileNotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'content' => $this->resource->content,
            'color' => $this->resource->color,
            'location' => $this->resource->location,
            'creator_id'=> $this->resource->creator_id,
            'status'=> $this->resource->status,
            'filter_criteria'=> $this->resource->filter_criteria,
            'created_at' => $this->resource->created_at,
            'description' => $this->resource->description,
        ];
    }
}
