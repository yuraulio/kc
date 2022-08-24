<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $pages = count($this->pages);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'dynamic' => $this->dynamic,
            'rows' => $this->rows,
            'user' => $this->when(
                $this->user,
                function () {
                    return $this->user->firstname . " " . $this->user->lastname;
                }
            ),
            'pages' => $pages > 0 ? '<a href="/pages?templateName=' . $this->title . '&templateID=' . $this->id . '">' . $pages . '</a>' : 0,
            'created_at' => Carbon::parse($this->created_at)->toFormattedDateString(),
            'type' => $this->type,
        ];
    }
}
