<?php

namespace App\Providers;

use App\Model\Item;
use App\Model\User;
use App\Model\Cashier as newCashier;
use App\Observers\ItemObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Auth;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        define('__ROOT__', dirname(dirname(__FILE__)));
        require_once(__ROOT__.'/helpers/functions.php');

    

        Item::observe(ItemObserver::class);
        User::observe(UserObserver::class);
        Cashier::useCustomerModel(User::class);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
