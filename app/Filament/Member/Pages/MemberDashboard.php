<?php

namespace App\Filament\Member\Pages;

use App\Models\Post;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class MemberDashboard extends Page
{
    protected static string $panel = 'member';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.member.pages.member-dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    protected ?string $heading = 'Welcome';
    
    public function getViewData(): array
    {
        $user = Auth::user()->loadCount(['posts', 'comments', 'likes']);

        $posts = Post::with(['user', 'category', 'likes'])
            ->where('user_id', Auth::id())
            ->latest()
            ->withCount('likes', 'comments')
            ->get();

        return [
            'user' => $user,
            'posts' => $posts,
            'user_stats' => [
                'posts_count' => $user->posts_count,
                'comments_count' => $user->comments_count,
                'likes_given_count' => $user->likes_count,
                'likes_received_count' => $user->likesReceivedCount(),
            ],
        ];
    }
}
