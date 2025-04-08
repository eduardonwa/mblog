<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $staffPosts = Post::with('category', 'author')
            ->where('status', 'published')
            ->staffPosts()
            ->paginate(5);

        return Inertia::render('Welcome', [
            'staffPosts' => $staffPosts,
        ]);
    }
}
