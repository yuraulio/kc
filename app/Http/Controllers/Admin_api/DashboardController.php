<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PageResource;
use App\Model\Admin\Comment;
use App\Model\Admin\Page;
use App\Model\Instructor;
use App\Model\User;
use App\Model\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DashboardController extends Controller
{
    /**
     * Return user count
     *
     * @return JsonResponse
     */
    public function get_widget_data_users(): JsonResponse
    {
        return response()->json(['data' => User::count()], 200);
    }

    /**
     * Return admin count
     *
     * @return JsonResponse
     */
    public function get_widget_data_admins(): JsonResponse
    {
        $users = User::all();

        $adminUsers = User::whereHas('role', function ($q) {
            return $q->where('roles.id', 1);
        })->count();

        return response()->json(['data' => $adminUsers], 200);
    }

    /**
     * Return active students count
     *
     * @return JsonResponse
     */
    public function get_widget_data_students(): JsonResponse
    {


        $students_in_class = User::whereHas('events_for_user_list1', function ($q) {
            $q->wherePublished(true)->whereStatus(0)->where(function ($q1) {
                $q1->doesntHave('delivery')->OrWhereHas('delivery', function ($q2) {
                    return $q2->where('deliveries.id', '<>', 143);
                });
            });
        })->count();

        $students_online = User::whereHas('events_for_user_list1', function ($q) {

            $q->wherePublished(true)->whereStatus(0)->where('event_user.expiration', '>=',date('Y-m-d'))->whereHas('delivery', function ($q1) {
                return $q1->where('deliveries.id', 143);
            });
        })->count();


        return response()->json(['data' => [
            $students_in_class,
            $students_online
        ]], 200);
    }

    public function get_widget_data_students_all(): JsonResponse
    {

        $students_in_class = User::whereHas('events_for_user_list1', function ($q) {
            $q->wherePublished(true)->where('event_user.paid', true)->where(function ($q1) {
                $q1->doesntHave('delivery')->OrWhereHas('delivery', function ($q2) {
                    return $q2->where('deliveries.id', '<>', 143);
                });
            });
        })->count();

        $students_online = User::whereHas('events_for_user_list1', function ($q) {

            $q->wherePublished(true)->where('event_user.paid', true)->whereHas('delivery', function ($q1) {
                return $q1->where('deliveries.id', 143);
            });
        })->count();


        return response()->json(['data' => [
            $students_in_class,
            $students_online
        ]], 200);
    }

    public function get_widget_data_instructors(): JsonResponse
    {
        $instructors_in_class = [];
        $instructors_online = [];
        $all_instructors = 0;

        $instructors_in_class = Instructor::whereStatus(true)->whereHas('event', function($q){
            $q->whereStatus(0)->doesntHave('delivery')
                ->OrWhereHas('delivery', function ($q2) {
                    return $q2->where('deliveries.id', '<>', 143);
                });
        })->count();



        $instructors_online = Instructor::whereStatus(true)->whereHas('event', function($q){
            $q->whereStatus(0)
                ->WhereHas('delivery', function ($q2) {
                    return $q2->where('deliveries.id', 143);
                });
        })->count();

        $all_instructors = Instructor::whereStatus(true)->has('event')->count();



        return response()->json(['data' => [
            $instructors_in_class,
            $instructors_online,
            $all_instructors
        ]], 200);
    }

    /**
     * Return active graduates count
     *
     * @return JsonResponse
     */
    public function get_widget_data_graduates(): JsonResponse
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

    /**
     * Return last 5 comments
     *
     * @return JsonResponse
     */
    public function get_widget_comments(): AnonymousResourceCollection
    {
        $comments = Comment::orderBy("created_at", "desc")->limit(5)->get();

        return CommentResource::collection($comments);
    }

    /**
     * Return last 5 comments
     *
     * @return JsonResponse
     */
    public function get_widget_pages(): AnonymousResourceCollection
    {
        $pages = Page::orderBy("created_at", "desc")->limit(5)->get();

        return PageResource::collection($pages);
    }
}
