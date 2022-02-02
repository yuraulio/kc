<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'comment' => $this->comment,
            'user' => $this->when(
                $this->user,
                function () {
                    return $this->user->firstname . " " . $this->user->lastname;
                }
            ),
            'page' => '<a target="_blank" href="/new_page/' . $this->page->id . '">' . $this->page->title . "</a>",
            'created_at_formated' => Carbon::parse($this->created_at)->format("Y-m-d G:i:s"),
            'created_at' => $this->created_at,
        ];
    }
}
