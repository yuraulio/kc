<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\Review;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'    => ['required', 'min:3'],
            'content'  => ['required', 'min:3'],
            'rating'   => ['required', 'integer'],
            'event_id' => ['required', 'exists:events,id'],
        ];
    }
}
