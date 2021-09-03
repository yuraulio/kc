<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VenueRequest extends FormRequest
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
            'name' => [
                'required', 'min:3'
            ],
            'address' => [
                'required', 'min:3'
            ],
            'longitude' => [
                'required', 'numeric', 'between:-180,180'
            ],
            'latitude' => [
                'required','numeric','between:-90,90'
            ]
        ];
    }
}
