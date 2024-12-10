<?php

namespace App\Http\Requests\Api\v1\Topic;

use Illuminate\Foundation\Http\FormRequest;

class ChangeLessonOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order' => ['required', 'integer'],
        ];
    }
}
