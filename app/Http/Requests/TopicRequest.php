<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Model\Event;
use Illuminate\Validation\Rule;

class TopicRequest extends FormRequest
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
            'comment_status' => [

            ],
            'title' => [
                'required', 'min:3'
            ],
            'short_title' => [

            ],
            'subtitle' => [

            ],
            'header' => [

            ],
            'summary' => [

            ],
            'body' => [

            ]
        ];
    }
}
