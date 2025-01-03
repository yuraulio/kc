<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\LessonCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'        => ['required', 'min:3'],
            'description' => ['required'],
        ];
    }
}
