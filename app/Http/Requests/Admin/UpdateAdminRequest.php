<?php

namespace App\Http\Requests\Admin;

use App\Model\Role;
use App\Model\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends FormRequest
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
            'firstname' => ['required', 'min:3'],
            'lastname' => ['required', 'min:3'],
            'email' => [
                'required', 'email', "unique:admins,email,{$this->id}",
            ],
            'password' => [
                $this->route()->user ? 'nullable' : 'confirmed', 'min:6',
            ],
        ];
    }
}
