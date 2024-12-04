<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'                 => ['required', 'min:3'],
            'course_payment_method' => ['required', Rule::in(['free', 'paid'])],
            'status'                => ['sometimes', 'digits:1'],
            'launch_date'           => ['sometimes', 'date_format:d-m-Y'],
            'release_date_files'    => ['sometimes', 'date_format:d-m-Y'],
            'language_id'           => ['sometimes', 'exists:languages,id'],
            'delivery_id'           => ['sometimes', 'exists:deliveries,id'],
            'audience_id'           => ['sometimes', 'exists:audiences,id'],
            'topic_id'              => ['sometimes', 'exists:topics,id'],
            'media_id'              => ['sometimes', 'exists:medias,id'],
        ];
    }
}
