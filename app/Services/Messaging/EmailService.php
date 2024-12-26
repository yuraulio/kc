<?php

namespace App\Services\Messaging;

use App\Model\Email;
use App\Model\EmailTrigger;
use App\Model\Event;
use App\Model\MessageCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Storage;

class EmailService
{
    public function createOrUpdate(Request $request)
    {
        $email = Email::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'title' => $request->title,
                'status' => $request->status,
                'predefined_trigger' => $request->predefined_trigger,
                'template' => $request->template,
                'description' => $request->description,
                'creator_id' => $request->creator_id,
            ]
        );
        $this->saveTriggers($email, $request);
        if (count($request->categories)) {
            $email->messaging_categories()->detach();
            foreach ($request->categories as $category) {
                $messageCategory = MessageCategory::find($category['id']);
                $messageCategory->email()->save($email);
            }
        }

        return $email;
    }

    public function updateEmail(Email $email, Request $request)
    {
        return $email->update($request->all());
    }

    public function delete(Email $email)
    {
        return $email->delete();
    }

    private function saveTriggers(Email $email, Request $request)
    {
        if (count($request->triggers)) {
            $email->triggers()->delete();

            foreach ($request->triggers as $trigger) {
                $emailTrigger = EmailTrigger::firstOrCreate([
                    'email_id' => $email->id,
                    'trigger_type' => $trigger['trigger_type'],
                    'value' => $trigger['value'],
                    'value_sign' => $trigger['value_sign'],
                    'trigger_filters' => $trigger['trigger_filters'],
                ]);
            }
        }
    }

    public function getCourseStartorEndEmails($eventId)
    {
        return $this->getEmailsByTriggerType($eventId, ['course_start_date', 'course_end_date'], function ($emailTrigger, $event) {
            $logicType = $emailTrigger->trigger_type === 'course_start_date' ? 'starts' : 'ends';
            $logicDate = $emailTrigger->trigger_type === 'course_start_date' ? $event->launch_date : $event->finishClassDuration();
            $exactDate = date('Y-m-d', strtotime(-1 * ($emailTrigger->value_sign * $emailTrigger->value) . ' days', strtotime($logicDate)));
            $isLogged = in_array($emailTrigger->id, $event->email_trigger_logs->pluck('id')->toArray());
            $status = $exactDate > Date::now()->format('Y-m-d') ? 'Scheduled' : ($isLogged ? 'Sent' : 'Failed');

            return [
                'to' => 'To all students of the course',
                'exact_date' => $exactDate,
                'status' => $status,
                'trigger_description' => $emailTrigger->value_sign > 0
                    ? "$emailTrigger->value days before the course $logicType"
                    : "$emailTrigger->value days after the course $logicType",
            ];
        });
    }

    private function getTopicEmailTargetUsers(EmailTrigger $emailTrigger)
    {
        $to = '';
        switch ($emailTrigger->trigger_filters['role_id'] ?? '0') {
            case '7':
                $to = 'To all students of the course';
                break;
            case '10':
                $to = 'To the instructor of the lesson';
                break;
            default:
                $to = '-';
                break;
        }

        return $to;
    }

    public function getTopicEmails($eventId)
    {
        return $this->getEmailsByTriggerType($eventId, ['lesson'], function (EmailTrigger $emailTrigger, $event) {
            $to = $this->getTopicEmailTargetUsers($emailTrigger);
            $eventLessons = count($emailTrigger->trigger_filters['lesson_ids'])
                ? $event->lessons()->wherePivotIn('lesson_id', array_column($emailTrigger->trigger_filters['lesson_ids'], 'id'))->get()
                : $event->lessons()->wherePivot('date', '!=', null)->get();
            $data = [];
            foreach ($eventLessons as $eventLesson) {
                $exactDate = date('Y-m-d', strtotime("-$emailTrigger->value days", strtotime($eventLesson->pivot->date)));
                $isLogged = in_array($emailTrigger->id, $eventLesson->email_trigger_logs->pluck('id')->toArray());
                $status = $exactDate > Date::now()->format('Y-m-d') ? 'Scheduled' : ($isLogged ? 'Sent' : 'Failed');
                $data[] = array_merge($this->getGenericEmailTriggerData($emailTrigger), [
                    'to' => $to,
                    'exact_date' => $exactDate,
                    'status' => $status,
                    'trigger_description' => "$emailTrigger->value days before lesson " . $eventLesson->title,
                ]);
            }

            return $data;
        });
    }

    private function getEmailsByTriggerType($eventId, array $triggerTypes, callable $callback)
    {
        $emailTriggers = EmailTrigger::whereIn('trigger_type', $triggerTypes)->whereHas('email', function (Builder $query) {
            $query->where('status', 1);
        })->get();
        $data = [];
        foreach ($emailTriggers as $emailTrigger) {
            $filters = $emailTrigger->trigger_filters;
            $events = Event::where('published', true)
                ->when(isset($filters['status']) && count($filters['status']), function ($query) use ($filters) {
                    return $query->whereIn('status', array_column($filters['status'], 'id'));
                })
                ->when(isset($filters['course_ids']) && count($filters['course_ids']), function ($query) use ($filters) {
                    return $query->whereIn('id', array_column($filters['course_ids'], 'id'));
                })
                ->when(isset($filters['course_deliveries']) && count($filters['course_deliveries']), function ($query) use ($filters) {
                    return $query->whereHas('eventInfo', function ($query) use ($filters) {
                        $query->whereIn('course_delivery', array_column($filters['course_deliveries'], 'id'));
                    });
                })
                ->when(isset($filters['lesson_ids']) && count($filters['lesson_ids']), function ($query) use ($filters) {
                    return $query->whereHas('lessons', function ($query) use ($filters) {
                        $query->whereIn('lesson_id', array_column($filters['lesson_ids'], 'id'));
                    });
                })
                ->with('users')
                ->where('id', $eventId)
                ->first();
            if (!$events) {
                continue;
            }
            $callbackData = $callback($emailTrigger, $events);
            if (isset($callbackData[0])) {
                $data = array_merge($data, $callbackData);
            } else {
                $data[] = array_merge($this->getGenericEmailTriggerData($emailTrigger), $callbackData);
            }
        }

        return $data;
    }

    private function getGenericEmailTriggerData(EmailTrigger $emailTrigger)
    {
        return [
            'title' => $emailTrigger->email->title,
            'description' => $emailTrigger->email->description,
            'id' => $emailTrigger->email->id,
            'template' => $emailTrigger->email->template,
            'trigger_id' => $emailTrigger->id,
        ];
    }
}
