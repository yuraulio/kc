<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EventUserCommentDefaultExams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:usercommentdefaultexams';

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
        $commend = DB::table('event_user')->where('comment', 'LIKE', '%'.'enroll from'.'%')->get();

        foreach($commend as $com){
            DB::table('event_user')->where('id', $com->id)->update(['comment' => $com->comment.'||0']);
        }


        return 0;
    }
}
