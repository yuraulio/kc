<?php

namespace App\Http\Requests\Api\v1\Event\Participants;

use App\Contracts\Api\v1\Dto\IDtoRequest;
use App\Dto\Api\v1\Event\Participants\SettingsDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest implements IDtoRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // absences
            'limit' => 'nullable|numeric',
            'starting_hours' => 'nullable|numeric',
            // files access
            'access_duration' => 'nullable|numeric',
            'files_access_till' => 'nullable|date',
            // event
            'admin_title' => 'nullable|string',
            'slug' => 'nullable|string',
            'support_group' => 'nullable|string',
            // event info
            'completion_title' => 'nullable|string',
            'diploma_title' => 'nullable|string',
            'selected_language' => 'nullable|numeric',
            'course_satisfaction_url' => 'nullable|string',
            'instructors_url' => 'nullable|string',
            'send_after_days' => 'nullable|numeric',
            // cta
            'course_page' => 'nullable|string',
            'course_page_re_enroll' => 'nullable|string',
            'home_page' => 'nullable|string',
            'lists' => 'nullable|string',
            'is_price_visible_on_button' => 'nullable|boolean',
            'is_discount_price_visible' => 'nullable|boolean',
            // delivery type
            'course_delivery' => 'nullable|in:classroom,video,virtual_class,corporate_training',
            // dynamic ads
            'headline' => 'nullable|string',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            // bonus course
            'selected_course' => 'nullable|array',
            'selected_course.*' => 'nullable|numeric',
            'exams_required' => 'nullable|boolean',
            // files
            'attached_files'  => 'nullable|array',
            // meta
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            // other
            'selected_skills' => 'nullable|array',
            'selected_skills.*' => 'nullable|numeric',
            'selected_paths' => 'nullable|array',
            'selected_paths.*' => 'nullable|numeric',
            'course_city' => 'nullable|array',
            'course_city.*' => 'nullable|numeric',
            'selected_partner' => 'nullable|array',
            'selected_partner.*' => 'nullable|numeric',
            'selected_gateway' => 'nullable|array',
            'selected_gateway.*' => 'nullable|numeric',
            'selected_payment_options' => 'nullable|array',
            'selected_payment_options.*' => 'nullable|numeric',
            'selected_exams' => 'nullable|array',
            'selected_exams.*' => 'nullable|numeric',
        ];
    }

    public function toDto(): SettingsDto
    {
        return new SettingsDto($this->validated());
    }
}
