<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\User;

use Illuminate\Foundation\Http\FormRequest;

class MoveCourseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'event_id' => ['required', 'exists:events,id'],
        ];
    }
}
