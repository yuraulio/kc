<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1\Event;

use App\Contracts\Api\v1\Dto\IDtoRequest;
use App\Dto\Api\v1\Event\Participants\SettingsDto;
use App\Enums\EventStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest implements IDtoRequest
{
    public function rules(): array
    {
        return [
            'launch_date' => ['sometimes', 'date_format:d-m-Y'],
            'status'      => ['sometimes', Rule::in(EventStatusEnum::values())],
            'published'   => ['sometimes', 'boolean'],
            'promoted'    => ['sometimes', 'boolean'],
            'index'       => ['sometimes', 'boolean'],

            'title'      => ['sometimes'],
            'subtitle'   => ['sometimes'],
            'body'       => ['sometimes'],
            'video_url'  => ['sometimes'],
            'creators'   => ['sometimes', 'array'],
            'creators.*' => ['sometimes', 'integer', 'exists:users,id'],

            'summary'    => ['sometimes'],
            'is_summary' => ['sometimes', 'boolean'],

            'language_id' => ['sometimes', 'exists:languages,id'],
            'delivery_id' => ['sometimes', 'exists:deliveries,id'],
            'audience_id' => ['sometimes', 'exists:audiences,id'],
            'topic_id'    => ['sometimes', 'exists:topics,id'],

            'summary_title'    => ['sometimes'],
            'summary_visible'  => ['sometimes', 'boolean'],
            'topics_title'     => ['sometimes'],
            'topics_text'      => ['sometimes'],
            'topics_visible'   => ['sometimes', 'boolean'],
            'location_title'   => ['sometimes'],
            'location_text'    => ['sometimes'],
            'location_visible' => ['sometimes', 'boolean'],
            'ticket_title'     => ['sometimes'],
            'ticket_text'      => ['sometimes'],
            'ticket_visible'   => ['sometimes', 'boolean'],
            'review_title'     => ['sometimes'],
            'review_text'      => ['sometimes'],
            'review_visible'   => ['sometimes', 'boolean'],

            'feed'            => ['sometimes', 'boolean'],
            'syllabus'        => ['sometimes', 'exists:instructors,id'],
            'htmlTitle'       => ['sometimes'],
            'header'          => ['sometimes'],
            'hours'           => ['sometimes', 'integer'],
            'view_tpl'        => ['sometimes'],
            'image'           => ['sometimes', 'image'],
            'category_id'     => ['sometimes', 'exists:categories,id'],
            'city_id'         => ['sometimes', 'exists:cities,id'],
            'type_id'         => ['sometimes', 'exists:types,id'],
            'partner_enabled' => ['sometimes', 'boolean'],
            'partners'        => ['sometimes', 'array'],
            'partners.*'      => ['sometimes', 'exists:partners,id'],
            'course'          => ['sometimes'],
        ];
    }

    public function toDto(): SettingsDto
    {
        return new SettingsDto($this->validated()['settings']);
    }

    public function settingsRules(): array
    {
        return [
            'settings'                   => 'required|array',
            'settings.limit'             => 'nullable|numeric',
            'settings.starting_hours'    => 'nullable|numeric',
            'settings.access_duration'   => 'nullable|numeric',
            'settings.files_access_till' => 'nullable|date',
            'settings.admin_title'       => 'nullable|string',
            'settings.slug'              => 'nullable|string',
            'settings.support_group'     => 'nullable|string',

            'settings.completion_title'        => 'nullable|string',
            'settings.diploma_title'           => 'nullable|string',
            'settings.selected_language'       => 'nullable|numeric',
            'settings.course_satisfaction_url' => 'nullable|string',
            'settings.instructors_url'         => 'nullable|string',
            'settings.send_after_days'         => 'nullable|numeric',

            'settings.course_page'                => 'nullable|string',
            'settings.course_page_re_enroll'      => 'nullable|string',
            'settings.home_page'                  => 'nullable|string',
            'settings.lists'                      => 'nullable|string',
            'settings.is_price_visible_on_button' => 'nullable|boolean',
            'settings.is_discount_price_visible'  => 'nullable|boolean',

            'settings.course_delivery'   => 'nullable|array',
            'settings.course_delivery.*' => 'nullable|numeric',

            'settings.headline'          => 'nullable|string',
            'settings.short_description' => 'nullable|string',
            'settings.long_description'  => 'nullable|string',

            'settings.bonus_courses'                    => 'nullable|array',
            'settings.bonus_courses.offer_bonus_course' => 'required|boolean',
            'settings.bonus_courses.selected_courses'   => 'nullable|array',
            'settings.bonus_courses.selected_courses.*' => 'required|numeric',
            'settings.bonus_courses.access_period'      => 'nullable|numeric',
            'settings.bonus_courses.exams_required'     => 'nullable|boolean',

            'settings.attached_files' => 'nullable|array',

            'settings.meta_title'       => 'nullable|string',
            'settings.meta_description' => 'nullable|string',

            'settings.selected_skills'         => 'nullable|array',
            'settings.selected_skills.*'       => 'nullable|numeric',
            'settings.selected_paths'          => 'nullable|array',
            'settings.selected_paths.*'        => 'nullable|numeric',
            'settings.course_city'             => 'nullable|array',
            'settings.course_city.*'           => 'nullable|numeric',
            'settings.course_in_collaboration' => 'required|boolean',
            'settings.selected_partner'        => 'nullable|numeric',
            'settings.selected_gateway'        => 'nullable|numeric',

            'settings.selected_payment_options'                              => 'nullable|array',
            'settings.selected_payment_options.*'                            => 'nullable',
            'settings.selected_payment_options.*.id'                         => 'required|numeric',
            'settings.selected_payment_options.*.active'                     => 'required|boolean',
            'settings.selected_payment_options.*.installments_allowed'       => 'required|boolean',
            'settings.selected_payment_options.*.monthly_installments_limit' => 'nullable|numeric',

            'settings.exams'                             => 'nullable|array',
            'settings.exams.selected_exam'               => 'required|numeric',
            'settings.exams.exam_accessibility_type'     => 'required|in:by_period_after,by_progress_percentage',
            'settings.exams.exam_accessibility_value'    => 'required|numeric',
            'settings.exams.exam_repeated'               => 'required|boolean',
            'settings.exams.exam_repeat_delay'           => 'nullable|numeric',
            'settings.exams.whole_amount_should_be_paid' => 'nullable|boolean',

            'settings.selected_audiences'   => 'nullable|array',
            'settings.selected_audiences.*' => 'nullable|numeric',

            'settings.related_courses'   => 'nullable|array',
            'settings.related_courses.*' => 'nullable|numeric',

            'settings.tags'   => 'nullable|array',
            'settings.tags.*' => 'nullable|numeric',
        ];
    }
}
