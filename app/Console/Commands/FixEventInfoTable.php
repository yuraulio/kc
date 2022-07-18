<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Event;
use App\Model\EventInfo;
use App\Model\Delivery;
use App\Model\City;

class FixEventInfoTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eventinfo:fixed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $events = Event::all();
        
        foreach($events as $event){
            $requestData = [];
            if($event->id == 4633){
                continue;
            }

            $delivery =  $event->delivery->first() ? $event->delivery->first()->id : -1;
            $paymentMethod = $event->paymentMethod->first() ? $event->paymentMethod->first()->id : false;
            $status = $event->status;
            $absences =  $event->absences_limit;
            $partner = count($event->partners) > 0;
            $syllabus =$event->syllabus->first();
            $certification_title = $event->certificate_title;
            $cityId = $event->city->first() ? $event->city->first()->id : -1;

            $visible = ['landing' => "on", "home"=> "on", 'list' => "on", "invoice" => "on", "emails" => "on"];
           
            $hours = [
                'icon' => ['path'=> null, 'alt_text' => null],
                'text' => $event->summary1()->where('section','access')->first() ? $event->summary1()->where('section','access')->first()->title : null,
                'visible' => $visible
            ];

            $requestData['hours'] = $hours;

            $language = [
                'icon' => ['path'=> null, 'alt_text' => null],
                'text' => $event->summary1()->where('section','language')->first() ? $event->summary1()->where('section','language')->first()->title : null,
                'visible' => $visible
            ];

            $requestData['language'] = $language;
            
            if($delivery!=143){
                $deliveryy = [
                    'inclass' => [
                         'dates' => [
                             'text' =>$event->summary1()->where('section','date')->first() ? $event->summary1()->where('section','date')->first()->title : null,
                             'icon' => ['path'=> null, 'alt_text' => null],
                         ],
                         'day' => [
                             'text' =>$event->summary1()->where('section','duration')->first() ? $event->summary1()->where('section','duration')->first()->title : null,
                             'icon' => ['path'=> null, 'alt_text' => null],
                         ],
                         'hours' => [
                             'text' =>$event->summary1()->where('section','duration')->first() ? $event->summary1()->where('section','duration')->first()->title : null,
                             'icon' => ['path'=> null, 'alt_text' => null],
                         ]
                    ]
                ];

                $requestData['delivery'] = $deliveryy;
     
            }else{
                
                $deliveryy = [
                    'elearning' => [
                         'expiration' => $event->expiration,
                         'icon' => ['path'=> null, 'alt_text' => null],
                         'text' => '',
                         'visible' => $visible
                    ]
                ];

                $requestData['delivery'] = $deliveryy;
            }
           
            
            if($event->view_tpl == 'elearning_event' || $event->view_tpl == 'event'){
                $payment = [
                
                    "icon" => ["path" => null,"alt_text" => null],
                    'enabled' =>  'on'
                        
                ];
                
                $requestData['payment'] = $payment;
            }
      
            if($event->enroll){
                $freeCourses = [
                    'icon' => ['path'=> null, 'alt_text' => null],
                    "enabled" => 'on',
                    "list" => [
                        2304
                    ]
                ];

                $requestData['free_courses'] = $freeCourses;

            }

            if($partner){
                $partner = [
                    'icon' => ['path'=> null, 'alt_text' => null],
                ];

                $requestData['partner'] = $partner;
            }

            $manager = [
                'icon' => ['path'=> null, 'alt_text' => null],
            ];

            $requestData['manager'] = $manager;
            
            $certificate = [
                'icon' => ['path'=> null, 'alt_text' => null],
                'failure_text' => $event->title,
                "type" => null,
                "visible" => $visible
            ];

            $requestData['certificate'] = $certificate;

            $students = [
                'icon' => ['path'=> null, 'alt_text' => null],
                'count_start' => 1,
                "text" => $event->summary1()->where('section','students')->first() ? $event->summary1()->where('section','students')->first()->title : null,
                "visible" => $visible
            ];

            $requestData['students'] = $students;

            $eventInfo = $this->prepareInfo($requestData, $status, $delivery, $absences, $partner, $syllabus, $certification_title, $cityId);
            $this->updateEventInfo($eventInfo, $event->id);
            //dd($requestData);

        }

        

        return 0;
    }


    public function prepareInfo($requestData, $status, $delivery, $absences, $partner, $syllabus, $certification_title, $cityId)
    {
        $data = [];

        /*switch($status){
            case 0:
                $status = 'Open';
                break;
            case 1:
                $status = 'Close';
                break;
            case 2:
                $status = 'Soldout';
                break;
            case 3:
                $status = 'Completed';
                break;
            case 4:
                $status = 'My Account Only';
                break;
            case 5:
                $status = 'Waiting';
        }*/

        //$delivery = ($delivery = Delivery::find($delivery)) ? $delivery['name'] : null;
        $city = City::find($cityId);

        $data['course_inclass_absences'] = $absences;
        $data['course_status'] = $status;
        $data['course_delivery'] = $delivery;
        $data['course_hours'] = $requestData['hours']['text'];

        $data['course_partner'] = $partner;
        $data['course_manager'] = ($syllabus != null) ? true : false;
        $data['course_certification_name_success'] = $certification_title;
        $data['course_inclass_city'] = ($city) ? $city->name : null;

        if(isset($requestData['hours']['visible'])){

            $visible_loaded_data = $requestData['hours']['visible'];
            $data['course_hours_visible'] = json_encode($this->prepareVisibleData($visible_loaded_data));

        }else{
            $data['course_hours_visible'] = json_encode($this->prepareVisibleData());
        }

        //Hour Icons

        //end hour icon

        // Language
        $data['course_language'] = $requestData['language']['text'];
        if(isset($requestData['language']['visible'])){

            $visible_loaded_data = $requestData['language']['visible'];
            $data['course_language_visible'] = json_encode($this->prepareVisibleData($visible_loaded_data));

        }else{
            $data['course_language_visible'] = json_encode($this->prepareVisibleData());
        }
        //Language Icons

        //end language icon

        if(isset($requestData['delivery']['inclass'])){
            $dates = [];
            $days = [];
            $times = [];


            // Dates
            if(isset($requestData['delivery']['inclass']['dates'])){
                $dates['text'] = $requestData['delivery']['inclass']['dates']['text'];

                if(isset($requestData['delivery']['inclass']['dates']['visible'])){
                    $visible_loaded_data = $requestData['delivery']['inclass']['dates']['visible'];
                    $dates['visible'] = $this->prepareVisibleData($visible_loaded_data);
                }else{
                    $dates['visible'] = $this->prepareVisibleData();
                }
            }
            $data['course_inclass_dates'] = json_encode($dates);

            // Days
            if(isset($requestData['delivery']['inclass']['day'])){
                $days['text'] = $requestData['delivery']['inclass']['day']['text'];

                if(isset($requestData['delivery']['inclass']['day']['visible'])){
                    $visible_loaded_data = $requestData['delivery']['inclass']['day']['visible'];
                    $days['visible'] = $this->prepareVisibleData($visible_loaded_data);
                }else{
                    $days['visible'] = $this->prepareVisibleData();
                }
            }
            $data['course_inclass_days'] = json_encode($days);

            // Times
            if(isset($requestData['delivery']['inclass']['times'])){
                $times['text'] = $requestData['delivery']['inclass']['times']['text'];

                if(isset($requestData['delivery']['inclass']['times']['visible'])){
                    $visible_loaded_data = $requestData['delivery']['inclass']['times']['visible'];
                    $times['visible'] = $this->prepareVisibleData($visible_loaded_data);
                }else{
                    $times['visible'] = $this->prepareVisibleData();
                }
            }
            $data['course_inclass_times'] = json_encode($times);
        }


        // Free E-learning
        if(isset($requestData['free_courses']['list'])){
            $data['course_elearning_access'] = json_encode($requestData['free_courses']['list']);
        }else{
            $data['course_elearning_access'] = null;
        }

        // Payment
        
        if(isset($requestData['payment'])){
            
            /*if(isset($requestData['payment']['paid'])){
                $data['course_payment_method'] = 'paid';
            }else{
                $data['course_payment_method'] = 'free';
            }*/
            $data['course_payment_method'] = 'paid';
        }else{
            $data['course_payment_method'] = 'free';
        }

        // Award
        if(isset($requestData['awards'])){
            $data['course_awards'] = true;
            $data['course_awards_text'] = $requestData['awards']['text'];
        }else{
            $data['course_awards'] = false;
            $data['course_awards_text'] = null;
        }

        // Certificate
        if(isset($requestData['certificate'])){
            $data['course_certification_name_failure'] = $requestData['certificate']['failure_text'];
            $data['course_certification_type'] = $requestData['certificate']['type'];

            if(isset($requestData['certificate']['visible'])){

                $visible_loaded_data = $requestData['certificate']['visible'];
                $data['course_certificate_visible'] = json_encode($this->prepareVisibleData($visible_loaded_data));

            }else{
                $data['course_certificate_visible'] = json_encode($this->prepareVisibleData());
            }
        }

        // Students
        if(isset($requestData['students'])){
            $data['course_students_number'] = $requestData['students']['count_start'];
            $data['course_students_text'] = $requestData['students']['text'];

            if(isset($requestData['students']['visible'])){

                $visible_loaded_data = $requestData['students']['visible'];
                $data['course_students_visible'] = json_encode($this->prepareVisibleData($visible_loaded_data));

            }else{
                $data['course_students_visible'] = json_encode($this->prepareVisibleData());
            }
        }
   
        $visible_loaded_data = isset($requestData['delivery']['elearning']['visible']) ? $requestData['delivery']['elearning']['visible'] : null;
        $data['course_elearning_visible'] = json_encode($this->prepareVisibleData($visible_loaded_data));
        $data['course_elearning_icon'] = isset($requestData['delivery']['elearning']['icon']) ?  json_encode($requestData['delivery']['elearning']['icon']) : null;
        $data['course_elearning_expiration'] = (isset($requestData['delivery']['elearning']['expiration']) && $requestData['delivery']['elearning']['expiration'] != null) ? $requestData['delivery']['elearning']['expiration'] : null;
        $data['course_elearning_text'] = (isset($requestData['delivery']['elearning']['text']) && $requestData['delivery']['elearning']['text'] != null) ? $requestData['delivery']['elearning']['text'] : null;


        return $data;

    }

    public function updateEventInfo($event_info, $event_id)
    {
        //dd($event_info);
        $event = Event::find($event_id);

        //dd($event->paymentMethod);

        $info = $event->event_info1;

        if($info === null){
            $infos = new EventInfo();
            $infos->event_id = $event->id;
        }else{
            $infos = $info;
        }

        $infos->course_inclass_absences = $event_info['course_inclass_absences'];
        $infos->course_status = $event_info['course_status'];
        $infos->course_delivery = $event_info['course_delivery'];
        $infos->course_hours = $event_info['course_hours'];
        $infos->course_language = $event_info['course_language'];
        $infos->course_language_visible = $event_info['course_language_visible'];
        $infos->course_partner = json_encode($event_info['course_partner']);
        $infos->course_manager = $event_info['course_manager'];
        $infos->course_certification_name_success = $event_info['course_certification_name_success'];
        $infos->course_hours_visible = $event_info['course_hours_visible'];
        $infos->course_inclass_city = $event_info['course_inclass_city'];
        $infos->course_inclass_dates = isset($event_info['course_inclass_dates']) ? $event_info['course_inclass_dates'] : null;
        $infos->course_inclass_times = isset($event_info['course_inclass_times']) ? $event_info['course_inclass_times'] : null;
        $infos->course_inclass_days = isset($event_info['course_inclass_days']) ? $event_info['course_inclass_days'] : null;
        //$infos->course_payment_method = (isset($event->paymentMethod) && count($event->paymentMethod) != 0) ? 'paid' : 'free';
        $infos->course_payment_method = (isset($event_info['course_payment_method']) && $event_info['course_payment_method'] == 'paid') ? 'paid' : 'free';
        $infos->course_awards = (isset($event_info['course_awards_text']) && $event_info['course_awards_text'] != "") ? true : false;
        $infos->course_awards_text = $event_info['course_awards_text'];
        $infos->course_certification_name_failure = $event_info['course_certification_name_failure'];
        $infos->course_certification_type = $event_info['course_certification_type'];
        $infos->course_certification_visible = $event_info['course_certificate_visible'];
        $infos->course_students_number = $event_info['course_students_number'];
        $infos->course_students_text = $event_info['course_students_text'];
        $infos->course_students_visible = $event_info['course_students_visible'];
        $infos->course_elearning_access = $event_info['course_elearning_access'];

        $infos->course_elearning_visible = isset($event_info['course_elearning_visible']) ? $event_info['course_elearning_visible'] : null;
        $infos->course_elearning_icon = isset($event_info['course_elearning_icon']) ? $event_info['course_elearning_icon'] : null;
        $infos->course_elearning_expiration = isset($event_info['course_elearning_expiration']) ? $event_info['course_elearning_expiration'] : null;
        $infos->course_elearning_text = isset($event_info['course_elearning_text']) ? $event_info['course_elearning_text'] : null;
        
        if($info === null){
            $infos->save();
        }else{
            $infos->update();
        }

    }

    public function prepareVisibleData($data = false)
    {
        $visible_returned_data = ['landing' => 0, 'home' => 0, 'list' => 0, 'invoice' => 0, 'emails' => 0];

        if(!$data){
            return $visible_returned_data;
        }

        foreach($data as $key => $item){
            if(in_array($item,$data)){
                $visible_returned_data[$key] = 1;
            }
        }

        return $visible_returned_data;
    }

}
