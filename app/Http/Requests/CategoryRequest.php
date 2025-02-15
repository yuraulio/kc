<?php

namespace App\Http\Requests;

use App\Model\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
            'name' => [
                'required', 'min:3', Rule::unique((new Category)->getTable())->ignore($this->route()->category->id ?? null),
            ],
            'description' => [
                'nullable', 'min:5',
            ],
        ];
    }
}
