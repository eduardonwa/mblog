<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use BeyondCode\Comments\Comment;
use Illuminate\Support\Collection;
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
                $query->where('is_approved', true)
                    ->with('commentator')
                    ->with('comments.commentator');
            }
        ])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();

        // Obtener todos los IDs de usuarios que han comentado
        $commenterIds = collect();
        
        // IDs de comentarios principales
        $commenterIds = $commenterIds->merge(
            $post->comments->pluck('user_id')
        );
        
        // IDs de respuestas (comentarios anidados)
        foreach ($post->comments as $comment) {
            $commenterIds = $commenterIds->merge(
                $comment->comments->pluck('user_id')
            );
        }
        
        // Filtrar IDs únicos y eliminar nulos
        $commenterIds = $commenterIds->filter()->unique();

        // Obtener usuarios mencionables (solo los que han comentado)
        $mentionableUsers = User::whereIn('id', $commenterIds)
            ->select('id', 'name')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name
                ];
            })
            ->toArray();
            
        $post->setAttribute('is_liked_by_user', $post->isLikedByUser());
        $post->setAttribute('likes_count', $post->likesCount());
    
        return Inertia::render('post/show', [
            'post' => $post->append('thumbnail_urls'),
            'comments' => $post->comments->whereNull('parent_id'),
            'mentionableUsers' => $mentionableUsers,
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
