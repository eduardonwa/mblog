<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\HandlesBotPreview;

class UserPublicProfileController extends Controller
{
    use HandlesBotPreview;

    public function index(User $user, Request $request)
    {
        $posts = Post::with(['category', 'tags', 'user', 'channel'])
            ->where('user_id', $user->id)
            ->where('status', 'published')
            ->withCount('comments', 'likes')
            ->orderBy('published_at', 'desc')
            ->paginate(12);
        
        if (request()->wantsJson()) {
            return response()->json($posts);
        }

        // prepara datos para SEO
        $meta = [
            'title' => "{$user->username}'s posts on Sick of Metal",
            'description' => ($user->bio ? Str::limit($user->bio, 160) : ''),
            'author' => $user?->username,
            'url' => route('author.posts', $user), // URL del perfil
            'image' => $user->avatar_url,
            'type' => 'profile'
        ];

        // detecta si es un bot
        if ($this->isBot($request)) {
            return $this->respondWithBotPreview($meta, [
                'user' => $user
            ]);
        }

        return Inertia::render('member/profile/index', [
            'posts' => $posts,
            'author' => $user,
        ]);
    }
}
