<?php

namespace App\Models;

use App\Models\Post;
use App\Models\User;
use Kalnoy\Nestedset\NodeTrait;
use App\Notifications\CommentModeration;
use BeyondCode\Comments\Comment as BaseComment;

class CustomComment extends BaseComment
{
    use NodeTrait;

    protected $table = 'comments';
    protected $parentColumn = 'comment_id';

    public function commentable()
    {
        return $this->morphTo();
    }

    public function commentator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function allDescendants()
    {
        return $this->descendants()->with('commentator');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function approve()
    {
        parent::approve();

        if ($this->commentator) {
            $this->commentator->notify(new CommentModeration($this, status: 'approved'));
        }

        return $this;
    }

    public function disapprove()
    {
        parent::disapprove();

        if ($this->commentator) {
            $this->commentator->notify(new CommentModeration($this, status: 'disapproved'));
        }

        return $this;
    }
}
