<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'title' => $this->title,
            'user' => $this->when(
                $this->user,
                function () {
                    return [
                        'firstname' => $this->user->firstname,
                        'lastname' => $this->user->lastname,
                        'id' => $this->user->id,
                    ];
                }
            ),
            // 'pages' => $this->pages,
            'pages_count' => $this->pagesCount,
            'subcategories' => $this->subcategories,
            'created_at' => Carbon::parse($this->created_at)->toFormattedDateString(),
            'category_image' => $this->image,
        ];
    }
}
