<?php

namespace App\Traits;

use App\Models\Post;
use App\Notifications\PostLiked;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

trait HandlesLikes
{
    public function like($postId)
    {
        try {
            $post = Post::with('user', 'channel')->findOrFail($postId);
            $post->likes()->firstOrCreate(['user_id' => Auth::id()]);

            if ($post->user_id !== Auth::id()) {
                /* Log::info('Notificando al autor del post por like.', [
                    'autor_id' => $post->user_id,
                    'liker_id' => Auth::id(),
                    'post_id' => $post->id,
                ]); */

                $post->user->notify(new PostLiked(Auth::user(), $post));
            }
    
            return response()->json([
                'success' => true,
                'likes_count' => $post->likes()->count(),
                'is_liked_by_user' => true
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error en like: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing uphail'
            ], 500);
        }
    }
    
    public function unlike($postId)
    {
        try {
            $post = Post::findOrFail($postId);
            $post->likes()->where('user_id', Auth::id())->delete();
    
            return response()->json([
                'success' => true,
                'likes_count' => $post->likes()->count(),
                'is_liked_by_user' => false
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error en unlike: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error removing uphail'
            ], 500);
        }
    }
}