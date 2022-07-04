<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventInfo extends Model
{
    use HasFactory;

    protected $table = 'event_info';

    protected $fillable = [
        'event_id',
        'course_status',
        'course_hours',
        'course_hours_visible',
        'course_hours_icon',
        'course_language',
        'course_language_visible',
        'course_language_icon',
        'course_delivery',
        'course_inclass_city',
        'course_inclass_dates',
        'course_inclass_days',
        'course_inclass_times',
        'course_inclass_absences',
        'course_elearning_access',
        'course_elearning_icon',
        'course_payment_method',
        'course_partner',
        'course_manager',
        'course_awards',
        'course_awards_text',
        'course_certification_name_failure',
        'course_certification_type',
        'course_students_number',
        'course_students_text',
        'course_students_visible'
    ];
}
