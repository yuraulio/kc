<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\User;
use App\Model\Event;
use App\Model\Transaction;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportAllUserByCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:user {category}';

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

        /*$users = User::whereHas('events',function($event){

            $event->whereHas('category', function($category){
                return $category->where('categoryables.category_id',$this->argument('category'));
            });

        })->get();*/

        $users = User::whereHas('transactions',function($transaction){

            $transaction->whereHas('event', function($event){
                $event->whereHas('category', function($category){
                    return $category->where('categoryables.category_id',$this->argument('category'));
                });
            });

        })->get();


        $columns = array("ID", "First Name", "Last Name", "email",'Mobile');

        $file = fopen('users.csv', 'w');
        fputcsv($file, $columns);

        foreach($users as $user){
            fputcsv($file, array($user->id, $user->firstname,  $user->lastname, $user->email,$user->mobile));
        }

        fclose($file);


        return 0;
    }
}
