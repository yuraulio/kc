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
                'required', 'min:3',
            ],
            'short_title' => [

            ],
            'subtitle' => [
                'required', 'min:3',
            ],
            'header' => [

            ],
            'summary' => [

            ],
            'body' => [

            ],
            'ext_url' => [

            ],
            'mobile' => [
                'nullable',
                'numeric',
                'digits:10',
            ],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The first name field is required.',
            'title.min' => 'The first name must be at least 3 characters.',

            'subtitle.required' => 'The last name field is required.',
            'subtitle.min' => 'The last name must be at least 3 characters.',
        ];
    }
}
