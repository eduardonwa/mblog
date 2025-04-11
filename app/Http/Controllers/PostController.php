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

        $post->load('media');
    
        return Inertia::render('post/show', [
            'post' => $post->append('thumbnail_urls'),
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
        try {
            $post = Post::findOrFail($postId);
            
            $post->likes()->firstOrCreate(['user_id' => Auth::id()]);
    
            return redirect()->back()->with([
                'likes_count' => $post->likes()->count()
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error en like: '.$e->getMessage());
            return back()->withErrors(['message' => 'Error al procesar el like']);
        }
    }
    
    public function unlike($postId)
    {
        try {
            $post = Post::findOrFail($postId);
            
            $post->likes()->where('user_id', Auth::id())->delete();
    
            return redirect()->back()->with([
                'likes_count' => $post->likes()->count()
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error en unlike: '.$e->getMessage());
            return back()->withErrors(['message' => 'Error al remover el like']);
        }
    }
}
