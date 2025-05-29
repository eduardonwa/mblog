<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function show(Request $request, string $slug)
    {
        $post = Post::with([
            'category',
            'user',
            'tags:id,name,slug',
            'likes',
            'media',
            'comments' => function ($query) {
                $query->with(['commentator:id,name,created_at'])
                    ->latest();
            }
        ])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();
            
        $post->setAttribute('is_liked_by_user', $post->isLikedByUser());
        $post->setAttribute('likes_count', $post->likesCount());
    
        return Inertia::render('post/show', [
            'post' => $post->append('thumbnail_urls'),
            'meta' => [
                'title' => $post->meta_title ?? $post->title,
                'description' => $post->meta_description ?? $post->description,
                'author' => $post->user?->name,
            ],
        ]);
    }

    public function index()
    {
        $posts = Post::with('category', 'user')
            ->where('status', 'published')
            ->paginate(20);

        return Inertia::render('post/index', [
            'posts' => $posts,
        ]);
    }

    public function postByTag($slug)
    {
        $posts = Post::withAnyTags([$slug])
            ->with('tags:id,name,slug')
            ->where('status', 'published')
            ->paginate(20);
    
        return Inertia::render('post/tags', [
            'posts' => $posts
        ]);
    }

    public function postByAuthor(User $user)
    {
        $posts = Post::with(['category', 'tags', 'user'])
            ->where('user_id', $user->id)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('post/author', [
            'posts' => $posts,
            'author' => $user,
        ]);
    }
  
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
