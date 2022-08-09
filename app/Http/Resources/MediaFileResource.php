<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaFileResource extends JsonResource
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
            'name' => $this->name,
            'path' => $this->path,
            'size' => $this->size,
            'url' => $this->url,
            'extension' => $this->extension,
            'full_path' => $this->full_path,
            'subfiles' => $this->whenLoaded('subfiles', MediaFileResource::collection($this->subfiles), []),
            'parrent' => $this->whenLoaded('parrent', $this->parrent, null),
            'siblings' => $this->whenLoaded('siblings', $this->siblings, []),
            'user' => $this->when(
                $this->user,
                function () {
                    return [
                        'firstname' => $this->user->firstname,
                        'lastname' => $this->user->lastname,
                    ];
                }
            ),
            'created_at' => Carbon::parse($this->created_at)->toFormattedDateString(),
            'alt_text' => $this->alt_text,
            'link' => $this->link,
            'pages_count' => $this->pages_count,
            'version' => $this->version,
            'folder_id' => $this->folder_id,
        ];
    }
}
