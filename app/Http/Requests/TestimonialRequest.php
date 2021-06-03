<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
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
            'name' => [
                'required', 'min:3'
            ],
            'lastname' => [
                'required', 'min:3'
            ],
            'status' => [
                'numeric'
            ],
            'testimonial' => [
                'required', 'min:3'
            ],
            'facebook' => [
                'nullable', 'url'
            ],
            'linkedin' => [
                'nullable', 'url'
            ],
            'youtube' => [
                'nullable', 'url'
            ],
        ];
    }
}
