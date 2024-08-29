<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'folder_name' => $this->resource->folder_name,
            'folders' => $this->resource->folders,
            'files' => $this->resource->files,
        ];
    }
}
