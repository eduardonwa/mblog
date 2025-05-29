<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use BeyondCode\Comments\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        if (!$request->user()->hasVerifiedEmail()) {
            return back()->with('error', 'You must verify your email before commenting.');
        }

        $request->validate(['comment' => 'required|string']);
        $comment = $post->commentAsUser(Auth::user(), $request->comment);
        $comment->approve();

        return back()->with('success', 'Comment added.');
    }

    public function destroy($comment)
    {
        $comment = Comment::findOrFail($comment);
        
        if (Auth::id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }

    public function storeReply(Request $request, Comment $comment)
    {
        if (!$request->user()->hasVerifiedEmail()) {
            return back()->with('error', 'You must verify your email before replying.');
        }

        $request->validate(['reply' => 'required|string']);
        
        $reply = $comment->commentAsUser(Auth::user(), $request->reply);
        $reply->approve();

        return back()->with('success', 'Reply added.');
    }
}
