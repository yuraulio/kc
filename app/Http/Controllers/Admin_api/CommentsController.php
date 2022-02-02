<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdminTemplateRequest;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateAdminTemplateRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\TemplateResource;
use App\Model\Admin\Comment;
use App\Model\Admin\Template;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentsController extends Controller
{

    /**
     * Get comments
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Comment::class, Auth::user());

        try {
            $comments = Comment::lookForOriginal($request->filter)->with(["page", "user"])->orderBy("created_at", "desc")->paginate(100);
            return CommentResource::collection($comments);
        } catch (Exception $e) {
            Log::error("Failed to get comments. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Add comment
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

            return redirect()->back();
        } catch (Exception $e) {
            Log::error("Failed to add new comment. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Delete comment
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
            Log::error("Failed to delete comment. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
