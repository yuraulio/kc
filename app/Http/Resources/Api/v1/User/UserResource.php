<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\v1\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        try {
            $paymentMethods = $this->paymentMethods();
        } catch (\Exception $exception) {
            $paymentMethods = null;
        }

        return [
            'id'                       => $this->id,
            'slug'                     => $this->slug,
            'firstname'                => $this->firstname,
            'lastname'                 => $this->lastname,
            'email'                    => $this->email,
            'birthday'                 => Carbon::parse($this->birthday)->format('d-m-Y'),
            'last_login'               => $this->last_login,
            'mobile'                   => $this->mobile,
            'notes'                    => $this->notes,
            'city'                     => $this->city,
            'country'                  => $this->country,
            'country_code'             => $this->country_code,
            'job_title'                => $this->job_title,
            'company'                  => $this->company,
            'company_url'              => $this->company_url,
            'biography'                => $this->biography,
            'address'                  => $this->address,
            'address_num'              => $this->address_num,
            'postcode'                 => $this->postcode,
            'stripe_id'                => $this->stripe_id,
            'is_employee'              => $this->is_employee,
            'is_freelancer'            => $this->is_freelancer,
            'will_work_remote'         => $this->will_work_remote,
            'will_work_in_person'      => $this->will_work_in_person,
            'work_experience'          => $this->work_experience,
            'terms'                    => (int)$this->terms,
            'meta_title'               => $this->meta_title,
            'meta_description'         => $this->meta_description,
            'profile_status'           => $this->profile_status,
            'account_status'           => $this->account_status,
            'social_links'             => $this->social_links,
            'updated_at'               => $this->updated_at,
            'created_at'               => $this->created_at,
            'profile_image'            => $this->profile_image,
            'roles'                    => $this->roles,
            'tags'                     => $this->tags,
            'skills'                   => $this->skills,
            'receipt_details'          => json_decode($this->receipt_details ?? '', true) ?? null,
            'invoice_details'          => json_decode($this->invoice_details ?? '', true) ?? null,
            'activities'               => UserActivitiesResource::collection($this->activities),
            'image'                    => $this->image,
            'instructor'               => $this->instructor()->first(),
            'paymentMethods'           => $paymentMethods,
            'concent'                  => json_decode($this->consent ?? '', true) ?? null,
            'career_paths'             => $this->careerPaths,
            'work_cities'              => $this->cities,
            'associated_registrations' => $this->getAssociatedRegistrations(),
        ];
    }
}
