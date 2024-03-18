<?php

namespace App\Http\Requests\Api\Media;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => [
                'required',
                'file' => 'mimes:jpeg,jpg,png,webp,svg',
            ],
            'folder_id' => [
                'required',
            ],
            'parent_id' => 'nullable',
            'alt_text' => 'nullable|string',
            'link' => 'nullable|string',
        ];
    }
}
