<?php

namespace App\Http\Resources\Api\v1\Event\Settings\Participants;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CTAResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'course_page' => $this->resource['course_page'] ?? null,
            'course_page_re_enroll' => $this->resource['course_page_re_enroll'] ?? null,
            'home_page' => $this->resource['home_page'] ?? null,
            'lists' => $this->resource['lists'] ?? null,
            'is_price_visible_on_button' => $this->resource['is_price_visible_on_button'] ?? false,
            'is_discount_price_visible' => $this->resource['is_discount_price_visible'] ?? false,
        ];
    }
}
