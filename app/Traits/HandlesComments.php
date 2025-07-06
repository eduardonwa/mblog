<?php

namespace App\Traits;

use App\Models\Post;
use App\Models\CustomComment;

trait HandlesComments
{
    protected function getCommentTreeForPost(Post $post)
    {
        // Obtener TODOS los comentarios del post (incluyendo respuestas)
        $allComments = CustomComment::with(['commentator'])
            ->where('commentable_type', Post::class)
            ->where('commentable_id', $post->id)
            ->approved()
            ->get();

        // Construir el árbol manualmente
        $grouped = $allComments->groupBy('comment_id');

        foreach ($allComments as $comment) {
            if ($grouped->has($comment->id)) {
                $comment->setRelation('children', $grouped[$comment->id]);
            } else {
                $comment->setRelation('children', collect()); // envia coleccion vacia
            }
        }

        // Retornar solo los comentarios raíz (comment_id = null)
        return $grouped->get(null, collect());
    }
}
