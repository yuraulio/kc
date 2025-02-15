<?php

namespace App\Providers;

use App\Contracts\Api\v1\Event\IEventSettingsService;
use App\Contracts\Api\v1\Event\IEventStatistic;
use App\Contracts\Api\v1\Lesson\ILessonService;
use App\Contracts\Api\v1\Topic\ITopicService;
use App\Model\Activation;
use App\Model\Cashier as newCashier;
use App\Model\Event;
use App\Model\Item;
use App\Model\User;
use App\Observers\ActionObserver;
use App\Observers\EventObserver;
use App\Observers\ItemObserver;
use App\Observers\UserObserver;
use App\Services\Event\EventFileService;
use App\Services\Event\EventSettingsService;
use App\Services\Event\EventStatisticService;
use App\Services\Lesson\LessonService;
use App\Services\Topic\TopicService;
use Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use View;
use Vimeo\Vimeo;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // define('__ROOT__', dirname(dirname(__FILE__)));
        require_once dirname(dirname(__FILE__)) . '/helpers/functions.php';

        Validator::extend('uniqueNameAndParent', function ($attribute, $value, $parameters, $validator) {
            $count = DB::table('cms_folders')->where('name', $value)
                ->where('parent_id', $parameters[0])
                ->count();

            return $count === 0;
        });

        Validator::extend('check_array_first_value_is_numeric', function ($attribute, $value, $parameters, $validator) {
            if (gettype($value) == 'array') {
                if (is_array($value) && count($value) > 0) {
                    $firstElement = $value[0];

                    return is_numeric($firstElement);
                }
            } else {
                return true;
            }
        });

        Item::observe(ItemObserver::class);
        User::observe(UserObserver::class);
        Event::observe(EventObserver::class);
        Activation::observe(ActionObserver::class);
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
        // Configure our Vimeo client with credentials
        $this->app->bind(Vimeo::class, function () {
            return new Vimeo(
                config('app.vimeo_client_id'),
                config('app.vimeo_client_secret'),
                config('app.vimeo_token')
            );
        });

        $this->app->bind(IEventStatistic::class, function ($app) {
            return new EventStatisticService();
        });

        $this->app->bind(IEventSettingsService::class, function ($app) {
            return new EventSettingsService(new EventFileService());
        });

        $this->app->bind(ITopicService::class, function ($app) {
            return new TopicService();
        });

        $this->app->bind(ILessonService::class, function ($app) {
            return new LessonService();
        });
    }
}
