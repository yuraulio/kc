<?php

namespace App\Console\Commands;

use App\Model\Certificate;
use App\Model\Event;
use Illuminate\Console\Command;

class AttachCerficateByEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certificate:event {event}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $event = Event::find($this->argument('event'));

        $succedUsers = [1676, 1672, 1679, 1691, 1667, 1697, 1682, 1665, 1671, 1683, 1692, 1678, 1670, 1674, 1675, 1660, 1696, 1663, 1681, 1695, 1685, 1666];
        $failedUsers = [1684, 1690, 1686, 1661, 1687, 1152, 1680, 1669];

        if (!$event) {
            return -1;
        }

        if (!$event->exam->first()) {
            return -1;
        }

        $examId = $event->exam->first()->id;

        $date = $event->launch_date && $event->launch_date != '1970-01-01' ? date('Y-m-d', strtotime($event->launch_date)) : date('Y-m-d', strtotime($event->published_at));

        $users = $event->users;

        foreach ($users as $user) {
            if (!$exam = $user->hasExamResults($examId)) {
                continue;
            }

            if ($user->instructor->first() && in_array($event->id, $user->instructor->first()->event->pluck('id')->toArray())) {
                continue;
            }

            /*if(count($event->certificatesByUser($user->id)) > 0){
                continue;
            }*/

            if (!($cert = $event->certificatesByUser($user->id)->first())) {
                $cert = new Certificate;
                $cert->firstname = $user->firstname;
                $cert->lastname = $user->lastname;
                $cert->certificate_title = $event->certificate_title;
                $cert->show_certificate = true;
                $createDate = strtotime($date);
                $cert->create_date = $createDate;
                $cert->expiration_date = null;
                $cert->certification_date = date('F', $createDate) . ' ' . date('Y', $createDate);
                $cert->credential = get_certifation_crendetial2(date('m', $createDate) . date('y', $createDate));

                $cert->save();

                $cert->event()->save($event);
                $cert->user()->save($user);
                if ($event->exam()->first()) {
                    $cert->exam()->save($event->exam()->first());
                }
            }

            if (in_array($user->id, $succedUsers)) {
                $cert->template = 'kc_diploma_2022a';
                $cert->success = true;
            } elseif (in_array($user->id, $failedUsers)) {
                $cert->template = 'kc_attendance_2022a';
                $cert->success = false;
            } else {
                $cert->template = ($exam->score / $exam->total_score) >= 0.7 ? 'kc_diploma_2022a' : 'kc_attendance_2022a';
                $cert->success = ($exam->score / $exam->total_score) >= 0.7;
            }

            $cert->save();
        }

        return 0;
    }
}
