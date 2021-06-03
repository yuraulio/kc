<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'published' => [
                'boolean'
            ],
            'status' => [
                'digits:1'
            ],
            'title' => [
                'required', 'min:3'
            ],
            'htmlTitle' => [

            ],
            'subtitle' => [

            ],
            'header' => [

            ],
            'summary' => [

            ],
            'body' => [

            ],
            'hours' => [
                'numeric'
            ],
            'release_date_files' => [
                'date', 'nullable'
            ],
            'view_tpl' => [

            ],
            'image' => [
                'image'
            ]
        ];
    }
}
