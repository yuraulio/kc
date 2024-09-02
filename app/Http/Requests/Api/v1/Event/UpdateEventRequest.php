<?php

namespace App\Http\Requests\Api\v1\Event;

use App\Contracts\Api\v1\Dto\IDtoRequest;
use App\Dto\Api\v1\Event\Participants\SettingsDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest implements IDtoRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'settings' => 'required|array',
            // absences
            'settings.limit' => 'nullable|numeric',
            'settings.starting_hours' => 'nullable|numeric',
            // files access
            'settings.access_duration' => 'nullable|numeric',
            'settings.files_access_till' => 'nullable|date',
            // event
            'settings.admin_title' => 'nullable|string',
            'settings.slug' => 'nullable|string',
            'settings.support_group' => 'nullable|string',
            // event info
            'settings.completion_title' => 'nullable|string',
            'settings.diploma_title' => 'nullable|string',
            'settings.selected_language' => 'nullable|numeric',
            'settings.course_satisfaction_url' => 'nullable|string',
            'settings.instructors_url' => 'nullable|string',
            'settings.send_after_days' => 'nullable|numeric',
            // cta
            'settings.course_page' => 'nullable|string',
            'settings.course_page_re_enroll' => 'nullable|string',
            'settings.home_page' => 'nullable|string',
            'settings.lists' => 'nullable|string',
            'settings.is_price_visible_on_button' => 'nullable|boolean',
            'settings.is_discount_price_visible' => 'nullable|boolean',
            // delivery type
            'settings.course_delivery' => 'nullable|in:classroom,video,virtual_class,corporate_training',
            // dynamic ads
            'settings.headline' => 'nullable|string',
            'settings.short_description' => 'nullable|string',
            'settings.long_description' => 'nullable|string',
            // bonus course
            'settings.selected_course' => 'nullable|array',
            'settings.selected_course.*' => 'nullable|numeric',
            'settings.exams_required' => 'nullable|boolean',
            // files
            'settings.attached_files'  => 'nullable|array',
            // meta
            'settings.meta_title' => 'nullable|string',
            'settings.meta_description' => 'nullable|string',
            // other
            'settings.selected_skills' => 'nullable|array',
            'settings.selected_skills.*' => 'nullable|numeric',
            'settings.selected_paths' => 'nullable|array',
            'settings.selected_paths.*' => 'nullable|numeric',
            'settings.course_city' => 'nullable|array',
            'settings.course_city.*' => 'nullable|numeric',
            'settings.selected_partner' => 'nullable|array',
            'settings.selected_partner.*' => 'nullable|numeric',
            'settings.selected_gateway' => 'nullable|array',
            'settings.selected_gateway.*' => 'nullable|numeric',
            'settings.selected_payment_options' => 'nullable|array',
            'settings.selected_payment_options.*' => 'nullable|numeric',
            'settings.selected_exams' => 'nullable|array',
            'settings.selected_exams.*' => 'nullable|numeric',
        ];
    }

    public function toDto(): SettingsDto
    {
        return new SettingsDto($this->validated()['settings']);
    }
}
