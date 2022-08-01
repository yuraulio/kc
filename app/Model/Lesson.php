<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Topic;
use App\Model\Type;
use App\Model\Slug;
use App\Model\Category;

class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lessons';

    protected $fillable = [
        'priority', 'status', 'links' ,'title', 'htmlTitle', 'subtitle', 'header', 'summary', 'body', 'vimeo_video', 'vimeo_duration','author_id', 'creator_id','bold',
    ];

    public function topic()
    {
        return $this->belongsToMany(Topic::class, 'categories_topics_lesson')->withPivot('priority')->orderBy('priority','asc');
    }

    public function category()
    {
        return $this->belongsToMany(Category::class, 'categories_topics_lesson');
    }



    public function type()
    {
        return $this->morphToMany(Type::class, 'typeable');
    }

    public function instructor()
    {
        return $this->belongsToMany(Instructor::class, 'event_topic_lesson_instructor')->select('instructors.*')->with('medias','slugable');

    }

    public function event()
    {
        return $this->belongsToMany(Event::class, 'event_topic_lesson_instructor');

    }

    public function get_instructor($id)
    {
        $instructor = Instructor::find($id);
        return $instructor;
    }

    public function deletee(){
        $this->event()->detach();
        $this->topic()->detach();
        $this->delete();
    }

    /*public function getLessonDurationToSec(){
        
        if(!$this->vimeo_duration){
            return 0;
        }

        $totalDuration = 0;
        
        $duration = explode(" ",$this->vimeo_duration);   
        if(count($duration) == 2){
           
            $seconds = (float)preg_replace('/[^0-9.]+/', '', $duration[0]) * 60;
            $seconds += (float)preg_replace('/[^0-9.]+/', '', $duration[1]);

            $totalDuration += $seconds;

        }else{
            $isMinutes = strpos($duration[0], '');

            if(!$isMinutes){
                $seconds = (float)preg_replace('/[^0-9.]+/', '', $duration[0]);
                $totalDuration += $seconds;
            }else{

                dd($duration[0]);

                $seconds = (float)preg_replace('/[^0-9.]+/', '', $duration[0]) * 60;
                $totalDuration += $seconds;
            }

           

        }

        return $totalDuration;

    }*/
}
