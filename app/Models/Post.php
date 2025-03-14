<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'slug',
        'extract',
        'body',
        'thumbnail',
        'language',
        'meta_title',
        'meta_description',
        'status',
        'featured',
        'category_id',
        'user_id'
    ];

    // relacion de "author"
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getFormattedDate()
    {
        return $this->created_at->format('F jS Y');
    }
}
