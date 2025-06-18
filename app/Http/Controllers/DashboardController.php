<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->loadCount(['posts', 'comments', 'likes']);
        
        $posts = Post::with(['user', 'category', 'likes'])
            ->where('user_id', Auth::id())
            ->latest()
            ->withCount('likes', 'comments')
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'extract' => $post->extract,
                    'published_at' => $post->smart_date,
                    'category' => $post->category->name,
                    'likes_count' => $post->likes_count,
                    'comments_count' => $post->comments_count,
                ];
            });

/*             dd([
                'posts_count' => $user->posts_count,
                'comments_count' => $user->comments_count,
                'likes_given_count' => $user->likes_count,
                'likes_received_count' => $user->likesReceivedCount()
            ]); */

            return Inertia::render('Dashboard', [
                'posts' => $posts,
                'user' => $user,
                'user_stats' => [
                    'posts_count' => $user->posts_count,
                    'comments_count' => $user->comments_count,
                    'likes_given_count' => $user->likes_count,
                    'likes_received_count' => $user->likesReceivedCount()
                ]
            ]);
        }
}
