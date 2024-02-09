<?php

namespace App\Http\Requests\AdminApi;

use Illuminate\Foundation\Http\FormRequest;

class PagesPriorityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'items' => 'required|array',
            'items.*' => 'required|integer',
        ];
    }
}
