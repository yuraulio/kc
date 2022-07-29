<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // dd($this->metaData()["meta_image"]->url);
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'content' => $this->content,
            'user' => $this->when(
                $this->user,
                function () {
                    return [
                        'firstname' => $this->user->firstname,
                        'lastname' => $this->user->lastname,
                    ];
                }
            ),
            'published' => $this->published,
            'template' => $this->template,
            'categories' => $this->category(),
            'subcategories' => $this->subcategories,
            'published_from' => $this->published_from,
            'published_to' => $this->published_to,
            'type' => $this->type,
            'slug' => $this->slug,
            'indexed' => $this->indexed,
            'dynamic' => $this->dynamic,
            'created_at' => Carbon::parse($this->created_at)->toFormattedDateString(),
            'meta_image' => $this->when(
                $this->metaData(),
                function () {
                    return $this->metaData()["meta_image"]->url ?? env("PAGE_IMAGE");
                }
            ),
        ];
    }
}
