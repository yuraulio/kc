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
            'rating'   => ['required', 'integer', 'min:1', 'max:5'],
            'event_id' => ['required', 'exists:events,id'],
            'user_id'  => ['required', 'exists:users,id'],
            'tags'     => ['sometimes', 'array'],
            'tags.*'   => ['sometimes', 'integer', 'exists:tags,id'],
        ];
    }
}
