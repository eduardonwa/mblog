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
            'author',
            'tags:id,name,slug',
            'likes'
        ])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();
    
        $post->setAttribute('is_liked_by_user', $post->isLikedByUser());
        $post->setAttribute('likes_count', $post->likesCount());

        $post->thumbnail_url = $post->getFirstMediaUrl('thumbnails', 'lg_thumb');
    
        return Inertia::render('post/show', [
            'post' => $post,
            'meta' => [
                'title' => $post->meta_title ?? $post->title,
                'description' => $post->meta_description ?? $post->description,
                'author' => $post->author?->name,
            ],
        ]);
    }

    public function index()
    {
        $posts = Post::with('category', 'author')
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

    public function postByCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $posts = Post::with('category')
            ->where('category_id', $category->id)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('post/categories', [
            'posts' => $posts,
            'category' => $category
        ]);
    }

    public function postByAuthor(User $user)
    {
        $posts = Post::with(['category', 'tags', 'author'])
            ->where('author_id', $user->id)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        Log::debug("Posts encontrados: ".$posts->count());

        return Inertia::render('post/author', [
            'posts' => $posts,
            'author' => $user,
        ]);
    }
  
    public function like($postId)
    {
        // Debug inicial
        Log::debug('Inicio like', [
            'post_id' => $postId,
            'user_id' => auth()->id(),
            'ip' => request()->ip()
        ]);
    
        try {
            $post = Post::findOrFail($postId);
            
            // Verifica si ya existe el like
            $existingLike = $post->likes()
                ->where('user_id', auth()->id())
                ->first();
    
            if ($existingLike) {
                Log::debug('Like ya existente', ['like_id' => $existingLike->id]);
                return back();
            }
    
            // Crea el like
            $like = $post->likes()->create([
                'user_id' => auth()->id()
            ]);
    
            Log::debug('Like creado', [
                'like_id' => $like->id,
                'total_likes' => $post->likes()->count()
            ]);
    
            // Respuesta compatible con Inertia
            return redirect()->back()->with([
                'flash' => [
                    'message' => 'Like agregado',
                    'type' => 'success'
                ],
                'likes_count' => $post->likes()->count()
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error en like', [
                'error' => $e->getMessage(),
                'post_id' => $postId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors([
                'message' => 'Error al procesar el like'
            ]);
        }
    }

    public function unlike($postId)
    {
        Log::debug('Inicio unlike', [
            'post_id' => $postId,
            'user_id' => Auth::id()
        ]);

        try {
            $post = Post::findOrFail($postId);
            
            $deleted = $post->likes()
                ->where('user_id', Auth::id())
                ->delete();

            Log::debug('Unlike realizado', [
                'deleted_rows' => $deleted,
                'remaining_likes' => $post->likes()->count()
            ]);

            return redirect()->back()->with([
                'flash' => [
                    'message' => 'Like removido',
                    'type' => 'success'
                ],
                'likes_count' => $post->likes()->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error en unlike', [
                'error' => $e->getMessage(),
                'post_id' => $postId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors([
                'message' => 'Error al remover el like'
            ]);
        }
    }
}
