<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\User;

use Illuminate\Foundation\Http\FormRequest;

class FilterActivityRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'date_from'          => ['sometimes', 'date_format:d-m-Y'],
            'date_to'            => ['sometimes', 'date_format:d-m-Y'],
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
