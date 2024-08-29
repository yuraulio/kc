<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DynamicAdvertisingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            $this->attributes([
                'id',
                'headline',
                'short_description',
                'long_description',
            ]),
        ];
    }
}
