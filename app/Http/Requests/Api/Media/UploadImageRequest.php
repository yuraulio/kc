<?php

namespace App\Http\Requests\Api\Media;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file'             => ['required', 'file' => 'mimes:jpeg,jpg,png,webp,svg',],
            'folder_id'        => ['required', 'exists:cms_folders,id'],
            'parent_id'        => ['nullable'],
            'alt_text'         => ['nullable', 'string'],
            'link'             => ['nullable', 'string'],
            'admin_label'      => ['sometimes'],
            'crop_data'        => ['sometimes', 'array'],
            'crop_data.height' => ['required', 'numeric', 'min:1'],
            'crop_data.width'  => ['required', 'numeric', 'min:1'],
            'crop_data.top'    => ['sometimes', 'numeric', 'min:0'],
            'crop_data.left'   => ['sometimes', 'numeric', 'min:0'],
            'height_ratio'     => ['sometimes', 'required_with:crop_data.height', 'numeric', 'min:1'],
            'width_ratio'      => ['sometimes', 'required_with:crop_data.width', 'numeric', 'min:1'],
        ];
    }
}
