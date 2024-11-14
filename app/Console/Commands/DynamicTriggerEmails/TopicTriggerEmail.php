<?php

namespace App\Console\Commands\DynamicTriggerEmails;

use App\Events\EmailSent;
use App\Model\Email;
use App\Model\EmailTrigger;
use App\Model\Event;
use App\Model\Instructor;
use App\Notifications\SendTopicAutomateMail;
use App\Services\EmailSendService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class TopicTriggerEmail extends Command
{
    // Command name and description
    protected $signature = 'email:sendTopicTriggerEmail';
    protected $description = 'Send automated email based on the topics';

    public function __construct(protected readonly EmailSendService $emailSendService)
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the private function to execute the email sending logic
        $this->sendTopicTriggerEmail();
    }

    private function sendTopicTriggerEmail()
    {
        $emailTriggers = EmailTrigger::where('trigger_type', 'lesson')->whereHas('email', function (Builder $query) {
            $query->where('status', 1);
        })->get();
        foreach ($emailTriggers as $emailTrigger) {
            $date = ($emailTrigger->value || $emailTrigger->value === 0) ? date('Y-m-d', strtotime("+$emailTrigger->value days")) : null;
            if ($date === null) { //Skip if trigger value is not set.
                continue;
            }
            $dates = [$date];
            $filters = ($emailTrigger->trigger_filters);

            $statuses = array_column($filters['status'], 'id');
            $lessons = array_column($filters['lesson_ids'], 'id');
            $courses = array_column($filters['course_ids'], 'id');

            $events = Event::
            where('published', true);
            if (count($statuses)) {
                $events = $events->whereIn('status', $statuses);
            }
            if (count($courses)) {
                $events = $events->whereIn('id', $courses);
            }

            if (count($lessons)) {
                $events = $events->whereHas('lessons', function ($query) use ($lessons, $dates) {
                    $query->whereIn('lesson_id', $lessons)->whereIn('date', $dates);
                });
            }

            $events = $events->with([
                'lessons' => function ($query) use ($dates) {
                    return $query->whereIn('date', $dates);
                },
            ])
                ->with('users')
                ->get();
            foreach ($events as $event) {
                $checkForDoubleTopics = [];
                $data = [];
                $info = $event->event_info();
                $venues = $event->venues;
                if (isset($info['inclass'])) {
                    $data['duration'] = isset($info['inclass']['dates']['text']) ? $info['inclass']['dates']['text'] : '';
                    $data['course_hours'] = isset($info['inclass']['days']['text']) ? $info['inclass']['days']['text'] : '';
                }
                if (count($venues)) {
                    $data['venue'] = isset($venues[0]['name']) ? $venues[0]['name'] : '';
                    $data['address'] = isset($venues[0]['address']) ? $venues[0]['address'] : '';
                    $data['Location'] = isset($venues[0]['name']) ? $venues[0]['name'] : '';
                }
                $data['faq'] = url('/') . '/' . $event->slugable->slug . '/#faq?utm_source=Knowcrunch.com&utm_medium=Registration_Email';
                $data['fb_group'] = $event->fb_group ? $event->fb_group : '';
                $data['eventTitle'] = $event->title;
                $data['eventId'] = $event->id;

                foreach ($event['lessons'] as $lesson) {
                    if (in_array($lesson->id, $checkForDoubleTopics)) {
                        continue;
                    }
                    $checkForDoubleTopics[] = $lesson->id;

                    $data['Date'] = (isset($lesson->pivot->date)) ? $lesson->pivot->date : '-';
                    if (isset($lesson->pivot->time_starts)) {
                        $timeObj = new DateTime($lesson->pivot->time_starts);
                        $data['Time'] = $timeObj->format('H:i:s');
                    }
                    $date1 = new DateTime($lesson->pivot->time_starts);
                    $date2 = new DateTime('today');

                    $interval = $date1->diff($date2);

                    $data['email_template'] = $emailTrigger->email->template['label'];
                    if (!isset($filters['to_instructor']) || $filters['to_instructor'] === '0') {
                        foreach ($event->users as $user) {
                            $data['firstname'] = $user->firstname;
                            $data['lastname'] = $user->lastname;

                            $this->emailSendService->sendEmailByEmailId($emailTrigger->email->id, $user->toArray(), null, array_merge([
                                'FNAME'=> $data['firstname'],
                                'CourseName'=>$data['eventTitle'],
                                'SubscriptionPrice'=>isset($data['subscription_price']) ? $data['subscription_price'] : '0',
                            ], $data), ['event_id'=>$data['eventId']]);

                            event(new EmailSent($user->email, 'SendTopicAutomateMail'));
                        }
                    } else {
                        $instructor = Instructor::find($lesson->pivot->instructor_id);
                        $user = $instructor->user[0];
                        $data['firstname'] = $user->firstname;
                        $data['lastname'] = $user->lastname;
                        $this->emailSendService->sendEmailByEmailId($emailTrigger->email->id, $user->toArray(), null, array_merge([
                            'FNAME'=> $data['firstname'],
                            'CourseName'=>$data['eventTitle'],
                            'SubscriptionPrice'=>isset($data['subscription_price']) ? $this->data['subscription_price'] : '0',
                        ], $data), ['event_id'=>$data['eventId']]);
                        event(new EmailSent($user->email, $emailTrigger->email->title));
                    }
                    $emailTrigger->lesson_trigger_logs()->save($lesson);
                }
                $emailTrigger->course_trigger_logs()->save($event);
            }
        }
    }
}
