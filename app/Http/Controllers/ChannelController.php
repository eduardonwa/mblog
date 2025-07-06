<?php

namespace App\Http\Controllers;

use App\Traits\HandlesBotPreview;
use App\Models\Post;
use Inertia\Inertia;
use App\Models\Channel;
use App\Traits\HandlesLikes;
use Illuminate\Http\Request;
use App\Traits\HandlesComments;
use App\Traits\FetchesMentionableUsers;

class ChannelController extends Controller
{
    use HandlesLikes, HandlesComments, FetchesMentionableUsers, HandlesBotPreview;

    // listado de canales disponibles
    public function index()
    {
        return Inertia::render('channels/index', [
            'channels' => $this->getActiveChannels()
        ]);
    }

    // muestra el canal y sus posts asociados
    public function show($slug, Request $request)
    {
        $channel = Channel::where('slug', $slug)->firstOrFail();
        
        $posts = $channel->posts()
            ->with(['user:id,slug', 'media'])
            ->withCount(['likes', 'comments'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // dd($posts);
        
        if ($request->wantsJson() || $request->query('json')) {
            return $posts;
        }

        return Inertia::render('channels/show', [
            'channel' => $channel,
            'posts' => $posts,
        ]);
    }

    private function getActiveChannels()
    {
        return Channel::with('media')
            ->where('is_active', true)
            ->get();
    }

    // muestra un post relacionado con un canal
    public function showPost(Channel $channel, Post $post, Request $request)
    {
        if ($post->channel_id !== $channel->id) {
            abort(404);
        }

        $post->load(['channel', 'user', 'comments', 'likes']);

        $comments = $this->getCommentTreeForPost($post);

        $mentionableUsers = $this->getMentionableUsersForPost($post);

        // prepara metadatos
        $meta = [
            'title' => $post->meta_title ?? $post->title,
            'description' => $post->meta_description ?? $post->extract,
            'author' => $post->user?->name,
            'url' => route('post.show', $post->slug),
            'thumbnail' => $post->getFirstMediaUrl('thumbnails', 'lg_thumb'),
        ];

        // detecta si es un bot
        if ($this->isBot($request)) {
            return $this->respondWithBotPreview($meta, $post);
        }

        return Inertia::render('member/post/show', [
            'mentionableUsers' => $mentionableUsers,
            'comments' => $comments,
            'channel' => $channel,
            'post' => $post
        ]);
    }
}
