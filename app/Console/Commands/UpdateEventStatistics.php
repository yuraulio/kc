<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Jobs\UpdateStatisticJson;
use Illuminate\Support\Facades\Log;

class UpdateEventStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:event-statistics';

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
        $events = DB::table('event_statistics_queue')->whereRunning(true)->count();

        if($events > 0 ){
            return 0;
        }

        $events = DB::table('event_statistics_queue')->orderBy('created_at','asc')->get();
        $now = Carbon::parse(date('Y-m-d H:i:s'));
        $running = false;

        foreach($events as $event){

            if($running){
                continue;
            }

            $updated_event = Carbon::parse($event->updated_at);

            $diff_in_minutes = $now->diffInMinutes($updated_event);

            try{

                if($diff_in_minutes >= 10){
                   
                    DB::table('event_statistics_queue')->where('event_id', $event->event_id)->update(['running' => true]);
                    $running = true;

                    $has_updated = new UpdateStatisticJson($event->event_id);

                    if($has_updated->execute != null){
                        DB::table('event_statistics_queue')->where('event_id', $event->event_id)->delete();
                    }else{
                        DB::table('event_statistics_queue')->where('event_id', $event->id)->update(['running' => false]);
                        Log::info('UpdateEventStatistics Command -> This event id: '.$event->event_id.', do not exist!!');
                    }

                }

            }catch(exception $e){
                DB::table('event_statistics_queue')->where('event_id', $event->event_id)->update(['running' => false]);
                echo $e;
            }

        }

        return 0;
    }
}
