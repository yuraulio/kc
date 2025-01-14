<?php

namespace App\Http\Requests\Api\Media;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditImageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'version'   => ['required', 'string'],
            'id'        => ['nullable', 'numeric'],
            'parent_id' => [
                'sometimes',
                Rule::requiredIf(function () {
                    return request()->input('version') !== 'original';
                }),
                'numeric',
            ],
            'alt_text'  => ['nullable', 'string'],
            'link'      => ['nullable', 'string'],
            'name'      => ['string'],
            'folder_id' => ['numeric', 'exists:cms_folders,id'],
        ];
    }
}
