<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Model\Instructor;
use App\Model\User;
use App\Model\Event;

class Dashboard extends Controller
{
    /**
     * Return user count
     *
     * @return \Illuminate\View\View
     */
    public function get_widget_data_users()
    {
        return response()->json(['data' => User::count()], 200);
    }

    /**
     * Return admin count
     *
     * @return \Illuminate\View\View
     */
    public function get_widget_data_admins()
    {
        $users = User::all();

        $adminUsers = 0;
        foreach ($users as $user) {
            if ($user->isAdmin()) {
                $adminUsers += 1;
            }
        }

        return response()->json(['data' => $adminUsers], 200);
    }

    /**
     * Return active instructors count
     *
     * @return \Illuminate\View\View
     */
    public function get_widget_data_instructors()
    {
        $instructors = Instructor::where('status', true)->has('event')->get();

        return response()->json(['data' => count($instructors)], 200);
    }

    /**
     * Return active students count
     *
     * @return \Illuminate\View\View
     */
    public function get_widget_data_students()
    {
        $students_in_class = [];
        $students_online = [];
        $events = Event::where('published', true)->with('delivery', 'type', 'users')->get();

        foreach ($events as $event) {
            if ($event->delivery->first() && $event->delivery->first()->id == 143) {
                //dd($event->users()->pluck('user_id')->toArray());
                $students_online = array_merge($students_online, $event->users()->pluck('user_id')->toArray());
            } elseif ($event->delivery->first() && $event->delivery->first()->id == 139) {
                $students_in_class = array_merge($students_in_class, $event->users()->pluck('user_id')->toArray());
            }
        }
        return response()->json(['data' => [count(array_unique($students_in_class)), count(array_unique($students_online))]], 200);
    }

    /**
     * Return active graduates count
     *
     * @return \Illuminate\View\View
     */
    public function get_widget_data_graduates()
    {
        $graduates = [];
        $events = Event::where('published', true)->with('delivery', 'type', 'users')->get();

        foreach ($events as $event) {
            if (count($event->type()->whereIn('id', [13,14])->get()) > 0) {
                $graduates = array_merge($graduates, $event->users()->pluck('user_id')->toArray());
            }
        }

        return response()->json(['data' => count(array_unique($graduates))], 200);
    }
}
