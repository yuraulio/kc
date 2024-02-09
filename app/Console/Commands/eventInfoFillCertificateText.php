<?php

namespace App\Console\Commands;

use App\Model\Event;
use Illuminate\Console\Command;

class eventInfoFillCertificateText extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eventinfo:fillcertificatetext';

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
        $events = Event::all();

        foreach ($events as $event) {
            $info = $event->event_info1;

            if ($info->course_certification_type != null) {
                $info->course_certification_text = $info->course_certification_type;
            }

            if ($info->course_certification_name_success != null) {
                $info->has_certificate_exam = 1;
            } else {
                $info->has_certificate_exam = 0;
            }

            if ($info->course_certification_name_failure != null) {
                $info->course_certification_completion = $info->course_certification_name_failure;
            }

            $info->save();
        }

        return 0;
    }
}
