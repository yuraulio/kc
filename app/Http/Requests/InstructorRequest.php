<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstructorRequest extends FormRequest
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
            'title' => [
                'required', 'min:3'
            ],
            'short_title' => [

            ],
            'subtitle' => [
                'required', 'min:3'
            ],
            'header' => [

            ],
            'summary' => [

            ],
            'body' => [

            ],
            'ext_url' => [

            ]
        ];
    }
}
