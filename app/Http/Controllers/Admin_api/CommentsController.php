<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdminTemplateRequest;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateAdminTemplateRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\TemplateResource;
use App\Jobs\DeleteMultipleComments;
use App\Model\Admin\Comment;
use App\Model\Admin\Page;
use App\Model\Admin\Template;
use App\Model\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentsController extends Controller
{
    /**
     * Get comments.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Comment::class, Auth::user());

        try {
            $comments = Comment::tableSort($request);

            $comments = $this->filters($request, $comments);

            $comments = $comments->paginate($request->per_page ?? 50);

            return CommentResource::collection($comments);
        } catch (Exception $e) {
            Log::error('Failed to get comments. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    private function filters($request, $comments)
    {
        $comments->lookForOriginal($request->filter)->with(['page', 'user']);
        if ($request->pagefilter !== null) {
            $comments->whereHas('page', function ($q) use ($request) {
                $q->where('id', $request->pagefilter);
            });
        }

        return $comments;
    }

    /**
     * Add comment.
     *
     * @return CommentResource
     */
    public function store(CreateCommentRequest $request)
    {
        $this->authorize('create', Comment::class, Auth::user());

        try {
            $comment = new Comment();
            $comment->comment = $request->comment;
            $comment->page_id = $request->page_id;
            $comment->user_id = Auth::user()->id;
            $comment->save();

            return new CommentResource($comment);
        } catch (Exception $e) {
            Log::error('Failed to add new comment. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Delete comment.
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $comment = Comment::find($id);

            $this->authorize('delete', $comment, Auth::user());

            $comment->delete();

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error('Failed to delete comment. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function getPageComments($page_id)
    {
        $comments = Comment::with(['page', 'user'])->where('page_id', $page_id)->orderBy('created_at', 'desc')->limit(500)->get();

        return CommentResource::collection($comments);
    }

    public function deleteMultiple(Request $request)
    {
        try {
            $ids = $request->selected;

            // authorize action
            $comments = Comment::findOrFail($ids);
            foreach ($comments as $comment) {
                $this->authorize('delete', $comment, Auth::user());
            }

            // start job
            DeleteMultipleComments::dispatch($ids, Auth::user());

            return response()->json(['message' => 'success'], 200);
        } catch (Exception $e) {
            Log::error('Failed to bulk delete comments. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function widgets(Request $request)
    {
        return [
            [
                'Comments',
                $this->commentCount($request),
            ],
            [
                'Last comment',
                $this->lastCommentCreated($request),
            ],
            // [
            //     "Popular page",
            //     $this->popularPage($request),
            // ],
            // [
            //     "Popular user",
            //     $this->popularUser($request),
            // ]

        ];
    }

    public function commentCount($request)
    {
        $comments = Comment::tableSort($request);
        $comments = $this->filters($request, $comments);

        return $comments->count();
    }

    public function popularPage($request)
    {
        try {
            $pages = Page::withoutGlobalScope('published');

            if ($request->pagefilter !== null) {
                $pages->whereId($request->pagefilter);
            }
            $pages = $pages->get();

            return $pages->sortByDesc(function ($page) {
                return $page->comments()->count();
            })->first()->title ?? '-';
        } catch (Exception $e) {
            Log::warning('(comments widget) Failed to find popular comments page. ' . $e->getMessage());

            return '-';
        }
    }

    public function lastCommentCreated($request)
    {
        try {
            $comments = Comment::tableSort($request);
            $comments = $this->filters($request, $comments);
            $comments = $comments->orderByDesc('created_at')->first();

            return $comments->created_at->diffForHumans();
        } catch (Exception $e) {
            Log::warning('(comments widget) Failed to find last comment created. ' . $e->getMessage());

            return '-';
        }
    }

    public function popularUser($request)
    {
        try {
            $user = User::with('usersComments')->get()->sortByDesc(function ($user) use ($request) {
                $data = $user->usersComments;
                if ($request->pagefilter !== null) {
                    $data = $data->where('page_id', $request->pagefilter);
                }

                return $data->count();
            })->first();

            return $user->firstname . ' ' . $user->lastname;
        } catch (Exception $e) {
            Log::warning('(comments widget) Failed to find user with most comments. ' . $e->getMessage());

            return '-';
        }
    }
}
