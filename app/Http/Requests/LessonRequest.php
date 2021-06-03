<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
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
            'status' => [
                'numeric'
            ],
            'htmlTitle' => [
                'required', 'min:3'
            ],
            'title' => [
                'required', 'min:3'
            ],
            'subtitle' => [

            ],
            'header' => [

            ],
            'summary' => [

            ],
            'body' => [

            ],
            'vimeo_video' => [

            ],
            'vimeo_duration' => [

            ]
        ];
    }
}
