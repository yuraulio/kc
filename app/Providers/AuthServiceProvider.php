<?php

namespace App\Providers;

use App\Model\Admin\Admin;
use App\Model\Admin\Category;
use App\Model\Admin\Comment;
use App\Model\Admin\Page;
use App\Model\Admin\Template;
use App\Model\User;
use App\Policies\AdminPolicy;
use App\Policies\TagPolicy;
use App\Policies\ItemPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CommentPolicy;
use App\Policies\PagePolicy;
use App\Policies\TemplatePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Category::class => CategoryPolicy::class,
        Template::class => TemplatePolicy::class,
        Page::class => PagePolicy::class,
        Comment::class => CommentPolicy::class,
        Admin::class => AdminPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(1));

        //Gate::define('manage-items', 'App\Policies\UserPolicy@manageItems');
        Gate::define('manage-users', 'App\Policies\UserPolicy@manageUsers');
        Gate::define('view', 'App\Policies\TransactionPolicy@view');
    }
}
