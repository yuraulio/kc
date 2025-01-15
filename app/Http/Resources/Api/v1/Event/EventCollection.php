<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\v1\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventCollection extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'title'              => $this->title,
            'published'          => $this->published,
            'status'             => $this->status,
            'release_date_files' => $this->release_date_files,
            'created_at'         => $this->created_at,
        ];
    }
}
