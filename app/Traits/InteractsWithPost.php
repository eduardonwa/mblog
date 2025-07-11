<?php

namespace App\Traits;

use App\Models\Post;

trait InteractsWithPost
{
    protected function generatePostUrl(?Post $post = null, ?int $commentId = null): string
    {
        $post = $post ?? $this->comment->commentable;
        
        $baseUrl = $post->channel
            ? url("/channel/{$post->channel->slug}/{$post->slug}")
            : url("/posts/{$post->slug}");

        return $commentId ? "{$baseUrl}#comment-{$commentId}" : $baseUrl;
    }
}