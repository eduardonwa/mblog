<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use App\Traits\HandlesLikes;
use Illuminate\Http\Request;
use App\Traits\HandlesComments;
use App\Traits\HandlesBotPreview;
use App\Traits\FetchesMentionableUsers;

class PostController extends Controller
{
    use HandlesLikes, HandlesComments, FetchesMentionableUsers, HandlesBotPreview;

    public function show(Request $request, string $slug)
    {
        $post = Post::with([
            'category',
            'user',
            'tags:id,name,slug',
            'likes',
            'media'
        ])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();

        // Cargar comentarios principales y sus descendientes
        $comments = $this->getCommentTreeForPost($post);
        
        // Obtener usuarios mencionables
        $mentionableUsers = $this->getMentionableUsersForPost($post);

        // preparar metadatos
        $meta = [
            'title' => $post->meta_title ?? $post->title,
            'description' => $post->meta_description ?? $post->extract,
            'author' => $post->user?->name,
            'url' => route('post.show', $post->slug),
            'thumbnail' => $post->getFirstMediaUrl('thumbnails', 'lg_thumb'),
        ];

        // respuesta si es un bot
        if ($this->isBot($request)) {
            return $this->respondWithBotPreview($meta, $post);
        }

        return Inertia::render('post/show', [
            'post' => $post->append('thumbnail_urls'),
            'comments' => $comments,
            'mentionableUsers' => $mentionableUsers,
            'meta' => $meta,
            'url' => route('post.show', $post->slug)
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
}
