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
            'course_delivery' => 'nullable|array',
            'course_delivery.*' => 'nullable|numeric',
            // dynamic ads
            'headline' => 'nullable|string',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            // bonus course
            'bonus_courses' => 'nullable|array',
            'bonus_courses.offer_bonus_course' => 'required|boolean',
            'bonus_courses.selected_courses' => 'nullable|array',
            'bonus_courses.selected_courses.*' => 'required|numeric',
            'bonus_courses.access_period' => 'nullable|numeric',
            'bonus_courses.exams_required' => 'nullable|boolean',
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
            'course_in_collaboration' => 'required|boolean',
            'selected_partner' => 'nullable|numeric',
            'selected_gateway' => 'nullable|numeric',
            'selected_payment_options' => 'nullable|array',
            'selected_payment_options.*' => 'nullable',
            'selected_payment_options.*.option_id' => 'required|numeric',
            'selected_payment_options.*.active' => 'required|boolean',
            'selected_payment_options.*.installments_allowed' => 'required|boolean',
            'selected_payment_options.*.monthly_installments_limit' => 'nullable|numeric',
            'exams' => 'nullable|array',
            'exams.selected_exam' => 'required|numeric',
            'exams.exam_accessibility_type' => 'required|in:by_period_after,by_progress_percentage',
            'exams.exam_accessibility_value' => 'required|numeric',
            'exams.exam_repeated' => 'required|boolean',
            'exams.exam_repeat_delay' => 'nullable|numeric',
            'exams.whole_amount_should_be_paid' => 'nullable|boolean',
            // audience
            'selected_audiences' => 'nullable|array',
            'selected_audiences.*' => 'nullable|numeric',
            // related courses
            'related_courses' => 'nullable|array',
            'related_courses.*' => 'nullable|numeric',
            // tags
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|numeric',
        ];
    }

    public function toDto(): SettingsDto
    {
        return new SettingsDto($this->validated());
    }
}
