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
     * Return active instructors count
     *
     * @return JsonResponse
     */
    public function get_widget_data_instructors(): JsonResponse
    {
        $instructors = Instructor::where('status', true)->has('event')->get();

        return response()->json(['data' => count($instructors)], 200);
    }

    /**
     * Return active students count
     *
     * @return JsonResponse
     */
    public function get_widget_data_students(): JsonResponse
    {
        $students_in_class = [];
        $students_online = [];
        $events = Event::where('published', true)->with('delivery', 'type', 'users')->get();

        foreach ($events as $event) {
            if ($event->delivery->first() && $event->delivery->first()->id == 143) {
                //dd($event->users()->pluck('user_id')->toArray());
                $students_online = array_merge($students_online, $event->users()->pluck('user_id')->toArray());
            } else {
                $students_in_class = array_merge($students_in_class, $event->users()->pluck('user_id')->toArray());
            }
        }
        return response()->json(['data' => [count(array_unique($students_in_class)), count(array_unique($students_online))]], 200);
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
