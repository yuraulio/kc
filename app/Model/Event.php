<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Topic;
use App\Model\Event;
use App\Model\Ticket;
use App\Model\Category;
use App\Model\Type;
use App\Model\City;
use App\Model\Summary;
use App\Model\Delivery;
use App\Model\Section;
use App\Model\Benefit;
use App\Model\Venue;
use App\Model\User;
use App\Model\Testimonial;
use App\Model\Partner;
use App\Model\Lesson;
use App\Model\Faq;
use App\Model\Media;
use App\Model\Instructor;
use App\Traits\SlugTrait;
use App\Traits\MetasTrait;
use App\Traits\BenefitTrait;
use App\Traits\MediaTrait;

class Event extends Model
{
    use HasFactory;
    use SlugTrait;
    use MetasTrait;
    use BenefitTrait;
    use MediaTrait;

    protected $table = 'events';

    protected $fillable = [
        'published', 'release_date_files', 'expiration' ,'status', 'title', 'htmlTitle', 'subtitle', 'header', 'summary', 'body', 'hours','author_id', 'creator_id', 'view_tpl', 'view_counter'
    ];

    public function category()
    {
        return $this->morphToMany(Category::class, 'categoryable')->with('faqs','testimonials','dropbox');
    }

    public function faqs()
    {
        return $this->morphToMany(Faq::class, 'faqable')->with('category')->withPivot('priority')->orderBy('faqables.priority','asc');
    }


    public function type()
    {
        return $this->morphToMany(Type::class, 'typeable');
    }

    public function topic()
    {

        return $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor')->select('topics.*','topic_id')
            ->withPivot('event_id','topic_id','lesson_id','instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority')->orderBy('event_topic_lesson_instructor.priority','asc');
    }


    public function lessons()
    {

        return $this->belongsToMany(Lesson::class,'event_topic_lesson_instructor')->where('status',true)->select('lessons.*','topic_id','event_id', 'lesson_id','instructor_id')
        ->withPivot('event_id','topic_id','lesson_id','instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room','priority')->orderBy('event_topic_lesson_instructor.priority','asc');
    }


    public function instructors()
    {
        return $this->belongsToMany(Instructor::class,'event_topic_lesson_instructor')->with('mediable')->select('instructors.*','lesson_id','instructor_id','event_id')
            ->withPivot('lesson_id','instructor_id')->orderBy('event_topic_lesson_instructor.priority','asc')->with('slugable');
    }


    public function summary1()
    {
        return $this->belongsToMany(Summary::class, 'events_summaryevent', 'event_id', 'summary_event_id')->with('medias');
    }

    public function is_inclass_course()
    {

        if($this->delivery->first() && $this->delivery->first()->id == 139){
            return true;
        }else{
            return false;
        }
    }

    public function is_elearning_course()
    {

        if($this->delivery->first() && $this->delivery->first()->id == 143){
            return true;
        }else{
            return false;
        }
    }

    public function delivery()
    {
        return $this->belongsToMany(Delivery::class, 'event_delivery', 'event_id', 'delivery_id');
    }

    public function ticket()
    {
        return $this->belongsToMany(Ticket::class, 'event_tickets')->withPivot('id','priority', 'price', 'options', 'quantity', 'features')->orderBy('priority');
    }

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'event_user_ticket');
    }

    public function city()
    {
        return $this->belongsToMany(City::class, 'event_city');
    }

    public function career()
    {
        return $this->morphedByMany(Career::class, 'careerpathables');
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'sectiontitles_event', 'event_id', 'section_title_id');
    }



    public function venues()
    {
        return $this->belongsToMany(Venue::class, 'event_venue');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user')->withPivot('expiration');
    }

    public function partners()
    {
        return $this->belongsToMany(Partner::class, 'event_partner');
    }

    public function syllabus()
    {
        return $this->belongsToMany(Instructor::class, 'event_syllabus_manager')->with('mediable','slugable');
    }

    public function paymentMethod()
    {
        return $this->belongsToMany(PaymentMethod::class, 'paymentmethod_event');
    }

    public function medias()
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    public function topicsLessonsInstructors(){

        $topics = [];

        $lessons = $this->lessons->groupBy('topic_id');
        //dd($lessons);
        $sum1 = 0;

        // sum topic duration
        foreach($lessons as $key => $lesson1){



            $sum1 = 0;

            foreach($lesson1 as $key1 => $lesson){
                $sum = 0;

                if($lesson['vimeo_duration'] != null && $lesson['vimeo_duration'] != '0'){

                    $vimeo_duration = explode(' ', $lesson['vimeo_duration']);
                    $hour = 0;
                    $min = 0;
                    $sec = 0;



                    if(count($vimeo_duration) == 3){
                        $string_hour = $vimeo_duration[0];
                        $string_hour = intval(explode('h',$string_hour)[0]);
                        $hour = $string_hour * 3600;

                        $string_min = $vimeo_duration[1];
                        $string_min = intval(explode('m',$string_min)[0]);
                        $min = $string_min * 60;

                        $string_sec = $vimeo_duration[2];
                        $string_sec = intval(explode('s',$string_sec)[0]);
                        $sec = $string_sec;

                        $sum = $hour + $min + $sec;

                    }else if(count($vimeo_duration) == 2){
                        $string_min = $vimeo_duration[0];
                        $string_min = intval(explode('m',$string_min)[0]);
                        $min = $string_min * 60;

                        $string_sec = $vimeo_duration[1];
                        $string_sec = intval(explode('s',$string_sec)[0]);
                        $sec = $string_sec;

                        $sum = $min + $sec;
                    }else if(count($vimeo_duration) == 1){
                        //dd($vimeo_duration);
                        $a = strpos( $vimeo_duration[0], 's');
                        //dd($a);
                        if($a === false ){
                            $sum = 0;
                            if(strpos( $vimeo_duration[0], 'm')){
                                $string_min = $vimeo_duration[0];
                                $string_min = intval(explode('m',$string_min)[0]);
                                $min = $string_min * 60;
                                $sum = $min;
                            }

                        }else if($a !== false ){
                            $string_sec = intval(explode('s',$vimeo_duration[0])[0]);
                            $sec = $string_sec;
                            $sum = $sec;

                        }
                    }

                }

                $sum1 = $sum1 + $sum;
                //var_dump($sum1);
                $data['keys'][$key] = $sum1;

        }


        }


        $instructors = $this->instructors->unique()->groupBy('instructor_id')->toArray();

        foreach($this->topic->unique()->groupBy('topic_id') as $key => $topic){

            foreach($topic as $t){


                $lessonsArray = $lessons[$t->id]->toArray();
                foreach( $lessonsArray as $key => $lesson){
                    if(!$lesson['instructor_id']){
                        unset($lessonsArray[$key]);
                    }
                }
                if(count($lessonsArray)>0){
                    $topics[$t->title]['lessons'] = $lessonsArray;
                }
            }


        }

        $data['topics'] = $topics;
        $data['instructors'] = $instructors;
        foreach($data['topics'] as $key => $topics){
            $topic_id = $topics['lessons'][0]['topic_id'];
            $data['topics'][$key]['topic_duration'] = $data['keys'][$topic_id];

        }

        return $data;
    }

    public function getFaqs(){

        $faqs = [];
        foreach($this->faqs->toArray() as $faq){
            if(!isset($faq['category']['0'])){
                continue;
            }

            $faqs[$faq['category']['0']['name']][] = ['question' =>$faq['title'] , 'answer' => $faq['answer'] ];


        }
       // dd($faqs);
        return $faqs;
    }

    public function getFaqsByCategoryEvent(){

        $faqs = [];

        foreach($this->getFaqs() as $key => $faq){
            if(key_exists($key,$faqs)){
                continue;
            }
            $faqs[$key] = [];
        }

        //dd($faqs);

        if($this->category->first()){
            $categoryFaqs = $this->category->first()->faqs;

            foreach($categoryFaqs as $faq){

                foreach($faq->category as $categoryFaq){
                    $faqs[$categoryFaq['name']][] = array('id' => $faq['id'], 'question' =>  $faq['title']);
                }

            }

        }

        return $faqs;
    }

    public function progress($user)
    {

        $videos = $user->statistic()->wherePivot('event_id',$this['id'])->first()->pivot['videos'];
        $videos = json_decode($videos, true);
        $sum = 0;
        foreach($videos as $video){
            if($video['seen'] == 1){
                $sum++;
            }

        }

        return ($sum/count($videos)) * 100;
    }




}
