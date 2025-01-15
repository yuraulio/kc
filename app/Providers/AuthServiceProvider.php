<?php

namespace App\Providers;

use App\Model\Admin\Admin;
use App\Model\Admin\Category;
use App\Model\Admin\Comment;
use App\Model\Admin\Page;
use App\Model\Admin\Setting;
use App\Model\Admin\Template;
use App\Model\Event;
use App\Model\Lesson;
use App\Model\LessonCategory;
use App\Model\Passport\Token;
use App\Model\Review;
use App\Model\Role;
use App\Model\Skill;
use App\Model\Tag;
use App\Model\Topic;
use App\Model\User;
use App\Policies\AdminPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CommentPolicy;
use App\Policies\EventPolicy;
use App\Policies\ItemPolicy;
use App\Policies\LessonCategoryPolicy;
use App\Policies\LessonPolicy;
use App\Policies\PagePolicy;
use App\Policies\ReviewPolicy;
use App\Policies\RolePolicy;
use App\Policies\SettingPolicy;
use App\Policies\SkillPolicy;
use App\Policies\TagPolicy;
use App\Policies\TemplatePolicy;
use App\Policies\TopicPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class           => UserPolicy::class,
        Event::class          => EventPolicy::class,
        Topic::class          => TopicPolicy::class,
        Lesson::class         => LessonPolicy::class,
        Category::class       => CategoryPolicy::class,
        Template::class       => TemplatePolicy::class,
        Page::class           => PagePolicy::class,
        Comment::class        => CommentPolicy::class,
        Admin::class          => AdminPolicy::class,
        Setting::class        => SettingPolicy::class,
        Role::class           => RolePolicy::class,
        LessonCategory::class => LessonCategoryPolicy::class,
        Tag::class            => TagPolicy::class,
        Skill::class          => SkillPolicy::class,
        Review::class         => ReviewPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

//        Passport::routes();
        Passport::useTokenModel(Token::class);
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(1));

        //Gate::define('manage-items', 'App\Policies\UserPolicy@manageItems');
        Gate::define('manage-users', 'App\Policies\UserPolicy@manageUsers');
        Gate::define('view', 'App\Policies\TransactionPolicy@view');
    }
}
