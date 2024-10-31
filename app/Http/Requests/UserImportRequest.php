<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserImportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'import_file' => 'required|mimes:xlsx,xls|max:2048',
        ];
    }
}
