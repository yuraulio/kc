<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\v1\Review;

use App\Http\Resources\Api\v1\Event\EventResource;
use App\Http\Resources\Api\v1\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'title'            => $this->title,
            'content'          => $this->content,
            'rating'           => $this->rating,
            'status'           => $this->status,
            'facebook_post_id' => $this->facebook_post_id,
            'visibility'       => $this->visibility,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
            'user'             => UserResource::make($this->user),
            'event'            => EventResource::make($this->event),
        ];
    }
}
