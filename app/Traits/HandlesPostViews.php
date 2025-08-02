<?php

namespace App\Traits;

use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

trait HandlesPostViews
{
    /**
     * Increment the view count for a post.
     *
     * @param \App\Models\Post $post
     * @return void
     */
    public function incrementPostViewCount(Post $post)
    {
        $ip = request()->ip();
        $key = "post_{$post->id}_viewed_by_{$ip}";

        // revisa si el usuario ya vio el post en la ultima hora
        if (!Cache::has($key)) {
            $post->increment('views');
            Cache::put($key, true, now()->addMinutes(30));
        } else {
            Log::info("No se incrementa, IP ya registrada en caché para post {$post->id}.");
        }
    }
}