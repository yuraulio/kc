<?php

namespace App\Console\Commands\Emails;

use App\Events\EmailSent;
use App\Model\Event;
use App\Model\Instructor;
use App\Notifications\InstructorsMail;
use App\Notifications\SendTopicAutomateMail;
use Carbon\Carbon;
use DateTime;
use Illuminate\Console\Command;

class AutomatedTopicEmail extends Command
{
    // Command name and description
    protected $signature = 'email:sendAutomateMailBasedOnTopic';
    protected $description = 'Send automated email based on the topic automated emails configurations';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the private function to execute the email sending logic
        $this->sendAutomateMailBasedOnTopic();
    }

    private function sendAutomateMailBasedOnTopic()
    {
        $date1 = date('Y-m-d', strtotime('+1 days'));

        $dates = [$date1, date('Y-m-d', strtotime('+4 days'))];

        $events = Event::
        where('published', true)
            ->whereIn('status', [/*0,2,*/ 3])
            ->whereHas('eventInfo', function ($query) {
                $query->where('course_delivery', '!=', 143);
            })
            ->whereHas('lessons', function ($query) use ($dates) {
                return $query->where('automate_mail', true)->where('send_automate_mail', false)->whereIn('date', $dates);
            })
            ->with([
                'topic' => function ($query) use ($dates) {
                    return $query->where('automate_mail', true)->where('send_automate_mail', false)->whereIn('date', $dates);
                },
            ])
            ->with('lessons')
            ->with('users')
            ->get();

        $checkForDoubleTopics = [];

        foreach ($events as $event) {
            $checkForDoubleTopics = [];
            $info = $event->event_info();
            $venues = $event->venues;

            $data['duration'] = isset($info['inclass']['dates']['text']) ? $info['inclass']['dates']['text'] : '';
            $data['course_hours'] = isset($info['inclass']['days']['text']) ? $info['inclass']['days']['text'] : '';
            $data['venue'] = isset($venues[0]['name']) ? $venues[0]['name'] : '';
            $data['address'] = isset($venues[0]['address']) ? $venues[0]['address'] : '';
            $data['faq'] = url('/') . '/' . $event->slugable->slug . '/#faq?utm_source=Knowcrunch.com&utm_medium=Registration_Email';
            $data['fb_group'] = $event->fb_group;
            $data['eventTitle'] = $event->title;
            $data['Location'] = isset($venues[0]['name']) ? $venues[0]['name'] : '';
            $data['eventId'] = $event->id;

            foreach ($event['topic'] as $topic) {
                if (in_array($topic->id, $checkForDoubleTopics)) {
                    continue;
                }
                $checkForDoubleTopics[] = $topic->id;

                if (!$topic->email_template) {
                    continue;
                }

                $data['Date'] = (isset($topic->pivot->date)) ? $topic->pivot->date : '-';
                if (isset($topic->pivot->time_starts)) {
                    $timeObj = new DateTime($topic->pivot->time_starts);
                    $data['Time'] = $timeObj->format('H:i:s');
                }
                $date1 = new DateTime($topic->pivot->time_starts);
                $date2 = new DateTime('today');

                $interval = $date1->diff($date2);

                $templates = explode(',', $topic->email_template);
                foreach ($templates as $emailTemplate) {
                    $data['email_template'] = $emailTemplate;

                    if (strpos($data['email_template'], 'activate') !== false && $interval->days > 1) {
                        return;
                    }
                    if (strpos($emailTemplate, 'instructor') === false) {
                        foreach ($event->users as $user) {
                            $data['firstname'] = $user->firstname;
                            $data['lastname'] = $user->lastname;

                            $user->notify(new SendTopicAutomateMail($data, $user));
                            event(new EmailSent($user->email, 'SendTopicAutomateMail'));
                        }
                    } else {
                        $instructor = Instructor::find($topic->pivot->instructor_id);
                        $user = $instructor->user[0];
                        $data['firstname'] = $user->firstname;
                        $data['lastname'] = $user->lastname;

                        $user->notify(new SendTopicAutomateMail($data, $user));
                        event(new EmailSent($user->email, 'SendTopicAutomateMail'));
                    }

                    foreach ($event->lessons()->wherePivot('topic_id', $topic->id)->get() as $lesson) {
                        if ($interval->days === 1) {
                            $lesson->pivot->send_automate_mail = true;
                            $lesson->pivot->save();
                        }
                    }
                }
            }
        }
    }
}
