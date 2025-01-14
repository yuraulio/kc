<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterCourseRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'date_from'  => ['sometimes', 'date_format:d-m-Y'],
            'date_to'    => ['sometimes', 'date_format:d-m-Y'],
            'query'      => ['sometimes', 'min:1'],
            'order_by'   => ['sometimes', Rule::in(['id', 'title', 'event_delivery.delivery_id'])],
            'order_type' => ['sometimes', Rule::in(['asc', 'desc'])],
            'per_page'   => ['sometimes', 'integer'],
        ];

        if ($this->filled('from') && $this->filled('to')) {
            $rules['date_to'] = 'after_or_equal:date_from';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'to.after_or_equal' => 'Date to have to be after date from',
        ];
    }
}
