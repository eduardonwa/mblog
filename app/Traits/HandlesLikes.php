<?php

namespace App\Traits;

use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

trait HandlesLikes
{
    public function like($postId)
    {
        try {
            $post = Post::findOrFail($postId);
            $post->likes()->firstOrCreate(['user_id' => Auth::id()]);
    
            return response()->json([
                'success' => true,
                'likes_count' => $post->likes()->count(),
                'is_liked_by_user' => true
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error en like: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el like'
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
                'message' => 'Error al remover el like'
            ], 500);
        }
    }
}