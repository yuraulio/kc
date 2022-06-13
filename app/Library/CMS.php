<?php

namespace App\Library;

use App\Model\Instructor;
use Illuminate\Support\Facades\Auth;
use App\Model\Category;
use App\Model\City;

class CMS
{
    public static function getEventData($event)
    {
        $data = $event->topicsLessonsInstructors();
        $data['event'] = $event;
        $data['benefits'] = $event->benefits->toArray();
        $data['summary'] = $event->summary1()->get()->toArray();
        $data['sections'] = $event->sections->groupBy('section');
        $data['section_fullvideo'] = $event->sectionVideos->first();
        $data['faqs'] = $event->getFaqs();
        $data['testimonials'] = isset($event->category->toArray()[0]) ? $event->category->toArray()[0]['testimonials'] : [];
        shuffle($data['testimonials']);
        $data['tickets'] = $event->ticket()->where('price', '>', 0)->where('active', true)->get()->toArray();
        $data['venues'] = $event->venues->toArray();
        $data['syllabus'] = $event->syllabus->toArray();
        $data['is_event_paid'] = 0;
        $data['is_joined_waiting_list'] = 0;
        $data['sumStudents'] = get_sum_students_course($event->category->first());//isset($event->category[0]) ? $event->category[0]->getSumOfStudents() : 0;
        $data['showSpecial'] = false;
        $data['showAlumni'] = $event->ticket()->where('type', 'Alumni')->where('active', true)->first() ? true : false;
        $data['partners'] = $event->partners;
        


        if ($event->ticket()->where('type', 'Early Bird')->first()) {
            $data['showSpecial'] = ($event->ticket()->where('type', 'Early Bird')->first() && $event->ticket()->where('type', 'Special')->first())  ?
                                    ($event->ticket()->where('type', 'Special')->first()->pivot->active
                                        || ($event->ticket()->where('type', 'Early Bird')->first()->pivot->quantity > 0)) : false;
        } else {
            $data['showSpecial'] = $event->ticket()->where('type', 'Special')->first() ? $event->ticket()->where('type', 'Special')->first()->pivot->active  : false;
        }

        

        $price = -1;

        foreach ($data['tickets'] as $ticket) {
            if ($ticket['pivot']['price'] && $ticket['pivot']['price'] > $price) {
                $price = $ticket['pivot']['price'];
            }
        }

        if ($price <= 0) {
            $price = (float) 0;
        }
        $categoryScript = $event->delivery->first() && $event->delivery->first()->id == 143 ? 'Video e-learning courses' : 'In-class courses'; //$event->category->first() ? 'Event > ' . $event->category->first()->name : '';
        
        $tr_price = $price;
        if ($tr_price - floor($tr_price)>0) {
            $tr_price = number_format($tr_price, 2, '.', '');
        } else {
            $tr_price = number_format($tr_price, 0, '.', '');
            $tr_price = strval($tr_price);
            $tr_price .= ".00";
        }

        $data['tigran'] = ['Price' => $tr_price,'Product_id' => $event->id,'Product_SKU' => $event->id,'ProductCategory' => $categoryScript, 'ProductName' =>  $event->title,'Event_ID' => 'kc_' . time() ];

        if (Auth::user() && count(Auth::user()->events->where('id', $event->id)) > 0) {
            $data['is_event_paid'] = 1;
        } elseif (Auth::user() && $event->waitingList()->where('user_id', Auth::user()->id)->first()) {
            $data['is_joined_waiting_list'] = 1;
        }

        return $data;
    }

    public static function getInstructorData($page)
    {
        $data['content'] = $page;
        $events = array();
        $lessons = [];
        $instructor = Instructor::with('eventInstructorPage.category', 'mediable', 'eventInstructorPage.lessons', 'eventInstructorPage.slugable', 'eventInstructorPage.city', 'eventInstructorPage.summary1')->where('status', 1)->find($page['id']);
        $data['instructor'] = $instructor;
        
        if (!$instructor) {
            abort(404);
        }

        $category = array();

        $data['title'] = '';

        if (isset($instructor['title'])) {
            $data['title'] .= $instructor['title'];
        }

        if (isset($instructor['subtitle'])) {
            $data['title'] .= ' '.$instructor['subtitle'];
        }
        $data['title'] = trim($data['title']);
       
        foreach ($instructor['eventInstructorPage'] as $key => $event) {
            if (($event['status'] == 0 || $event['status'] == 2) && $event->is_inclass_course()) {
                foreach ($event['lessons'] as $lesson) {
                    if ($lesson->pivot['date'] != '') {
                        $date = date("Y/m/d", strtotime($lesson->pivot['date']));
                    } else {
                        $date = date("Y/m/d", strtotime($lesson->pivot['time_starts']));
                    }
                    if (strtotime("now") < strtotime($date)) {
                        if ($lesson['instructor_id'] == $page['id']) {
                            $lessons[] = $lesson['title'];
                        }
                    }
                }
            }
        }
        $category = array();

        foreach ($instructor['eventInstructorPage'] as $key => $event) {
            if ($key == 0) {
                $category[$event['id']] = $event;
            } else {
                if (!isset($category[$event['id']])) {
                    $category[$event['id']] = $event;
                }
            }
        }

        $new_events = array();

        foreach ($category as $category) {
            if (count($new_events) == 0) {
                array_push($new_events, $category);
            } else {
                $find = false;
                foreach ($new_events as $event) {
                    if ($event['title'] == $category['title']) {
                        $find = true;
                    }
                }
                if (!$find) {
                    array_push($new_events, $category);
                }
            }
        }

        $data['instructorTeaches'] = array_unique($lessons);

        $data['instructorEvents'] = $new_events;

        return $data;
    }

    public static function getDeliveryData($delivery)
    {
        $data['title'] = $delivery['name'];
        $data['delivery'] = $delivery;
        $data['openlistt'] = $delivery->event()->has('slugable')->with('category', 'city', 'ticket')->where('published', true)->whereIn('status', [0,5])->orderBy('created_at', 'desc')->get();
        $data['completedlist'] = $delivery->event()->has('slugable')->with('category', 'slugable', 'city', 'ticket')->where('published', true)->where('status', 3)->orderBy('created_at', 'desc')->get();

        $data['openlist'] = [];
        
        foreach ($data['openlistt'] as $openlist) {
            if ($openlist->category->first() == null) {
                $index = 0;
            } else {
                $index = $openlist->category->first()->priority ?  $openlist->category->first()->priority : 0;
            }
            while (in_array($index, array_keys($data['openlist']))) {
                $index++;
            }
            
            $data['openlist'][$index] = $openlist;
        }
        ksort($data['openlist']);

        return $data;
    }

    public static function getHomepageData()
    {
        $data = [];

        $data['nonElearningEvents'] = [];
        $data['elearningEvents'] = [];
        $data['elearningFree'] = [];
        $data['inclassFree'] = [];

        $categories =Category::with('slugable', 'events.slugable', 'events.city', 'events', 'events.mediable')->orderBy('priority', 'asc')->get()->toArray();

        foreach ($categories as $category) {
            if (!key_exists($category['id'], $data['nonElearningEvents'])) {
                $data['nonElearningEvents'][$category['id']]['name'] = $category['name'];
                $data['nonElearningEvents'][$category['id']]['slug'] = isset($category['slugable']) ? $category['slugable']['slug'] : '';
                $data['nonElearningEvents'][$category['id']]['description'] = $category['description'];
                $data['nonElearningEvents'][$category['id']]['hours'] = $category['hours'];
                $data['nonElearningEvents'][$category['id']]['events'] = [];

                $data['elearningEvents'][$category['id']]['name'] = $category['name'];
                $data['elearningEvents'][$category['id']]['slug'] = isset($category['slugable']) ? $category['slugable']['slug'] : '';
                $data['elearningEvents'][$category['id']]['description'] = $category['description'];
                $data['elearningEvents'][$category['id']]['hours'] = $category['hours'];
                $data['elearningEvents'][$category['id']]['events'] = [];

                $data['elearningFree'][$category['id']]['name'] = $category['name'];
                $data['elearningFree'][$category['id']]['slug'] = isset($category['slugable']) ? $category['slugable']['slug'] : '';
                $data['elearningFree'][$category['id']]['description'] = $category['description'];
                $data['elearningFree'][$category['id']]['hours'] = $category['hours'];
                $data['elearningFree'][$category['id']]['events'] = [];

                $data['inclassFree'][$category['id']]['name'] = $category['name'];
                $data['inclassFree'][$category['id']]['slug'] = isset($category['slugable']) ? $category['slugable']['slug'] : '';
                $data['inclassFree'][$category['id']]['description'] = $category['description'];
                $data['inclassFree'][$category['id']]['hours'] = $category['hours'];
                $data['inclassFree'][$category['id']]['events'] = [];
            }

            foreach ($category['events'] as $event) {
                if ($event['status'] == 1 || $event['status'] == 3 || $event['status'] == 4 || !$event['published']) {
                    continue;
                }

                if ($event['view_tpl'] == 'elearning_event' || $event['view_tpl'] == 'elearning_pending') {
                    $data['elearningEvents'][$category['id']]['events'][] = $event;
                } elseif ($event['view_tpl'] == 'event_free' || $event['view_tpl'] == 'event_free_coupon') {
                    $data['inclassFree'][$category['id']]['events'][] = $event;
                } elseif ($event['view_tpl'] == 'elearning_free') {
                    $data['elearningFree'][$category['id']]['events'][] = $event;
                } else {
                    $data['nonElearningEvents'][$category['id']]['events'][] = $event;
                }
            }
        }

        foreach ($data as $key => $categories) {
            foreach ($categories as $key2 => $category) {
                if (count($category['events']) == 0) {
                    unset($data[$key][$key2]);
                }
            }

            if (count($categories) == 0) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    public static function getCityData($page)
    {
        $data['content'] = $page;

        $city = City::with('event')->find($page['id']);

        $data['title'] = $city['name'];
        $data['city'] = $city;
        $data['openlist'] = $city->event()->with('category', 'slugable', 'city', 'ticket', 'summary1')->where('published', true)->whereIn('status', [0])->orderBy('published_at', 'desc')->get();
        $data['completedlist'] = $city->event()->with('category', 'slugable', 'city', 'ticket', 'summary1')->where('published', true)->where('status', 3)->orderBy('published_at', 'desc')->get();
        
        return $data;
    }
}
