<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\v1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCollection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'slug'                => $this->slug,
            'firstname'           => $this->firstname,
            'lastname'            => $this->lastname,
            'email'               => $this->email,
            'birthday'            => $this->birthday,
            'mobile'              => $this->mobile,
            'notes'               => $this->notes,
            'city'                => $this->city,
            'country_code'        => $this->country_code,
            'job_title'           => $this->job_title,
            'company'             => $this->company,
            'company_url'         => $this->company_url,
            'biography'           => $this->biography,
            'address'             => $this->address,
            'address_num'         => $this->address_num,
            'postcode'            => $this->postcode,
            'stripe_id'           => $this->stripe_id,
            'is_employee'         => $this->is_employee,
            'is_freelancer'       => $this->is_freelancer,
            'will_work_remote'    => $this->will_work_remote,
            'will_work_in_person' => $this->will_work_in_person,
            'work_experience'     => $this->work_experience,
            'profile_status'      => $this->profile_status,
            'account_status'      => $this->account_status,
            'social_links'        => $this->social_links,
            'updated_at'          => $this->updated_at,
            'created_at'          => $this->created_at,
            'profile_image'       => $this->profile_image,
            'roles'               => $this->roles,
            'receipt_details'     => $this->receipt_details,
            'invoice_details'     => $this->invoice_details,
        ];
    }
}
