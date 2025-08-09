<?php

namespace App\Observers;

use App\Models\Post;

class PostObserver
{

    /**
     * Handle the Post "saving" event.
     */
    public function saving(Post $post): void
    {
        // created_at siempre debe tener valor
        if (is_null($post->created_at)) {
            $post->created_at = now();
        }

        if ($post->status === 'published') {
            if (is_null($post->published_at)) {
                $post->published_at = now();
            }
        } elseif ($post->status === 'scheduled') {
            if (is_null($post->published_at)) {
                $post->published_at = now()->addHour();
            }
        } elseif ($post->status === 'draft') {
            $post->published_at = null;
        }

        // 🔹 Limpiar series_order si no hay serie
        if (empty($post->post_series_id)) {
            $post->series_order = null;
        }

        // (Opcional) Autoasignar series_order si falta y hay serie
        if ($post->post_series_id && is_null($post->series_order)) {
            $max = Post::where('post_series_id', $post->post_series_id)->max('series_order');
            $post->series_order = ($max ?? 0) + 1;
        }
    }
    
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        //
    }

    public function deleting(Post $post)
    {
        // le cambia el slug al post antes de borrarlo
        $post->slug = $post->slug . '-deleted-' . now()->timestamp;
        $post->saveQuietly();
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }
}
