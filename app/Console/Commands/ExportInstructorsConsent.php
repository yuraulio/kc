<?php

namespace App\Console\Commands;

use App\Model\Instructor;
use Illuminate\Console\Command;

class ExportInstructorsConsent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export_instructor_consent';

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
        $file = fopen('inststructor_consent.csv', 'w');
        $columns = ['Firstname', 'Lastname', 'Email', 'Consent'];
        fputcsv($file, $columns);

        $instructors = Instructor::with('user')->get();

        foreach ($instructors as $instructor) {
            if (isset($instructor['user'][0])) {
                $user = $instructor['user'][0];
                $consent = null;

                if ($user['consent'] != null) {
                    $consent = json_decode($user['consent'], true);

                    if ($consent['date']) {
                        fputcsv($file, [$user['firstname'], $user['lastname'], $user['email'], $consent['date']]);
                    } else {
                        fputcsv($file, [$user['firstname'], $user['lastname'], $user['email'], '']);
                    }
                } else {
                    fputcsv($file, [$user['firstname'], $user['lastname'], $user['email'], '']);
                }
            }
        }

        // foreach($categories as $category){
        //     foreach($category->events as $event){

        //         fputcsv($file, array($event->id, $event->title,  date('d-m-Y',strtotime($event->published_at))));
        //     }
        // }

        fclose($file);

        return 0;
    }
}
