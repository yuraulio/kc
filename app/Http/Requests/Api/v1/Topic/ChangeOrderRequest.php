<?php

namespace App\Http\Requests\Api\v1\Topic;

use Illuminate\Foundation\Http\FormRequest;

class ChangeOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order' => ['required', 'integer'],
        ];
    }
}
