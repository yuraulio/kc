<?php

namespace Database\Seeders;

use CodexShaper\Menu\Models\Menu;
use CodexShaper\Menu\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MenuItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // clear existing darta
        DB::table('menus')->truncate();
        DB::table('menu_items')->truncate();

        // insert new data

        // main menu
        $menu = new Menu();
        $menu->name = 'Main menu';
        $menu->slug = Str::slug('Main menu');
        $menu->url = '/';
        $menu->order = 1;
        $menu->custom_class = 'Main menu';
        $menu->save();

        // main menu items
        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'IN-CLASS COURSES';
        $menuItem->slug = Str::slug('IN-CLASS COURSES');
        $menuItem->url = '/in-class-courses';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'VIDEO E-LEARNING COURSES';
        $menuItem->slug = Str::slug('VIDEO E-LEARNING COURSES');
        $menuItem->url = '/video-on-demand-courses';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'CORPORATE TRAINING';
        $menuItem->slug = Str::slug('CORPORATE TRAINING');
        $menuItem->url = '/corporate-training';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'BLOG';
        $menuItem->slug = Str::slug('BLOG');
        $menuItem->url = '/blog';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        // footer menu about
        $menu = new Menu();
        $menu->name = 'About';
        $menu->slug = Str::slug('About');
        $menu->url = '/';
        $menu->order = 2;
        $menu->custom_class = 'About';
        $menu->save();

        // footer menu about items
        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'ABOUT US';
        $menuItem->slug = Str::slug('ABOUT US');
        $menuItem->url = '/about';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'TRAINED BY US';
        $menuItem->slug = Str::slug('TRAINED BY US');
        $menuItem->url = '/brands-trained';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'IN THE MEDIA';
        $menuItem->slug = Str::slug('IN THE MEDIA');
        $menuItem->url = '/in-the-media';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'OUR INSTRUCTORS';
        $menuItem->slug = Str::slug('OUR INSTRUCTORS');
        $menuItem->url = '/instructors';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'BECOME AN INSTRUCTOR';
        $menuItem->slug = Str::slug('BECOME AN INSTRUCTOR');
        $menuItem->url = '/become-an-instructor';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'CORPORATE TRAINING';
        $menuItem->slug = Str::slug('CORPORATE TRAINING');
        $menuItem->url = '/corporate-training';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'BLOG';
        $menuItem->slug = Str::slug('BLOG');
        $menuItem->url = '/blog';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'JOBS';
        $menuItem->slug = Str::slug('JOBS');
        $menuItem->url = 'https://www.facebook.com/pg/KnowCrunch/jobs/';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'TERMS & CONDITIONS';
        $menuItem->slug = Str::slug('TERMS & CONDITIONS');
        $menuItem->url = '/terms';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'COOKIES NOTICE';
        $menuItem->slug = Str::slug('COOKIES NOTICE');
        $menuItem->url = '/cookies-notice';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'DATA PRIVACY POLICY';
        $menuItem->slug = Str::slug('DATA PRIVACY POLICY');
        $menuItem->url = '/data-privacy-policy';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'CONTACT US';
        $menuItem->slug = Str::slug('CONTACT US');
        $menuItem->url = '/contact';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        // footer menu education
        $menu = new Menu();
        $menu->name = 'Education';
        $menu->slug = Str::slug('Education');
        $menu->url = '/';
        $menu->order = 1;
        $menu->custom_class = 'Education';
        $menu->save();

        // footer menu education items
        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'IN-CLASS COURSES';
        $menuItem->slug = Str::slug('IN-CLASS COURSES');
        $menuItem->url = '/in-class-courses';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'E-LEARNING COURSES';
        $menuItem->slug = Str::slug('E-LEARNING COURSES');
        $menuItem->url = '/video-on-demand-courses';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        // footer menu students
        $menu = new Menu();
        $menu->name = 'Students';
        $menu->slug = Str::slug('Students');
        $menu->url = '/';
        $menu->order = 1;
        $menu->custom_class = 'Students';
        $menu->save();

        // footer menu students items
        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'MANAGE YOUR ACCOUNT';
        $menuItem->slug = Str::slug('MANAGE YOUR ACCOUNT');
        $menuItem->url = 'javascript:void(0)';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = 'account-menu';
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'OFFICIAL ALUMNI GROUP';
        $menuItem->slug = Str::slug('OFFICIAL ALUMNI GROUP');
        $menuItem->url = 'https://www.facebook.com/groups/KnowcrunchAlumni/';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'DIGITAL NATION GROUP';
        $menuItem->slug = Str::slug('DIGITAL NATION GROUP');
        $menuItem->url = 'https://www.facebook.com/groups/socialmediagreece/';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        // account menu
        $menu = new Menu();
        $menu->name = 'Account';
        $menu->slug = Str::slug('Account');
        $menu->url = '/';
        $menu->order = 1;
        $menu->custom_class = 'Account';
        $menu->save();

        // account menu items
        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'ACCOUNT';
        $menuItem->slug = Str::slug('ACCOUNT');
        $menuItem->url = '/myaccount';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();

        // account menu items
        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->title = 'LOGOUT';
        $menuItem->slug = Str::slug('LOGOUT');
        $menuItem->url = '/logout';
        $menuItem->parent_id = null;
        $menuItem->order = 1;
        $menuItem->route = null;
        $menuItem->params = null;
        $menuItem->middleware = null;
        $menuItem->controller = '\CodexShaper\Menu\Http\Controllers\MenuController@index';
        $menuItem->target = '_self';
        $menuItem->icon = null;
        $menuItem->custom_class = null;
        $menuItem->save();
    }
}
