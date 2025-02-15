<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\v1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserActivitiesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'description'  => $this->description,
            'created_at'   => $this->created_at,
            'user'         => UserCollection::make($this->who),
            'entity_type'  => $this->entity_type,
            'entity_title' => $this->entity?->title,
            'entity'       => $this->entity,
        ];
    }
}
