<?php

namespace App\Providers;

use App\View\Components\AnnualSubscriptionComponent;
use App\View\Components\StudentsCourseComponent;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blade::component('students-course-component', StudentsCourseComponent::class);
        Blade::component('annual-subscription-component', AnnualSubscriptionComponent::class);
    }
}
