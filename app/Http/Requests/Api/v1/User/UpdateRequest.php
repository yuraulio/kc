<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\User;

use App\Enums\AccountStatusEnum;
use App\Enums\ProfileStatusEnum;
use App\Enums\WorkExperience;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'slug'                => ['sometimes', 'min:3'],
            'firstname'           => ['sometimes', 'min:3'],
            'lastname'            => ['sometimes', 'min:3'],
            'email'               => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')
                    ->whereNot('id', $this->user->id),
            ],
            'password'            => ['sometimes', 'confirmed', 'min:6'],
            'birthday'            => ['sometimes', 'date_format:d-m-Y'],
            'mobile'              => ['sometimes', 'regex:/^\+?\d+$/'],
            'notes'               => ['sometimes'],
            'city'                => ['sometimes'],
            'country'             => ['sometimes'],
            'country_code'        => ['sometimes', 'integer'],
            'job_title'           => ['sometimes'],
            'company'             => ['sometimes'],
            'company_url'         => ['sometimes'],
            'biography'           => ['sometimes', 'max:255'],
            'social_links'        => ['sometimes', 'array'],
            'social_links.*'      => ['sometimes', 'string'],
            'profile_status'      => ['sometimes', Rule::in(ProfileStatusEnum::values())],
            'account_status'      => ['sometimes', Rule::in(AccountStatusEnum::values())],
            'address'             => ['sometimes'],
            'address_num'         => ['sometimes'],
            'postcode'            => ['sometimes', 'integer'],
            'is_employee'         => ['sometimes', 'boolean'],
            'is_freelancer'       => ['sometimes', 'boolean'],
            'will_work_remote'    => ['sometimes', 'boolean'],
            'will_work_in_person' => ['sometimes', 'boolean'],
            'work_experience'     => ['sometimes', Rule::in(WorkExperience::values())],
            'skills'              => ['sometimes', 'array'],
            'skills.*'            => ['sometimes', 'array'],
            'skills.*.id'         => ['sometimes', 'integer', 'exists:skills,id'],
            'roles'               => ['sometimes', 'array'],
            'roles.*'             => ['sometimes', 'array'],
            'roles.*.id'          => ['sometimes', 'integer', Rule::exists('roles', 'id')],
            'tags'                => ['sometimes', 'array'],
            'tags.*'              => ['sometimes', 'array'],
            'tags.*.id'           => ['sometimes', 'integer', 'exists:tags,id'],

            'receipt_details'                => ['sometimes', 'array'],
            'receipt_details.billing'        => ['sometimes', 'boolean'],
            'receipt_details.billname'       => ['sometimes', 'string'],
            'receipt_details.billsurname'    => ['sometimes', 'string'],
            'receipt_details.billaddress'    => ['sometimes', 'string'],
            'receipt_details.billaddressnum' => ['sometimes', 'string'],
            'receipt_details.billpostcode'   => ['sometimes', 'integer'],
            'receipt_details.billcity'       => ['sometimes', 'string'],
            'receipt_details.billstate'      => ['sometimes', 'string'],
            'receipt_details.billcountry'    => ['sometimes', 'string'],
            'receipt_details.billemail'      => ['sometimes', 'email'],
            'receipt_details.billafm'        => ['sometimes', 'string'],
            'receipt_details.billmobile'     => ['sometimes', 'string'],

            'invoice_details'                   => ['sometimes', 'array'],
            'invoice_details.companyname'       => ['sometimes', 'string'],
            'invoice_details.companyprofession' => ['sometimes', 'string'],
            'invoice_details.companyafm'        => ['sometimes', 'string'],
            'invoice_details.companydoy'        => ['sometimes', 'string'],
            'invoice_details.companyaddress'    => ['sometimes', 'string'],
            'invoice_details.companyaddressnum' => ['sometimes', 'string'],
            'invoice_details.companypostcode'   => ['sometimes', 'integer'],
            'invoice_details.companycity'       => ['sometimes', 'string'],
        ];
    }
}
