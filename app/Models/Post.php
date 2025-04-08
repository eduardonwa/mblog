<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Like;
use App\Models\Category;
use Spatie\Tags\HasTags;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTags;

    protected $appends = ['smart_date'];
    
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeStaffPosts(Builder $query): Builder
    {
        return $query->whereHas('author', function($q) {
            $q->role('is_staff');
        });
    }

    public function getSmartDateAttribute()
    {
        $createdAt = $this->created_at;
           
        if ($createdAt > now()->subDays(7)) {
            return $createdAt->diffForHumans();
        }
        
        return $createdAt->isoFormat('D MMMM YYYY');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function likesCount()
    {
        return $this->likes()->count();
    }

    public function isLikedByUser()
    {
        return $this->likes()->where('user_id', Auth::id())->exists();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnails')
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {   
        $this
            ->addMediaConversion('sm_thumb')
            ->fit(Fit::Contain, 150, 150)
            ->format('webp')
            ->nonQueued();

        $this
            ->addMediaConversion('md_thumb')
            ->fit(Fit::Contain, 300, 300)
            ->format('webp')
            ->nonQueued();

        $this
            ->addMediaConversion('lg_thumb')
            ->fit(Fit::Contain, 1080, 1080)
            ->format('webp')
            ->nonQueued(); 
    }
}
