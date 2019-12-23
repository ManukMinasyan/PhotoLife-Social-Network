<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $comment = new Comment();
        $comment->body = $request->get('body');

        if ($request->has('parent_id')) {
            $comment->parent_id = $request->get('parent_id');
        }

        $comment->author()->associate(Auth::user());
        $post->comments()->save($comment);

        return new CommentResource($comment->load('replies'));
    }

    /**
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(Comment $comment)
    {
        Auth::user()->toggleLike($comment);

        return response()->json(['success' => true]);
    }
}
