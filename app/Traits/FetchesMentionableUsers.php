<?php

namespace App\Traits;

use App\Models\Post;
use App\Models\User;

trait FetchesMentionableUsers
{
    protected function getMentionableUsersForPost(Post $post): array
    {
        return User::whereHas('comments', function ($query) use ($post) {
                $query->where('commentable_type', Post::class)
                      ->where('commentable_id', $post->id);
            })
            ->select('id', 'slug')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'slug' => $user->slug
                ];
            })
            ->toArray();
    }
}
