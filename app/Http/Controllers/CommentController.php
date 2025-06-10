<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CustomComment;
use App\Notifications\UserMention;
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
        $comment = CustomComment::findOrFail($comment);
        
        if (Auth::id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }

    public function storeReply(Request $request, $commentId)
    {
        $parentComment = CustomComment::findOrFail($commentId);

        // Validación básica
        $validated = $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        // Crea la réplica manualmente para controlar todos los campos
        $reply = new CustomComment([
            'comment' => $validated['comment'],
            'commentable_type' => $parentComment->commentable_type,
            'commentable_id' => $parentComment->commentable_id,
            'comment_id' => $parentComment->id,
            'user_id' => Auth::id(),
            'is_approved' => true
        ]);

        $reply->save();

        // Opcional: Reconstruir el árbol si es necesario
        CustomComment::fixTree();

        // Si necesitas aprobación automática:
        $reply->update(['is_approved' => true]);
        
        // Detectar menciones (si lo tienes implementado)
        $this->detectAndNotifyMentions($request, $reply);

        return back()->with('success', 'Reply added.');
    }

    protected function detectAndNotifyMentions(Request $request, $comment)
    {
        preg_match_all('/@([\w]+)/', $request->reply, $matches);

        if (!empty($matches[1])) {
            $mentionedUsers = User::whereIn('name', $matches[1])->get();

            foreach ($mentionedUsers as $user) {
                $user->notify(new UserMention(Auth::user()));
            }
        }
    }
}
