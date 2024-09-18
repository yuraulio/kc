<?php

namespace App\Http\Resources\Api\v1\Report;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'creator_id'=> $this->resource->creator_id,
            'filter_criteria'=> $this->resource->filter_criteria,
            'created_at' => $this->resource->created_at,
            'description' => $this->resource->description,
        ];
    }
}
