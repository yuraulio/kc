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
                'required', 'min:3',
            ],
            'duration' => [
                'numeric',
            ],

            'event_id' => [
                'required',
            ],
            'examMethods' => [
                'required',
            ],
            'q_limit' => [
                'numeric',
            ],

            'intro_text' => [
                'required', 'min:3',
            ],
            'end_of_time_text' => [
                'required', 'min:3',
            ],
            'success_text' => [
                'required', 'min:3',
            ],
            'failure_text' => [
                'required', 'min:3',
            ],

            'repeat_exam' => [
                'nullable', 'boolean',
            ],
            'repeat_exam_in' => [
                'nullable', 'numeric', 'min:0', 'max:255',
            ],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['repeat_exam' => $this->has('repeat_exam') ? 1 : 0]);
    }
}
