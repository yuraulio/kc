<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'company' => ['required', 'min:3'],
            'birthday' => ['required', 'date_format:m/d/Y'],
            'mobile' => 'required|digits:10',
            'address' => ['required', 'min:3'],
            'address_num' => ['required'],
            'postcode' => ['required', 'digits:5'],
            'city' => ['required', 'min:3'],
            'afm' => ['required', 'max:9'],
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => [auth()->id() == 1 ? 'sometimes' : 'required', 'email', Rule::unique((new User)->getTable())->ignore(auth()->id())],
        ];
    }
}
