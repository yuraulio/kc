<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
                    return [
                        'name' => $this->user->firstname . ' ' . $this->user->lastname,
                        'image' => get_profile_image($this->user->image) ?? '/theme/assets/images/icons/user-circle-placeholder.svg',
                    ];
                }
            ),
            'page' => '<a target="_blank" href="' . ($this->page->type == 'Blog' ? '/blog/' : '') . $this->page->slug . '">' . $this->page->title . '</a>',
            'created_at_formated' => Carbon::parse($this->created_at)->format('Y-m-d G:i:s'),
            'created_at' => Carbon::parse($this->created_at)->toFormattedDateString(),
            'diffForHumans' => $this->created_at->diffForHumans(),
        ];
    }
}
