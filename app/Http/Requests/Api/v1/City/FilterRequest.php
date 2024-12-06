<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\City;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'country_id' => ['sometimes', 'exists:countries,id'],
            'query'      => ['sometimes', 'min:1'],
            'order_by'   => ['sometimes', Rule::in(['name', 'id', 'created_at'])],
            'order_type' => ['sometimes', Rule::in(['asc', 'desc'])],
            'per_page'   => ['sometimes', 'integer'],
        ];
    }
}
