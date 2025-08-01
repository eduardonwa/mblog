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
use App\Traits\HandlesPostViews;

class ChannelController extends Controller
{
    use
        HandlesLikes,
        HandlesComments,
        FetchesMentionableUsers,
        HandlesBotPreview,
        HandlesPostViews;

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
            ->with(['user:id,username', 'media', 'channel'])
            ->withCount(['likes', 'comments'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(12);
        
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
        
        if ($post->post_template === 'list') {
            $post->rendered_body = $post->list_data_html;
        } else {
            $post->rendered_body = $post->body;
        }
        
        $comments = $this->getCommentTreeForPost($post);
        $mentionableUsers = $this->getMentionableUsersForPost($post);
        
        // prepara metadatos
        $meta = [
            'title' => $post->meta_title ?? $post->title,
            'description' => $post->meta_description ?? $post->extract,
            'author' => $post->user?->name,
            'url' => route('post.show', $post->slug),
            'thumbnail' => $post->getFirstMediaUrl('thumbnails', 'lg_thumb'),
            'type' => 'article'
        ];

        // detecta si es un bot
        if ($this->isBot($request)) {
            return $this->respondWithBotPreview($meta, [
                'post' => $post
            ]);
        }

        // incrementar vistas del post
        $this->incrementPostViewCount($post);

        return Inertia::render('member/post/show', [
            'post' => $post,
            'comments' => $comments,
            'mentionableUsers' => $mentionableUsers,
            'channel' => $channel,
            'url' => route('channel.post.show', ['channel' => $post->channel->slug, 'post' => $post->slug]),
        ]);
    }
}
