<?php
/*

=========================================================
* Argon Dashboard PRO - v1.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard-pro-laravel
* Copyright 2018 Creative Tim (https://www.creative-tim.com) & UPDIVISION (https://www.updivision.com)

* Coded by www.creative-tim.com & www.updivision.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

*/

namespace App\Model;

use App\Model\Admin\Countdown;
use App\Model\Dropbox;
use App\Model\Event;
use App\Model\Faq;
use App\Model\Lesson;
use App\Model\Slug;
use App\Model\Testimonial;
use App\Model\Topic;
use App\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use SlugTrait;
    protected $table = 'categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'parent', 'hours', 'show_homepage', 'priority'];

    /**
     * Get all of the events that are assigned this tag.
     */
    public function events()
    {
        return $this->morphedByMany(Event::class, 'categoryable');
    }

    /**
     * Get all of the events that are assigned this tag.
     */
    public function eventsWithUsers()
    {
        return $this->morphedByMany(Event::class, 'categoryable')->with('users');
    }

    /**
     * Get all of the topics that are assigned this tag.
     */
    public function topics()
    {
        return $this->morphedByMany(Topic::class, 'categoryable')->with('lessonsCategory')->withPivot('priority')->orderBy('categoryables.priority', 'asc');
    }

    public function menu()
    {
        return $this->morphedByMany(Menu::class, 'menu');
    }

    public function tickets()
    {
        return $this->morphedByMany(Ticket::class, 'categoryable');
    }

    public function ticket()
    {
        return $this->belongsToMany(Ticket::class, 'event_tickets');
    }

    public function topic()
    {
        return $this->belongsToMany(Topic::class, 'categories_topics_lesson', 'category_id', 'topic_id');
    }

    public function dropbox()
    {
        return $this->morphToMany(Dropbox::class, 'dropboxcacheable');
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'categories_topics_lesson')->withPivot('id', 'lesson_id', 'topic_id', 'priority');
    }

    /**
     * Get all of the testimonials that are assigned this tag.
     */
    public function testimonials()
    {
        return $this->morphToMany(Testimonial::class, 'testimoniable')->with('mediable');
    }

    /**
     * Get all of the faqs that are assigned this tag.
     */

    /*public function faqs()
    {
        return $this->morphedByMany(Faq::class, 'categoryable')->with('category');
    }*/

    public function faqs()
    {
        return $this->morphToMany(Faq::class, 'faqable')->with('category');
    }

    // public function slug()
    // {
    //     return $this->morphedByMany(Slug::class, 'slugs');
    // }

    public function getFaqsCategorized()
    {
        $faqs = [];

        foreach ($this->faqs->toArray() as $faq) {
            if (!isset($faq['category']['0'])) {
                continue;
            }

            $faqs[$faq['category']['0']['name']][] = ['id'=>$faq['id'], 'question' =>$faq['title'], 'answer' => $faq['answer']];
        }

        return $this->morphToMany(Faq::class, 'faqable')->with('category');
    }

    public function getSumOfStudents()
    {
        $students = 0;
        foreach ($this->eventsWithUsers as $event) {
            $students += $event->users->count();
        }

        return $students;
    }

    public function getSumOfStudentsByCategory()
    {
        // This number is not modified each second.
        // So, we can cachce this number and refresh onle each 60 minutes to increase the speed of the page.
        return Cache::remember('Category-getSumOfStudentsByCategory-' . $this->id, 60 * 60, function () {
            $sumStudents = $this->getSumOfStudents();

            if ($this['id'] == 276) {
                $category = Category::find(49);

                if ($category) {
                    $sumStudents += $category->getSumOfStudents();
                }
            } elseif ($this['id'] == 219) {
                $categories = Category::whereIn('id', [104, 268])->get();

                foreach ($categories as $category) {
                    $sumStudents += $category->getSumOfStudents();
                }
            } elseif ($this['id'] == 183) {
                $categories = Category::whereIn('id', [277])->get();

                foreach ($categories as $category) {
                    $sumStudents += $category->getSumOfStudents();
                }
            } elseif ($this['id'] == 250) {
                $categories = Category::whereIn('id', [50, 244])->get();

                foreach ($categories as $category) {
                    $sumStudents += $category->getSumOfStudents();
                }
            }

            return $sumStudents;
        });
    }

    public function changeOrder($from = 0)
    {
        $or = [];

        foreach ($this->lessons()->wherePivot('priority', '>=', $from)->get() as  $pLesson) {
            $newPriorityLesson = $pLesson->pivot->priority + 1;
            $or[$pLesson->pivot->lesson_id] = [
                'priority' => $newPriorityLesson,
                'category_id' => $pLesson->pivot->category_id,
                'topic_id' => $pLesson->pivot->topic_id,
                'lesson_id' => $pLesson->pivot->lesson_id,
            ];
            //$pLesson->pivot->priority = $newPriorityLesson;
            //$pLesson->pivot->save();
        }
        //dd($or);
        $this->lessons()->wherePivotIn('lesson_id', array_keys($or))->detach();
        $this->lessons()->attach($or);
    }

    public function fixOrder($fromTopic = null)
    {
        $newPriorityLesson = 1;
        $newOrder = [];
        $or = [];
        foreach ($this->lessons()->orderBy('priority')->get() as  $pLesson) {
            //$pLesson->pivot->priority = $newPriorityLesson;
            //$pLesson->pivot->save();
            $or[$pLesson->pivot->lesson_id] = [
                'priority' => $newPriorityLesson,
                'category_id' => $pLesson->pivot->category_id,
                'topic_id' => $pLesson->pivot->topic_id,
                'lesson_id' => $pLesson->pivot->lesson_id,
            ];
            $newOrder[$this->id . '-' . $pLesson->pivot->topic_id . '-' . $pLesson->id] = $newPriorityLesson;
            $newPriorityLesson += 1;
        }

        $this->lessons()->wherePivotIn('lesson_id', array_keys($or))->detach();
        $this->lessons()->attach($or);

        return $newOrder;
    }

    /*public function getEventStatus(){

        return $this->morphedByMany(Event::class, 'categoryable')->latest('published_at');

        $events = $this->events()->orderBy('published_at','desc');

        //return $this->morphedByMany(Event::class, 'categoryable')->orderBy('published_at','desc')->first();
        if($events->first() && $events->first()->status == 0){
            return 'opened';
        }else if($events->first() && $events->first()->status == 1){
            return 'closed';
        }
        else if($events->first() && $events->first()->status == 2){
            return 'soldout';
        }
        else if($events->first() && $events->first()->status == 3){
            return 'completed';
        }

        return 'closed';

    }*/

    public function updateLesson(Topic $topic, Lesson $lesson)
    {
        $allEvents = $this->events;

        foreach ($allEvents as $event) {
            $allLessons = $event->allLessons->groupBy('id');

            $date = '';
            $time_starts = null;
            $time_ends = '';
            $duration = '';
            $room = '';
            $instructor_id = '';
            $automate_mail = false;
            $send_automate_mail = false;

            if ($existLesson = $event->allLessons()->wherePivot('topic_id', $topic->id)->wherePivot('lesson_id', $lesson->id)->first()) {
                $priority = $existLesson->pivot->priority;
            } else {
                $priorityLesson = $event->allLessons()->wherePivot('topic_id', $topic->id)->orderBy('priority')->get();
                $priority = isset($priorityLesson->last()['pivot']['priority']) ? $priorityLesson->last()['pivot']['priority'] + 1 : count($priorityLesson) + 1;
            }

            if (isset($allLessons[$lesson['id']][0])) {
                $date = $allLessons[$lesson['id']][0]['pivot']['date'];
                $time_starts = $allLessons[$lesson['id']][0]['pivot']['time_starts'];
                $time_ends = $allLessons[$lesson['id']][0]['pivot']['time_ends'];
                $duration = $allLessons[$lesson['id']][0]['pivot']['duration'];
                $room = $allLessons[$lesson['id']][0]['pivot']['room'];
                $instructor_id = $allLessons[$lesson['id']][0]['pivot']['instructor_id'];
                $automate_mail = $allLessons[$lesson['id']][0]['pivot']['automate_mail'];
                $send_automate_mail = $allLessons[$lesson['id']][0]['pivot']['send_automate_mail'];
            }

            $event->allLessons()->detach($lesson['id']);
            $event->changeOrder($priority);

            $event->topic()->attach($topic['id'], ['lesson_id' => $lesson['id'], 'date'=>$date, 'time_starts'=>$time_starts,
                'time_ends'=>$time_ends, 'duration' => $duration, 'room' => $room, 'instructor_id' => $instructor_id, 'priority' => $priority,
                'automate_mail' => $automate_mail, 'send_automate_mail' => $send_automate_mail]);
            $event->fixOrder();
        }

        $lesson->topic()->wherePivot('category_id', $this->id)->detach();
        $this->changeOrder($priority);
        $this->topic()->attach($topic, ['lesson_id' => $lesson->id, 'priority'=>$priority]);
        $this->fixOrder();
    }

    public function countdown()
    {
        return $this->belongsToMany(Countdown::class, 'cms_countdown_category');
    }
}
