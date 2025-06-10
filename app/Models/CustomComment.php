<?php

namespace App\Models;

use App\Models\User;
use Kalnoy\Nestedset\NodeTrait;
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
}
