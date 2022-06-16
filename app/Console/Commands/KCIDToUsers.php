<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\User;
use App\Model\Option;

class KCIDToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:kcid';

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

        $users = User::where('kc_id','')->whereHas('events')->get();

        $KC = "KC-";
        $time = strtotime(date('Y-m-d'));
        $MM = date("m",$time);
        $YY = date("y",$time);

        foreach($users as $user){
           

            $optionKC = Option::where('abbr','website_details')->first();
		    $next = $optionKC->value;

            $next_kc_id = str_pad($next, 4, '0', STR_PAD_LEFT);
            $knowcrunch_id = $KC.$YY.$MM.$next_kc_id;

            $user->kc_id = $knowcrunch_id;
            $user->save();

            if ($next == 9999) {
                $next = 1;
            }
            else {
                $next = $next + 1;
            }
    
            $optionKC->value=$next;
            $optionKC->save();
        }
        

        return 0;
    }
}
