<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\Review;

use App\Enums\ReviewStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'      => ['sometimes', 'min:3'],
            'content'    => ['sometimes', 'min:3'],
            'rating'     => ['sometimes', 'integer', 'min:1', 'max:5'],
            'tags'       => ['sometimes', 'array'],
            'status'     => ['sometimes', Rule::in(ReviewStatusEnum::values())],
            'tags.*'     => ['sometimes', 'integer', 'exists:tags,id'],
            'visibility' => ['sometimes', 'array'],
        ];
    }
}
