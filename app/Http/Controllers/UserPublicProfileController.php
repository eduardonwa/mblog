<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class UserPublicProfileController extends Controller
{
    public function index(User $user)
    {
        $posts = Post::with(['category', 'tags', 'user'])
            ->where('user_id', $user->id)
            ->where('status', 'published')
            ->withCount('comments', 'likes')
            ->orderBy('published_at', 'desc')
            ->paginate(12);
        
        if (request()->wantsJson()) {
            return response()->json($posts);
        }

        return Inertia::render('member/profile/index', [
            'posts' => $posts,
            'author' => $user,
        ]);
    }
}
