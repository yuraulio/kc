<?php

namespace App\Http\Requests;

use App\Model\Pages;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PagesRequest extends FormRequest
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
        /*
'title' => [
                'required', 'min:3', Rule::unique((new Pages)->getTable())->ignore($this->route()->page->id ?? null)
            ],
        */

        return [

            'name' => [
                'required', 'min:3'
            ],

            'title' => [
                'required', 'min:3'
            ],
            'content' => [
                'required'
            ]
        ];
    }
}
