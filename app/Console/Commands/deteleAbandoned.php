<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\ShoppingCart;

class deteleAbandoned extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:abandoned';

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
        $shoppinCarts = ShoppingCart::all();


        foreach($shoppinCarts as $shoppingCart){
           
            $content = unserialize($shoppingCart->content);
            foreach($content as $c){
                if($c->associatedModel == 'PostRider\Content'){
                    $shoppingCart->delete();
                }
            }
            
        }

        return 0;
    }
}
