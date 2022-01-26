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
        return [
            'id' => $this->id,
            'title' => $this->title,
            'rows' => $this->rows,
            'user' => $this->when(
                $this->user,
                function () {
                    return $this->user->firstname . " " . $this->user->lastname;
                }
            ),
            'pages' => count($this->pages),
            'created_at' => Carbon::parse($this->created_at)->format("Y-m-d"),
        ];
    }
}
