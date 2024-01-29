<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddSendAutomateEmailStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:add_send_email_new_column_event_statistic';

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
        $event_statistics = DB::table('event_statistics')->select('id', 'videos')->get();
        //$event_statistics = DB::table('event_statistics')->where('id',3701)->select('id','videos')->get();

        foreach ($event_statistics as $key => $row) {
            $statistic_videos = json_decode($row->videos, true);

            foreach ($statistic_videos as $key => $video) {
                // if(!isset($video['is_new'])){
                $statistic_videos[$key]['send_automate_email'] = 0;
                //}
            }

            $statistic_videos = json_encode($statistic_videos);

            DB::table('event_statistics')->where('id', $row->id)->update(['videos' => $statistic_videos]);
        }

        return 0;
    }
}
