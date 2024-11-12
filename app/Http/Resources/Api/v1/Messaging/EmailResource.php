<?php

namespace App\Http\Resources\Api\v1\Messaging;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'predefined_trigger'=> $this->resource->predefined_trigger,
            'template' => $this->resource->template,
            'creator_id'=> $this->resource->creator_id,
            'status'=> $this->resource->status,
            'created_at' => $this->resource->created_at,
            'description' => $this->resource->description,
        ];
    }
}
