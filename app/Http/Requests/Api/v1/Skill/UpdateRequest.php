<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\Skill;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3'],
        ];
    }
}
