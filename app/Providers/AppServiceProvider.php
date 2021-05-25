<?php

namespace App\Providers;

use App\Model\Item;
use App\Model\User;
use App\Observers\ItemObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

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
        require_once(__ROOT__.'/helpers\functions.php');

        Item::observe(ItemObserver::class);
        User::observe(UserObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
