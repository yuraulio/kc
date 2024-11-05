<?php

namespace App\Console\Commands\Emails;

use App\Model\Event;
use App\Model\Instructor;
use App\Notifications\InstructorsMail;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutomatedInstructorEmail extends Command
{
    // Command name and description
    protected $signature = 'email:sendAutomatedForInstructors';
    protected $description = 'Send automated email reminders to instructors';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the private function to execute the email sending logic
        $this->sendAutomateEmailForInstructors();
    }

    private function sendAutomateEmailForInstructors()
    {
        $data = [];

        $date = date('Y-m-d');

        $events = Event::where('published', true)
            ->whereIn('status', [0, 3])
            ->whereHas('eventInfo', function ($query) {
                $query->where('course_delivery', '=', 139);
            })
            ->whereHas('lessons', function ($lessonQ) use ($date) {
                return $lessonQ->where('event_topic_lesson_instructor.date', '>', $date);
            })
            ->with(
                [
                    'lessons' => function ($lessonQ) use ($date) {
                        return $lessonQ->wherePivot('date', '>', $date);
                    },
                ]
            )
            ->get();

        $date = Carbon::parse($date);

        foreach ($events as $key => $event) {
            foreach ($event['lessons'] as $key_lesson => $lesson) {
                $lesson_start = Carbon::parse($lesson->pivot->date)->format('Y-m-d');

                $diff = $date->diffInDays($lesson_start);

                // for 1 day or 7 day
                //7 is demo day
                if ($diff == 1 || $diff == 7) {
                    $data[$lesson->pivot->instructor_id][] = [$lesson];
                }
            }
        }

        foreach ($data as $instructor_id => $lessons) {
            $lesson = null;

            $lesson = $this->findFirstLessons($lessons);
            $instructor = Instructor::find($instructor_id);

            $email_data = [];
            $email_data['firstname'] = $instructor['user'][0]['firstname'];
            $email_data['template'] = 'emails.instructor.automate_instructor';
            $email_data['subject'] = 'Knowcrunch | ' . $email_data['firstname'] . ', reminder about your course';
            $email_data['location'] = isset($lesson->pivot->room) ? $lesson->pivot->room : '';
            $email_data['date'] = isset($lesson->pivot->time_starts) ? date('d-m-Y H:s', strtotime($lesson->pivot->time_starts)) : '';
            $email_data['title'] = isset($lesson) ? $lesson->title : '';

            $instructor['user'][0]->notify(new InstructorsMail($email_data, $instructor['user'][0]));
            event(new EmailSent($instructor['user'][0]->email, 'InstructorsMail'));
        }
    }

    private function findFirstLessons($lessons)
    {
        return $lessons[0][0] ?? null; // Adjust logic if necessary
    }
}
