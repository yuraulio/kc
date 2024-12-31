<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\User;

use Illuminate\Foundation\Http\FormRequest;

class DeleteCourseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tickets'   => ['sometimes', 'array'],
            'tickets.*' => ['sometimes', 'exists:tickets,id'],
        ];
    }
}
