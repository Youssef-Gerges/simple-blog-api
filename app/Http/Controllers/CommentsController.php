<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Comment;
use Throwable;

class CommentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function addComment(Request $request)
    {
        $this->validate($request, [
            'postId' => 'required|numeric|exists:posts,id',
            'content' => 'required'
        ]);
        $comment = new Comment();
        $comment->post_id = $request->postId;
        $comment->user_id = $request->user()->id;
        $comment->content = $request->content;
        $comment->save();
        return $comment;
    }

    public function deleteComment(Request $request)
    {
        $comment = Comment::findOrFail($request->commentId);
        if ($request->user()->id != $comment->user_id) {
            return response()->json(['message' => 'permission denied']);
        }
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    }

    public function editComment(Request $request)
    {
        $this->validate($request, [
            'commentId' => 'numeric|required',
            'content' => 'string|required',
        ]);
        $comment = Comment::findOrFail($request->commentId);

        if ($request->user()->id != $comment->user_id) {
            return response()->json(['message' => 'permission denied']);
        }
        $comment->content = $request->content;
        $comment->save();
        return response()->json(['message' => 'Comment edited successfully']);
    }
}
