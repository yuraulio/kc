<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamRequest extends FormRequest
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
            'exam_name' => [
                'required', 'min:3'
            ],
            'duration' => [
                'numeric'
            ],
            'date' => [
                'date_format:"DD/MM/YYYY H:i"'
            ],
            'event_id' => [
                'required'
            ],
            'examMethods' => [
                'required'
            ],
            'q_limit' => [
                'numeric'
            ],
            'examCheckbox' => [
                'required', 'min:3'
            ],
            'intro_text' => [
                'required', 'min:3'
            ],
            'end_of_time_text' => [
                'required', 'min:3'
            ],
            'success_text' => [
                'required', 'min:3'
            ],
            'failure_text' => [
                'required', 'min:3'
            ],

        ];
    }
}
