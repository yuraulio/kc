<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterPaymentsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'query'      => ['sometimes', 'min:1'],
            'order_by'   => ['sometimes', Rule::in(['id', 'created_at', 'amount'])],
            'order_type' => ['sometimes', Rule::in(['asc', 'desc'])],
            'per_page'   => ['sometimes', 'integer'],
        ];
    }
}
