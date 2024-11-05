<?php

namespace App\Console\Commands\Emails;

use App\Events\EmailSent;
use App\Model\Event;
use App\Model\Instructor;
use App\Notifications\InClassReminder;
use App\Notifications\InstructorsMail;
use Carbon\Carbon;
use Illuminate\Console\Command;

class InClassReminderEmail extends Command
{
    // Command name and description
    protected $signature = 'email:sendInClassReminder';
    protected $description = 'Send automated email reminders about in-class events about to start';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the private function to execute the email sending logic
        $this->sendInClassReminder();
    }

    private function sendInClassReminder()
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
            }
        }
    }
}
