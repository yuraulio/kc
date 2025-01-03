<?php

namespace App\Http\Requests\Api\v1\Topic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrphansRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'query'              => ['sometimes', 'min:1'],
            'date_from'          => ['sometimes', 'date_format:d-m-Y'],
            'date_to'            => ['sometimes', 'date_format:d-m-Y'],
            'order_by'           => ['sometimes', Rule::in(['id', 'lastname', 'account_status', 'profile_status', 'created_at'])],
            'order_type'         => ['sometimes', Rule::in(['asc', 'desc'])],
            'per_page'           => ['sometimes', 'integer'],
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
