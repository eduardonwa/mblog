<?php

namespace App\Filament\Member\Pages;

use App\Models\Post;
use Filament\Pages\Dashboard;
use Illuminate\Support\Facades\Auth;

class MemberDashboard extends Dashboard
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

        $notifications = Auth::user()->notifications()->latest()->limit(10)->get();
        $unreadCount = Auth::user()->unreadNotifications()->count();

        return [
            'user' => $user,
            'posts' => $posts,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'user_stats' => [
                'posts_count' => $user->posts_count,
                'comments_count' => $user->comments_count,
                'likes_given_count' => $user->likes_count,
                'likes_received_count' => $user->likesReceivedCount(),
            ],
        ];
    }
}
