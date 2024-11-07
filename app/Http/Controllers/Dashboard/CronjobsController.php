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

    public function remindAbandonedUser()
    {
        $abandoneds = CartCache::where('send_email', 0)->get();
        $nowTime = now()->subMinutes(60);
        if (isBlackFriday() || isCyberMonday()) {
            $nowTime = now()->subMinutes(120);
        }

        foreach ($abandoneds as $abandoned) {
            if ($abandoned->created_at >= $nowTime) {
                continue;
            }

            if (!$user = $abandoned->user) {
                continue;
            }

            if (!$event = $abandoned->eventt) {
                continue;
            }

            if (!$event->published || $event->status != 0) {
                continue;
            }

            $data['firstName'] = $user->firstname;
            $data['eventTitle'] = $event->title;
            $data['emailEvent'] = 'AbandonedCart';
            if (isBlackFriday()) {
                $data['emailEvent'] = 'AbandonedCartBF1';
                $data['DiscountedPrice'] = ($abandoned->price * 0.5); //50% Off Black Friday
            } elseif (isCyberMonday()) {
                $data['emailEvent'] = 'AbandonedCartCM1';
                $data['DiscountedPrice'] = ($abandoned->price * 0.6); //40% Off Cyber Monday
            }
            $data['eventId'] = $event->id;
            $data['faqs'] = url('/') . '/' . $event->slugable->slug . '/#faq';
            $data['slug'] = url('/') . '/registration?cart=' . $abandoned->slug;
            if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                $user->notify(new AbandonedCart($data, $user));
                event(new EmailSent($user->email, 'AbandonedCart'));
                $subject = $user->firstname . ' - do you need help with your enrollment';
                event(new ActivityEvent($user, ActivityEventEnum::EmailSent->value, $subject . ', ' . Carbon::now()->format('d F Y')));
                event(new ActivityEvent($user, ActivityEventEnum::AbandonedCart->value, $abandoned->product_title . ', ' . Carbon::now()->format('d F Y')));

                $abandoned->send_email = 1;
                $abandoned->save();
            } else {
                $abandoned->send_email = 1;
                $abandoned->save();
            }
        }
    }

    public function remindAbandonedUserSecond()
    {
        $now_date = now();
        $now_date = date_format($now_date, 'Y-m-d');

        if (isBlackFriday() || isCyberMonday()) {
            $abandoneds = CartCache::where('send_email', '=', 1)->get();

            foreach ($abandoneds as $abandoned) {
                if ($abandoned->created_at <= now()->subMinutes(240)) {
                    continue;
                }

                if ($abandoned->updated_at >= now()->subMinutes(120)) {
                    continue;
                }

                if (!$user = $abandoned->user) {
                    continue;
                }

                if (!$event = $abandoned->eventt) {
                    continue;
                }

                if (!$event->published || $event->status != 0) {
                    continue;
                }

                $data['firstName'] = $user->firstname;
                $data['eventTitle'] = $event->title;
                if (isBlackFriday()) {
                    $data['emailEvent'] = 'AbandonedCartBF2';
                    $data['DiscountedPrice'] = ($abandoned->price * 0.5); //50% Off Black Friday
                } elseif (isCyberMonday()) {
                    $data['emailEvent'] = 'AbandonedCartCM2';
                    $data['DiscountedPrice'] = ($abandoned->price * 0.6); //40% Off Cyber Monday
                }
                $data['eventId'] = $event->id;
                $data['faqs'] = url('/') . '/' . $event->slugable->slug . '/#faq';
                $data['slug'] = url('/') . '/registration?cart=' . $abandoned->slug;

                $user->notify(new AbandonedCart($data, $user, true));
                event(new EmailSent($user->email, 'AbandonedCart'));
                event(new ActivityEvent($user, ActivityEventEnum::EmailSent->value, $data['firstName'] . ' - do you need help with your enrollment' . ', ' . Carbon::now()->format('d F Y')));
                event(new ActivityEvent($user, ActivityEventEnum::AbandonedCart->value, $abandoned->product_title . ', ' . Carbon::now()->format('d F Y')));
                $abandoned->send_email = 2;
                $abandoned->save();
            }
        }
    }

    public function sendExpirationEmails()
    {
        $adminemail = 'info@knowcrunch.com';
        $events = Event::has('transactions')->with('users')->where('view_tpl', 'elearning_event')->get();

        $today = date_create(date('Y/m/d'));
        $today1 = date('Y-m-d');

        foreach ($events as $event) {
            foreach ($event['users'] as $user) {
                if (!($user->pivot->expiration >= $today1) || !$user->pivot->expiration) {
                    continue;
                }

                $date = date_create($user->pivot->expiration);
                $date = date_diff($date, $today);

                if ($event->id == 2304 && ($date->y == 0 && $date->m == 0 && ($date->d == 7 || $date->d == 15))) {
                    $data['firstName'] = $user->firstname;
                    $data['eventTitle'] = $event->title;
                    $data['eventId'] = $event->id;

                    $data['expirationDate'] = date('d-m-Y', strtotime($user->pivot->expiration));

                    $page = Pages::find(4752);

                    $data['subscriptionSlug'] = url('/') . '/' . $page->getSlug();
                    $data['template'] = 'emails.user.courses.masterclass_expiration';
                    $data['subject'] = 'Knowcrunch | ' . $data['firstName'] . ' your course expires soon';
                    $data['subscription_price'] = $event->plans[0]['cost'];

                    $user->notify(new ExpirationMails($data, $user));
                    event(new EmailSent($user->email, 'ExpirationMails'));
                    event(new ActivityEvent($user, ActivityEventEnum::EmailSent->value, $data['subject'] . ', ' . Carbon::now()->format('d F Y')));
                } elseif ($event->id !== 2304 && ($date->y == 0 && $date->m == 0 && ($date->d == 7 || $date->d == 15))) {
                    $data['firstName'] = $user->firstname;
                    $data['eventTitle'] = $event->title;
                    $data['eventId'] = $event->id;

                    $data['expirationDate'] = date('d-m-Y', strtotime($user->pivot->expiration));

                    $data['template'] = 'emails.user.courses.week_expiration';
                    $data['subject'] = 'Knowcrunch | ' . $data['firstName'] . ' your course expires soon';

                    $user->notify(new ExpirationMails($data, $user));
                    event(new EmailSent($user->email, 'ExpirationMails'));
                    event(new ActivityEvent($user, ActivityEventEnum::EmailSent->value, $data['subject'] . ', ' . Carbon::now()->format('d F Y')));
                }
            }
        }
    }

    public function sendPaymentReminder()
    {
        $today = date('Y-m-d');

        $invoiceUsers = Invoice::doesntHave('subscription')->where('date', '!=', '-')->where('instalments_remaining', '>', 0)->get();

        foreach ($invoiceUsers as $invoiceUser) {
            $user = $invoiceUser->user->first();
            if (!$user) {
                continue;
            }

            if (!$invoiceUser->transaction->first()) {
                continue;
            }

            $date = date_create($invoiceUser->date);
            $today = date_create(date('Y/m/d'));
            $date = date_diff($date, $today);

            if (($date->y == 0 && $date->m == 0 && $date->d == 7)) {
                $data = [];
                $data['firstName'] = $user->firstname;
                $data['eventTitle'] = $invoiceUser->event->first()->title;
                $data['eventId'] = $invoiceUser->event->first()->id;
                $data['subject'] = 'Knowcrunch - ' . $data['firstName'] . ' a payment is coming';
                $data['paymentDate'] = date('d-m-Y', strtotime($invoiceUser->date));
                $data['template'] = 'emails.user.payment_reminder';

                $user->notify(new PaymentReminder($data));
                event(new EmailSent($user->email, 'PaymentReminder'));
                event(new ActivityEvent($user, ActivityEventEnum::EmailSent->value, $data['subject'] . ', ' . Carbon::now()->format('d F Y')));
            }
        }
    }

    public function sendHalfPeriod()
    {
        $adminemail = 'info@knowcrunch.com';

        $events = Event::has('transactions')->where('published', true)->with('users')
            ->whereHas('eventInfo', function ($query) {
                $query->whereCourseDelivery(143);
            })
            ->get();

        $today = date_create(date('Y/m/d'));
        $today1 = date('Y-m-d');
        foreach ($events as $event) {
            $eventInfo = $event->event_info();
            $expiration = isset($eventInfo['elearning']['expiration']) ? $eventInfo['elearning']['expiration'] : '';

            foreach ($event['users'] as $user) {
                if (!($user->pivot->expiration >= $today1) || !$user->pivot->expiration || !$expiration) {
                    continue;
                }
                $date = date_create($user->pivot->expiration);
                $date = date_diff($date, $today);

                if ($date->y == 0 && $date->m == ($expiration / 2) && $date->d == 0) {
                    $data['firstName'] = $user->firstname;
                    $data['eventTitle'] = $event->title;
                    $data['eventId'] = $event->id;
                    $data['fbGroup'] = $event->fb_group;
                    $data['subject'] = 'Knowcrunch - ' . $data['firstName'] . ' you are almost there';
                    $data['template'] = 'emails.user.half_period';

                    $user->notify(new HalfPeriod($data, $user));
                    event(new EmailSent($user->email, 'HalfPeriod'));
                    event(new ActivityEvent($user, ActivityEventEnum::EmailSent->value, $data['subject'] . ', ' . Carbon::now()->format('d F Y')));
                }
            }
        }
    }

    public function sendElearningFQ()
    {
        $today = date('Y-m-d', strtotime('-15 day', strtotime(date('Y-m-d'))));
        $adminemail = 'info@knowcrunch.com';
        $transactions = Transaction::with('event', 'user')->whereDay('created_at', date('d', strtotime($today)))
            ->whereMonth('created_at', date('m', strtotime($today)))
            ->whereYear('created_at', date('Y', strtotime($today)))
            ->where(function ($q) use ($today) {
                $q->whereHas('event', function ($query) use ($today) {
                    $query->whereViewTpl('elearning_event');
                });
            })->get();

        foreach ($transactions as $transaction) {
            if (!($event = $transaction->event->first())) {
                continue;
            }

            if (count($event->getExams()) <= 0 || !$event->expiration) {
                continue;
            }

            foreach ($transaction['user'] as $user) {
                $expiration = $event->users()->wherePivot('user_id', $user->id)->first();
                if (!$expiration) {
                    continue;
                }

                $data['firstName'] = $user->firstname;
                $data['eventTitle'] = $event->title;
                $data['eventId'] = $event->id;
                $data['subject'] = 'Knowcrunch - ' . $data['firstName'] . ' enjoying ' . $event->title . '?';
                $data['elearningSlug'] = url('/') . '/myaccount/elearning/' . $event->title;
                $data['expirationDate'] = date('d-m-Y', strtotime($expiration->pivot->expiration));
                // $data['template'] = 'emails.user.elearning_f&qemail';

                $user->notify(new ElearningFQ($data, $user));
                event(new EmailSent($user->email, 'ElearningFQ'));
                event(new ActivityEvent($user, ActivityEventEnum::EmailSent->value, $data['subject'] . ', ' . Carbon::now()->format('d F Y')));
            }
        }
    }

    public function sendSurveyMail()
    {
        $events = Event::has('transactions')->with('users')->where('view_tpl', 'elearning_event')->get();
        $today = date('Y/m/d');
        $today = date('Y-m-d', strtotime('-1 day', strtotime($today)));
        foreach ($events as $event) {
            $sendEmail = false;
            foreach ($event['users'] as $user) {
                if ($user->pivot->expiration !== $today || !$user->pivot->expiration) {
                    continue;
                }
                if ($event->evaluate_instructors) {
                    $sendEmail = true;
                } elseif ($event->evaluate_topics) {
                    $sendEmail = true;
                } elseif ($event->fb_testimonial) {
                    $sendEmail = true;
                }

                $data['firstName'] = $user->firstname;
                $data['subject'] = 'Knowcrunch - ' . $data['firstName'] . ' please take our survey';
                $data['template'] = 'emails.user.survey_email';
                $data['eventTitle'] = $event->title;
                $data['eventId'] = $event->id;
                $data['evaluateTopics'] = $event->evaluate_topics;
                $data['evaluateInstructors'] = $event->evaluate_instructors;

                if ($sendEmail) {
                    $user->notify(new SurveyEmail($data, $user));
                    event(new EmailSent($user->email, 'SurveyEmail'));
                    event(new ActivityEvent($user, ActivityEventEnum::EmailSent->value, $data['subject'] . ', ' . Carbon::now()->format('d F Y')));
                }
            }
        }

        $events = Event::has('transactions')->with('users')->where('view_tpl', 'event')->get();

        foreach ($events as $event) {
            $sendEmail = false;
            $lessons = $event->lessons;
            foreach ($event['users'] as $user) {
                $lesson = $lessons->last();

                if (!isset($lesson['pivot']['time_ends'])) {
                    continue;
                }

                $lastDayLesson = date('Y-m-d', strtotime($lesson['pivot']['time_ends']));

                if ($lastDayLesson !== $today) {
                    continue;
                }

                if ($event->evaluate_instructors) {
                    $sendEmail = true;
                } elseif ($event->evaluate_topics) {
                    $sendEmail = true;
                } elseif ($event->fb_testimonial) {
                    $sendEmail = true;
                }

                $data['firstName'] = $user->firstname;
                $data['subject'] = 'Knowcrunch - ' . $data['firstName'] . ' please take our survey';
                $data['template'] = 'emails.user.survey_email';
                $data['evaluateTopics'] = $event->evaluate_topics;
                $data['evaluateInstructors'] = $event->evaluate_instructors;
                $data['eventTitle'] = $event->title;
                $data['eventId'] = $event->id;

                if ($sendEmail) {
                    $user->notify(new SurveyEmail($data, $user));
                    event(new EmailSent($user->email, 'SurveyEmail'));
                    event(new ActivityEvent($user, ActivityEventEnum::EmailSent->value, $data['subject'] . ', ' . Carbon::now()->format('d F Y')));
                }
            }
        }
    }

    public function sendInClassReminder()
    {
        $date1 = date('Y-m-d', strtotime('+7 days'));
        $dates = [$date1];

        $events = Event::
        where('published', true)
            ->whereIn('status', [0, 2])
            ->whereIn('launch_date', $dates)
            ->whereHas('eventInfo', function ($query) {
                $query->where('course_delivery', '!=', 143);
            })
            ->with('users')
            ->get();

        foreach ($events as $event) {
            $first_lesson = $event->lessons->first();

            if ($first_lesson) {
                $data['first_lesson_date'] = isset($first_lesson->pivot->date) ? date('d-m-Y', strtotime($first_lesson->pivot->date)) : '';
                $data['first_lesson_time'] = isset($first_lesson->pivot->time_starts) ? date('H:i', strtotime($first_lesson->pivot->time_starts)) : '';
            }

            $info = $event->event_info();
            $venues = $event->venues;

            $data['duration'] = isset($info['inclass']['dates']['text']) ? $info['inclass']['dates']['text'] : '';
            $data['course_hours'] = isset($info['inclass']['days']['text']) ? $info['inclass']['days']['text'] : '';
            $data['venue'] = isset($venues[0]['name']) ? $venues[0]['name'] : '';
            $data['address'] = isset($venues[0]['address']) ? $venues[0]['address'] : '';
            $data['faq'] = url('/') . '/' . $event->slugable->slug . '/#faq?utm_source=Knowcrunch.com&utm_medium=Registration_Email';
            $data['fb_group'] = $event->fb_group;
            $data['eventTitle'] = $event->title;
            $data['eventId'] = $event->id;

            foreach ($event->users as $user) {
                $data['button_text'] = 'Activate your account';
                $data['activateAccount'] = false;
                $data['slug'] = '';

                $slug = [];
                $slug['id'] = $user->id;
                $slug['email'] = $user->email;
                $slug['create'] = true;

                $slug = encrypt($slug);

                $data['firstname'] = $user->firstname;
                $data['lastname'] = $user->lastname;
                $data['slug'] = url('/') . '/create-your-password/' . $slug;

                if ($user->statusAccount && $user->statusAccount->completed) {
                    $data['activateAccount'] = false;
                    $data['button_text'] = 'Access your account';
                    $data['slug'] = url(config('app.url')) . '/myaccount';
                }

                $user->notify(new InClassReminder($data, $user));
                event(new EmailSent($user->email, 'InClassReminder'));
                event(new ActivityEvent($user, ActivityEventEnum::EmailSent->value, 'Knowcrunch - Welcome ' . $user->firstname . '. Reminder about your course' . ', ' . Carbon::now()->format('d F Y')));
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
