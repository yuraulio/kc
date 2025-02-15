<?php

namespace App\Jobs;

use App\Model\Event;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnrollStudentsToElearningEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $event;
    private $eventsToEnroll;
    private $eventsToEnrollExams;
    private ?string $newExpiration;
    private ?string $oldExpiration;
    private bool $statusChanged = false;

    public function __construct(
        $event,
        $eventsToEnroll,
        $eventsToEnrollExams,
        string $newExpiration = null,
        string $oldExpiration = null,
        bool $statusChanged = false,
    ) {
        $this->queue = 'enroll';

        $this->event = Event::find($event);
        $this->eventsToEnroll = $eventsToEnroll;

        $this->eventsToEnrollExams = 0;
        if ($eventsToEnrollExams) {
            $this->eventsToEnrollExams = 1;
        }

        $this->newExpiration = $newExpiration;
        $this->oldExpiration = $oldExpiration;
        $this->statusChanged = $statusChanged;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('EnrollStudentsToElearningEvents@handle enter');
        if (!$this->eventsToEnroll) {
            $students = $this->event->users()->get();

            foreach ($students as $student) {
                $student->events()->wherePivot('comment', 'enroll from ' . $this->event->id . '||1')->orWherePivot('comment', 'enroll from ' . $this->event->id . '||0')->detach();
            }

            $this->event->enroll = false;
            $this->event->save();
        } else {
            $students = $this->event->users()->pluck('user_id')->toArray();

            foreach ($students as $student) {
                $user = User::find($student);

                $userEvents = $user->events_for_user_list;

                foreach ($userEvents as $eve) {
                    if (in_array($eve->id, $this->eventsToEnroll)) {
                        if (str_contains($eve->pivot['comment'], 'enroll from') || str_contains($eve->pivot['comment'], 'enroll') && ($eve->pivot['comment'] != 'enroll from ' . $this->event->id . '||' . $this->eventsToEnrollExams || $eve->pivot['comment'] != 'enroll' . '||' . $this->eventsToEnrollExams)) {
                            $user->events()->wherePivot('event_id', $eve->id)->wherePivot('user_id', $user->id)->updateExistingPivot($eve->id, ['comment' => 'enroll from ' . $this->event->id . '||' . $this->eventsToEnrollExams], false);
                            //$user->events()->wherePivot('event_id', $eve->id)->wherePivot('user_id', $user->id)->detach();
                        }
                    } else {
                        if (str_contains($eve->pivot['comment'], 'enroll from') || str_contains($eve->pivot['comment'], 'enroll')) {
                            $user->events()->wherePivot('event_id', $eve->id)->wherePivot('user_id', $user->id)->detach();
                        }
                    }
                }
            }

            // enroll from .... ||0 // exams disabled
            // enroll from .... ||1 // exams enabled

            foreach ($this->eventsToEnroll as $eventToEnroll) {
                $elearningEvent = Event::find($eventToEnroll);
                $existsStudent = $elearningEvent->users()->pluck('user_id')->toArray();

                $students1 = array_diff($students, $existsStudent);
                $today = date('Y/m/d');
                $monthsExp2 = '';
                if (($exp = $elearningEvent->getAccessInMonths()) > 0) {
                    $monthsExp2 = '+' . $exp . ' months';
                    $monthsExp2 = date('Y-m-d', strtotime($monthsExp2, strtotime($today)));
                }

                foreach ($students1 as $student) {
                    $user = User::find($student);

                    $elearningEvent->users()->attach(
                        $student,
                        [
                            'comment' => 'enroll from ' . $this->event->id . '||' . $this->eventsToEnrollExams,
                            'expiration' => $this->newExpiration ? Carbon::parse($this->newExpiration)->format('Y-m-d') : $monthsExp2,
                            'paid' => true,
                        ]
                    );
                }

                // string or null
                $newExpiration =
                    $this->newExpiration ?
                        Carbon::createFromFormat('d-m-Y', $this->newExpiration)->format('Y-m-d') :
                        null;

                // string or null
                $oldExpiration = $this->oldExpiration;

                Log::info('EnrollStudentsToElearningEvents@handle $oldExpiration: ' . $oldExpiration);
                Log::info('EnrollStudentsToElearningEvents@handle $newExpiration: ' . $newExpiration);
                Log::info('EnrollStudentsToElearningEvents@handle $newExpiration != $oldExpiration: ' . ($newExpiration != $oldExpiration ? 'true' : 'false'));
                Log::info('EnrollStudentsToElearningEvents@handle $this->statusChanged: ' . ($this->statusChanged ? 'true' : 'false'));

                //update expiration date for the users in case it's changed
                if ($newExpiration != $oldExpiration || $this->statusChanged) {
                    if ($this->newExpiration) {
                        DB::table('event_user')
                            ->whereIn('user_id', $students)
                            ->where('event_id', $eventToEnroll)
                            ->update([
                                'expiration' => Carbon::parse($this->newExpiration)->format('Y-m-d'),
                            ]);
                    } else {
                        DB::table('event_user')
                            ->whereIn('user_id', $students)
                            ->where('event_id', $eventToEnroll)
                            ->update([
                                'expiration' => date('Y-m-d', strtotime($monthsExp2, strtotime($this->event->completed_at))),
                            ]);
                    }
                }
            }

            $this->event->enroll = true;
            $this->event->save();
        }
    }
}
