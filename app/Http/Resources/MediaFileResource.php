<?php

namespace App\Http\Resources;

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
            'subfiles' => $this->whenLoaded('subfiles', $this->subfiles, []),
            'user' => $this->when(
                $this->user,
                function () {
                    return [
                        'firstname' => $this->user->firstname,
                        'lastname' => $this->user->lastname,
                    ];
                }
            ),
            'created_at' => $this->created_at,
            'alt_text' => $this->alt_text,
        ];
    }
}
