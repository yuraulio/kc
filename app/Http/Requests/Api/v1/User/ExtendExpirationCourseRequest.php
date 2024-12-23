<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\User;

use Illuminate\Foundation\Http\FormRequest;

class ExtendExpirationCourseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'expiration' => ['required', 'date_format:d-m-Y'],
        ];
    }
}
