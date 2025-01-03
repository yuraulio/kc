<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\Review;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'   => ['sometimes', 'min:3'],
            'content' => ['sometimes', 'min:3'],
            'rating'  => ['sometimes', 'integer'],
            'tags'    => ['sometimes', 'array'],
            'tags.*'  => ['sometimes', 'integer', 'exists:tags,id'],
        ];
    }
}
