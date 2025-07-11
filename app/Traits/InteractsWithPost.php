<?php

namespace App\Traits;

use App\Models\Post;

trait InteractsWithPost
{
    protected function generatePostUrl(Post $post): string
    {
        if ($post->channel) {
            return url("/channel/{$post->channel->slug}/{$post->slug}");
        }

        return url("/posts/{$post->slug}");
    }
}