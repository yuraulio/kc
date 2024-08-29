<?php

namespace App\Model;

use App\Model\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property $course_payment_method
 * @property $has_certificate
 * @property $has_certificate_exam
 * @property null|int $language_id
 * @property Language|null $language
 * @property int $course_delivery
 * @property Delivery $delivery
 * @property string|null $course_certification_completion
 * @property string|null $diploma_title
 * @property string|null $cta_course_page
 * @property string|null $cta_course_page_re_enroll
 * @property string|null $cta_home_page
 * @property string|null $cta_lists
 * @property bool $cta_price_visible_on_button
 * @property bool $cta_discount_price_visible
 * @property string|null $course_satisfaction_url
 * @property string|null $instructors_url
 * @property int|null $send_after_days
 */
class EventInfo extends Model
{
    use HasFactory;

    protected $table = 'event_info';

    protected $fillable = [
        'event_id',
        'course_status',
        'course_hours',
        'course_hours_text',
        'course_hours_title',
        'course_hours_visible',
        'course_hours_icon',
        'course_language',
        'course_language_title',
        'language_id',
        'course_language_visible',
        'course_language_icon',
        'course_delivery',
        'course_delivery_icon',
        'course_delivery_title',
        'course_delivery_text',
        'course_delivery_visible',
        'course_elearning_expiration',
        'course_elearning_expiration_title',
        'course_elearning_text',
        'course_elearning_visible',
        'course_elearning_icon',
        'course_inclass_city',
        'course_inclass_city_icon',
        'course_inclass_dates',
        'course_inclass_days',
        'course_inclass_times',
        'course_inclass_absences',
        'course_elearning_access',
        'course_elearning_access_icon',
        'course_elearning_exam',
        'course_elearning_exam_title',
        'course_elearning_exam_icon',
        'course_elearning_exam_visible',
        'course_elearning_exam_activate_months',
        'course_elearning_exam_activate_months_icon',
        'course_payment_method',
        'course_payment_icon',
        'course_payment_installments',
        'course_files_icon',
        'course_partner',
        'course_partner_text',
        'course_partner_icon',
        'course_partner_visible',
        'course_manager',
        'course_manager_icon',
        'course_awards',
        'course_awards_text',
        'course_awards_icon',
        'course_certification_name_failure',
        'course_certification_title',
        'course_certification_text',
        'course_certification_completion',
        'diploma_title',
        'course_certification_attendance_title',
        'course_certification_type',
        'course_certification_icon',
        'has_certificate',
        'has_certificate_exam',
        'course_students_number',
        'course_students_title',
        'course_students_text',
        'course_students_visible',
        'course_students_icon',
        'cta_course_page',
        'cta_course_page_re_enroll',
        'cta_home_page',
        'cta_lists',
        'cta_price_visible_on_button',
        'cta_discount_price_visible',
    ];

    protected $casts = [
        'course_hours_icon' => 'array',
        'course_files_icon' => 'array',
        'course_payment_icon' => 'array',
        'course_language_icon' => 'array',
        'course_delivery_icon' => 'array',
        'course_elearning_icon' => 'array',
        'course_partner_icon' => 'array',
        'course_manager_icon' => 'array',
        'course_awards_icon' => 'array',
        'course_students_icon' => 'array',
        'course_elearning_access_icon' => 'array',
        'course_elearning_exam_icon' => 'array',
        'course_certification_icon' => 'array',
        'course_inclass_city_icon' => 'array',
        'course_language_visible' => 'array',
        'course_delivery_visible' => 'array',
        'course_elearning_visible' => 'array',
        'course_elearning_exam_visible' => 'array',
        'course_partner_visible' => 'array',
        'course_certification_visible' => 'array',
        'course_students_visible' => 'array',
        'course_hours_visible' => 'array',
        'course_inclass_dates' => 'array',
        'course_inclass_days' => 'array',
        'course_inclass_times' => 'array',
        'cta_price_visible_on_button' => 'bool',
        'cta_discount_price_visible' => 'bool',
    ];

    public function event(): HasOne
    {
        return $this->hasOne(Event::class, 'id', 'event_id');
    }

    public function language(): HasOne
    {
        return $this->hasOne(Language::class, 'id', 'language_id');
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class, 'id', 'course_delivery');
    }
}
