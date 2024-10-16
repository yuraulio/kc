<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'          => ['required', 'min:3'],
            'permissions'   => ['required', 'array'],
            'permissions.*' => ['required', 'boolean'],
            'level'         => [
                'required',
                'integer',
                Rule::unique('roles', 'level')
                    ->whereNot('id', $this->role->id),
            ],
        ];
    }
}
