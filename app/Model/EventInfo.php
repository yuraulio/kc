<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Event;

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
    ];

    public function event()
    {
        return $this->hasOne(Event::class, 'id','event_id');
    }
}
