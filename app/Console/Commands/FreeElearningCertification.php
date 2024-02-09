<?php

namespace App\Console\Commands;

use App\Model\Event;
use App\Model\User;
use Illuminate\Console\Command;

class FreeElearningCertification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'free:certificates';

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
        $users = User::whereHas('events')->get();

        foreach ($users as $user) {
            foreach ($user->events as $event) {
                if ($event->view_tpl == 'elearning_free') {
                    if ($event->userHasCertificate($user)->first()) {
                        continue;
                    }

                    $event->certification($user);
                } elseif (count($event->getExams()) == 0 && ($cert = $event->userHasCertificate($user->id)->first())) {
                    $cert->template = 'new_kc_certificate';
                    $cert->save();
                }
            }
        }

        return 0;
    }
}
