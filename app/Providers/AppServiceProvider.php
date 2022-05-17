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
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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

        Validator::extend('uniqueNameAndParent', function ($attribute, $value, $parameters, $validator) {
            $count = DB::table('cms_folders')->where('name', $value)
                                        ->where('parent_id', $parameters[0])
                                        ->count();
        
            return $count === 0;
        });

        Item::observe(ItemObserver::class);
        User::observe(UserObserver::class);
        Cashier::useCustomerModel(User::class);
        Paginator::useBootstrap();
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
