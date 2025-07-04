<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
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

    // muestra un post dentro de un canal
    public function showPost(Channel $channel, Post $post)
    {
        if ($post->channel_id !== $channel->id) {
            abort(404);
        }

        return Inertia::render('member/post/show', [
            'channel' => $channel,
            'post' => $post->load('channel')
        ]);
    }
}
