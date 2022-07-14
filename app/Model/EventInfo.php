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
        'course_hours_visible',
        'course_hours_icon',
        'course_language',
        'course_language_visible',
        'course_language_icon',
        'course_delivery',
        'course_elearning_expiration',
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
        'course_payment_method',
        'course_payment_icon',
        'course_partner',
        'course_partner_icon',
        'course_manager',
        'course_manager_icon',
        'course_awards',
        'course_awards_text',
        'course_awards_icon',
        'course_certification_name_failure',
        'course_certification_type',
        'course_certification_icon',
        'course_students_number',
        'course_students_text',
        'course_students_visible',
        'course_students_icon',
    ];

    public function formedData()
    {
        $infos = $this;
        $data = [];

        $data['status'] = $infos['course_status'];

        $data['hours']['hour'] = $infos['course_hours'];
        $data['hours']['text'] = $infos['course_hours_text'];
        $data['hours']['icon'] = json_decode($infos['course_hours_icon'],true);
        $data['hours']['visible'] = json_decode($infos['course_hours_visible'], true);

        $data['language']['text'] = $infos['course_language'];
        $data['language']['icon'] = json_decode($infos['course_language_icon'], true);
        $data['language']['visible'] = json_decode($infos['course_language_visible'], true);

        $data['delivery'] = $infos['course_delivery'];

        if($this->event->is_inclass_course()){

            //dd($infos['course_inclass_city']);
            $data['inclass']['city']['text'] = $infos['course_inclass_city'];
            $data['inclass']['city']['icon'] = json_decode($infos['course_inclass_city_icon'], true);

            $data['inclass']['dates'] = json_decode($infos['course_inclass_dates'], true);
            $data['inclass']['days'] = json_decode($infos['course_inclass_days'], true);
            $data['inclass']['times'] = json_decode($infos['course_inclass_times'], true);

        }else if($this->event->is_elearning_course()){

            $data['elearning']['expiration'] = (isset($infos['course_elearning_expiration']) && $infos['course_elearning_expiration'] != null) ? json_decode($infos['course_elearning_expiration'], true) : null;
            $data['elearning']['icon'] = (isset($infos['course_elearning_icon']) && $infos['course_elearning_icon'] != null) ? json_decode($infos['course_elearning_icon'], true) : null;
            $data['elearning']['visible'] = (isset($infos['course_elearning_visible']) && $infos['course_elearning_visible'] != null) ? json_decode($infos['course_elearning_visible'], true) : null;
        }

        $data['certificate']['messages']['success'] = $infos['course_certification_name_success'];
        $data['certificate']['messages']['failure'] = $infos['course_certification_name_failure'];
        $data['certificate']['type'] = $infos['course_certification_type'];
        $data['certificate']['visible'] = json_decode($infos['course_certification_visible'], true);
        $data['certificate']['icon'] = json_decode($infos['course_certification_icon'], true);

        $data['students']['number'] = (int)$infos['course_students_number'];
        $data['students']['text'] = $infos['course_students_text'];
        $data['students']['visible'] = json_decode($infos['course_students_visible'], true);
        $data['students']['icon'] = json_decode($infos['course_students_icon'], true);

        return $data;
    }

    public function event()
    {
        return $this->hasOne(Event::class, 'id','event_id');
    }
}
