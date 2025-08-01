<?php

function crushingScoreRaw()
{
    $postClass = addslashes(\App\Models\Post::class);
    return "(
        CASE
            WHEN published_at IS NULL THEN 0
            ELSE (
                (SELECT COUNT(*) FROM comments WHERE comments.commentable_type = '{$postClass}' AND comments.commentable_id = posts.id) * 10
                + (SELECT COUNT(*) FROM likes WHERE likes.likeable_type = '{$postClass}' AND likes.likeable_id = posts.id) * 3
                + (views * 1)
            ) / POW(LEAST(TIMESTAMPDIFF(HOUR, published_at, NOW()) + 2, 720), 2)
        END
    )";
}
