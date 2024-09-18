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
            'settings.bonus_courses' => 'nullable|array',
            'settings.bonus_courses.*.id' => 'required|numeric',
            'settings.bonus_courses.*.access_period' => 'nullable|numeric',
            'settings.bonus_courses.*.exams_required' => 'nullable|boolean',
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
            'settings.selected_partner' => 'nullable|numeric',
            'settings.selected_gateway' => 'nullable|numeric',
            'settings.selected_payment_options' => 'nullable|array',
            'settings.selected_payment_options.*' => 'nullable',
            'settings.selected_payment_options.*.option_id' => 'required|numeric',
            'settings.selected_payment_options.*.active' => 'required|boolean',
            'settings.selected_payment_options.*.installments_allowed' => 'required|boolean',
            'settings.selected_payment_options.*.monthly_installments_limit' => 'nullable|numeric',
            'settings.exam' => 'nullable|array',
            'settings.exam.selected_exam' => 'required|numeric',
            'settings.exam.exam_accessibility_type' => 'required|in:by_period_after,by_progress_percentage',
            'settings.exam.exam_accessibility_value' => 'required|numeric',
            'settings.exam.exam_repeat_delay' => 'nullable|numeric',
            'settings.exam.whole_amount_should_be_paid' => 'nullable|boolean',
            // audience
            'settings.selected_audiences' => 'nullable|array',
            'settings.selected_audiences.*' => 'nullable|numeric',
            // related courses
            'settings.related_courses' => 'nullable|array',
            'settings.related_courses.*' => 'nullable|numeric',
            // tags
            'settings.tags' => 'nullable|array',
            'settings.tags.*' => 'nullable|numeric',
        ];
    }

    public function toDto(): SettingsDto
    {
        return new SettingsDto($this->validated()['settings']);
    }
}
