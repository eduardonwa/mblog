<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    public function scopePublic($query)
    {
        return $query->where('scope', 'public');
    }

    public function scopeForKreators($query)
    {
        return $query->where('scope', 'kreators');
    }
}
