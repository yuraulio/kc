<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\EmailSent;
use App\Http\Controllers\Admin_api\RoyaltiesController;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use App\Model\Absence;
use App\Model\CartCache;
use App\Model\Event;
use App\Model\Instructor;
use App\Model\Invoice;
use App\Model\Option;
use App\Model\Pages;
use App\Model\Transaction;
use App\Model\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Laravel\Cashier\Subscription;
use Mail;

class CronjobsController extends Controller
{
    //'enroll from 4620||0'
    public function unroll()
    {
        $students = User::has('events')->with('events')->get();
        $today = date('Y-m-d');
        $today = strtotime($today);
        foreach ($students as $student) {
            $events = $student->events()->wherePivot('comment', 'LIKE', '%enroll from%')->get();

            foreach ($events as $event) {
                if (!$event->pivot->expiration) {
                    continue;
                }

                $expiration = strtotime($event->pivot->expiration);

                if ($today > $expiration) {
                    $student->events()->detach($event->id);
                }
            }
        }
    }

    public function generateXMLForPinterest()
    {
        $destinationPath = public_path() . '/xml/pinterest/';

        if (!File::exists($destinationPath)) {
            //dd('ddd');
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }

        $events = Event::wherePublished(1)->whereFeed(1)->with('category', 'ticket', 'mediable')->get();

        $template = '<?xml version="1.0" encoding="utf-8"?>
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
    <channel>';

        $template_finish = '
    </channel>
</rss>';

        $row = '' . $template;

        foreach ($events as $key => $event) {
            if ($event->feed == 0) {
                continue;
            }

            $amount = 0;
            $quantity_status = 'in stock';

            foreach ($event->ticket as $price) {
                if ($price->pivot->price > 0 && $price->type != 'Alumni' && $price->type != 'Special') {
                    $amount = $price->pivot->price;
                    $quantity_status = $price->pivot->quantity > 0 ? 'in stock' : 'out of stock';
                }
            }

            $eventTitle = $event->xml_title ? htmlspecialchars($event->xml_title) : htmlspecialchars($event->title);
            $summary = htmlspecialchars($event->xml_description);

            $img = url('/') . get_image($event['mediable'], 'feed-image');
            $img = str_replace(' ', '%20', $img);

            $item =
                '
        <item>
            <g:id>' . $event->id . '</g:id>
            <title>' . $eventTitle . '</title>
            <description>' . $summary . '</description>
            <link>' . url('/') . '/' . $event->slugable->slug . '</link>
            <g:image_link>' . $img . '</g:image_link>
            <g:price>' . $amount . ' EUR</g:price>
            <g:availability>' . $quantity_status . '</g:availability>
        </item>';

            $row = $row . $item;
        }

        $row = $row . $template_finish;

        File::put($destinationPath . 'pinterest_feed.xml', $row);
    }

    public function generateXMLForTikTok()
    {
        $destinationPath = public_path() . '/xml/tiktok/';

        if (!File::exists($destinationPath)) {
            //dd('ddd');
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }

        $events = Event::wherePublished(1)->whereFeed(1)->with('category', 'ticket', 'mediable')->get();

        $template = '<?xml version="1.0" encoding="utf-8"?>
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
    <channel>';

        $template_finish = '
    </channel>
</rss>';

        $row = '' . $template;

        foreach ($events as $key => $event) {
            if ($event->feed == 0) {
                continue;
            }

            $amount = 0;
            $quantity_status = 'in stock';

            foreach ($event->ticket as $price) {
                if ($price->pivot->price > 0 && $price->type != 'Alumni' && $price->type != 'Special') {
                    $amount = $price->pivot->price;
                    $quantity_status = $price->pivot->quantity > 0 ? 'in stock' : 'out of stock';
                }
            }

            $eventTitle = $event->xml_title ? htmlspecialchars($event->xml_title) : htmlspecialchars($event->title);
            $summary = htmlspecialchars($event->xml_description);

            $img = url('/') . get_image($event['mediable'], 'feed-image');
            $img = str_replace(' ', '%20', $img);

            $item =
                '
        <item>
            <g:id>' . $event->id . '</g:id>
            <g:title>' . $eventTitle . '</g:title>
            <g:description>' . $summary . '</g:description>
            <g:availability>' . $quantity_status . '</g:availability>
            <g:condition>new</g:condition>
            <g:price>' . $amount . ' EUR</g:price>
            <g:link>' . url('/') . '/' . $event->slugable->slug . '</g:link>
            <g:image_link>' . $img . '</g:image_link>
            <g:brand>Knowcrunch</g:brand>
        </item>';

            $row = $row . $item;
        }

        $row = $row . $template_finish;

        File::put($destinationPath . 'tiktok_feed.xml', $row);
    }

    public function generateCSVForFB()
    {
        $destinationPath = public_path() . '/csv/fb/';
        if (!File::exists($destinationPath)) {
            //dd('ddd');
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }

        //$events = Event::where('view_tpl','!=','event_free')->where('view_tpl','!=','event_free_coupon')->where('view_tpl','!=','elearning_free')->where('published',true)->whereIn('status',[0])->with('category', 'ticket','mediable')->get();
        $events = Event::wherePublished(1)->whereFeed(1)->with('category', 'ticket', 'mediable')->get();

        $columns = ['id', 'title', 'description', 'availability', 'price', 'link', 'image_link', 'brand', 'google_product_category', 'condition', 'custom_label_0'];

        $file = fopen('csv/fb/fb.csv', 'w');
        fputcsv($file, $columns);

        foreach ($events as $event) {
            //$cat = $event->category->first() ? $event->category->first()->name : '';
            $cat = 'Business & Industrial > Advertising & Marketing';
            $amount = 0;

            foreach ($event->ticket as $price) {
                if ($price->pivot->price > 0 && $price->type != 'Alumni' && $price->type != 'Special') {
                    $amount = $price->pivot->price;
                }
            }

            $eventTitle = $event->xml_title ? $event->xml_title : $event->title;
            $summary = $event->xml_description;

            //$img = url('/') . $event['mediable']['path'] . '/' . $event['mediable']['original_name'];
            $img = url('/') . get_image($event['mediable'], 'feed-image');
            $img = str_replace(' ', '%20', $img);
            fputcsv($file, [$event->id, $eventTitle, $event->xml_short_description, 'in stock', $amount . ' EUR', url('/') . '/' . $event->slugable->slug, str_replace('\"', '', $img), 'Knowcrunch', $cat, 'new', trim($summary)]);
        }
        fclose($file);

        return back();
    }

    public function generateCSVForGoogle()
    {
        $destinationPath = public_path() . '/csv/google/';
        if (!File::exists($destinationPath)) {
            //dd('ddd');
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }

        //$events = Event::where('view_tpl','!=','event_free')->where('view_tpl','!=','event_free_coupon')->where('view_tpl','!=','elearning_free')->where('published',true)->whereIn('status',[0])->with('category', 'ticket','mediable')->get();
        $events = Event::wherePublished(1)->whereFeed(1)->with('category', 'ticket', 'mediable')->get();
        $columns = ['ID', 'Item Title', 'Final URL', 'Image URL', 'Price', 'Item Category', 'Item Description'];

        $file = fopen('csv/google/google.csv', 'w');
        fputcsv($file, $columns);

        foreach ($events as $event) {
            //$cat = $event->category->first() ? $event->category->first()->name : '';
            $cat = 'Business & Industrial > Advertising & Marketing';
            $city = null;

            $amount = 0;

            foreach ($event->ticket as $price) {
                if ($price->pivot->price > 0 && $price->type != 'Alumni' && $price->type != 'Special') {
                    $amount = $price->pivot->price;
                }
            }

            //$img = url('/') . $event['mediable']['path'] . '/' . $event['mediable']['original_name'];
            $img = url('/') . get_image($event['mediable'], 'feed-image');
            $img = str_replace(' ', '%20', $img);
            $eventTitle = $event->xml_title ? $event->xml_title : $event->title;
            $summary = $event->xml_description;

            fputcsv($file, [$event->id, $eventTitle, url('/') . '/' . $event->slugable->slug, $img, $amount . ' EUR', $cat, trim($summary)]);
        }
        fclose($file);

        return back();
    }

    public function fbGoogleCsv()
    {
        $this->generateCSVForGoogle();
        $this->generateCSVForFB();
        $this->generateXMLForPinterest();
        $this->generateXMLForTikTok();
    }

    public function updateStatusField()
    {
        $subscriptions = Subscription::whereIn('stripe_status', ['canceled', 'cancel', 'cancelled'])->get();

        foreach ($subscriptions as $subscription) {
            $subscription->status = false;
            $subscription->save();
        }

        $subscriptions = Subscription::where('status', true)->get();

        foreach ($subscriptions as $subscription) {
            if (!$subscription->trial_ends_at && !$subscription->ends_at) {
                continue;
            }

            if (strtotime($subscription->trial_ends_at) < strtotime($subscription->ends_at)) {
                $subscription->stripe_status = 'active';
                $subscription->save();
            }
        }

        $date = strtotime(date('Y-m-d'));
        $subscriptions = Subscription::where('status', false)->get();

        foreach ($subscriptions as $subscription) {
            if (strtotime($subscription->ends_at) < $date) {
                $subscription->stripe_status = 'cancelled';
                $subscription->save();
            }
        }
    }

    public function absences()
    {
        $date = date('Y-m-d');
        $events = Event::
        wherePublished(true)
            ->whereHas('lessons', function ($lessonQ) use ($date) {
                return $lessonQ->where('event_topic_lesson_instructor.date', $date);
            })
            ->with(
                [
                    'lessons' => function ($lessonQ) use ($date) {
                        return $lessonQ->wherePivot('date', $date);
                    },
                    'users',
                    'instructors',
                ]
            )->get();

        foreach ($events as $event) {
            $timeStarts = false;
            $timeEnds = false;
            $totalLessonHour = 0;

            $lessons = $event['lessons'];

            foreach ($lessons as $key => $lesson) {
                $lessonHour = date('H', strtotime($lesson->pivot->time_starts));

                if (!$timeStarts) {
                    $timeStarts = (int) date('H', strtotime($lesson->pivot->time_starts));
                }
                $timeEnds = (int) date('H', strtotime($lesson->pivot->time_ends));
            }

            if ($timeStarts && $timeEnds) {
                $totalLessonHour = ($timeEnds - $timeStarts) * 60;
                $instructorIds = $event->instructors->unique()->pluck('id')->toArray();

                foreach ($event['users'] as $user) {
                    if ($user->instructor->first() && in_array($user->instructor->first()->id, $instructorIds)) {
                        continue;
                    }

                    if (Absence::where('user_id', $user->id)->where('event_id', $event->id)->where('date', $date)->first()) {
                        continue;
                    }

                    $absence = new Absence;
                    $absence->user_id = $user->id;
                    $absence->event_id = $event->id;
                    $absence->date = $date;
                    $absence->minutes = 0;
                    $absence->total_minutes = $totalLessonHour;

                    $absence->save();
                }
            }
        }
    }

    private function findFirstLessons($lessons)
    {
        $time = null;
        $earlyLesson = null;

        foreach ($lessons as $key => $lesson) {
            $lesson = $lesson[0];

            if (isset($lesson->pivot->time_starts)) {
                if ($time == null) {
                    $time = $lesson->pivot->time_starts;
                    $earlyLesson = $lesson;
                } else {
                    if (strtotime($lesson->pivot->time_starts) < strtotime($time)) {
                        $time = $lesson->pivot->time_starts;
                        $earlyLesson = $lesson;
                    }
                }
            }
        }

        return $earlyLesson;
    }

    public function calculateTotalRoyaltiesForInstructors()
    {
        $year = new DateTime();

        $request = new \Illuminate\Http\Request();

        $request->replace([
            'transaction_from' => $year->setDate($year->format('Y'), 1, 1)->format('Y-m-d'),
            'transaction_to'   => date('Y-m-d'),
        ]);

        $instructor = Instructor::has('elearningEventsForRoyalties')->whereStatus(1)->get();

        foreach ($instructor as $key => $instr) {
            $instructor[$key]['events'] = $instr->elearningEventsForRoyalties();

            $instructor[$key]['events'] = $instructor[$key]['events']->get();

            $instructor[$key]['income'] = 0;

            $data = (new RoyaltiesController)->getInstructorEventData($instr, $instructor[$key]['events'], $request);

            foreach ($instructor[$key]['events'] as $key2 => $event) {
                $instructor[$key]['income'] = $instructor[$key]['income'] + (new RoyaltiesController)->calculateIncomeByPercentHours($data['events'][$event->id]);
            }

            Instructor::where('id', $instr->id)->update(['cache_income' => $instructor[$key]['income']]);
        }
    }
}
