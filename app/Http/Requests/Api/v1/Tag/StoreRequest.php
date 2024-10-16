<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\Tag;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3'],
        ];
    }
}
