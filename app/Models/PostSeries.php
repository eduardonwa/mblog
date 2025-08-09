<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

class PostSeries extends Model
{
    protected $table = 'post_series';
    
    public function posts()
    {
        return $this->hasMany(Post::class, 'post_series_id')
            ->orderBy('series_order')
            ->with('media');
    }
}
