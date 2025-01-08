<?php

namespace App\Http\Requests\Api\Media;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditImageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'version' => 'required|string',
            'id' => 'nullable|numeric',
            'parent_id' => [
                'sometimes',
                'numeric',
                Rule::requiredIf(function () {
                    return request()->input('version') !== 'original';
                }),
            ],
            'alt_text' => 'nullable|string',
            'link' => 'nullable|string',
            'name' => 'string',
            'folder_id' => 'numeric',
        ];
    }
}
