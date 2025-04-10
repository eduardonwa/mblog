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

    protected $appends = [
        'smart_date',
        'short_date',
        'thumbnail_urls'
    ];
    
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

/*     public function getSmartDateAttribute()
    {
        $createdAt = $this->created_at;
           
        if ($createdAt > now()->subDays(7)) {
            return $createdAt->diffForHumans();
        }
        
        return $createdAt->isoFormat('D MMMM YYYY');
    } */
    public function getSmartDateAttribute()
    {
        return $this->formatDate(false);
    }
    
    public function getShortDateAttribute()
    {
        return $this->formatDate(true);
    }
    
    protected function formatDate($short)
    {
        $createdAt = $this->created_at;
        $now = now();
    
        if ($createdAt > $now->subDays(7)) {
            if ($short) {
                if ($createdAt > $now->subMinute()) {
                    return 'Now';
                }
                
                if ($createdAt > $now->subHour()) {
                    $minutes = $createdAt->diffInMinutes();
                    return $minutes < 60 ? $minutes.'m' : floor($minutes/60).'h';
                }
                
                if ($createdAt > $now->subDay()) {  // Cambiado de subDays() a subDay()
                    return $createdAt->diffInHours().'h';
                }
                
                return $createdAt->diffInDays().'d';
            }
            return $createdAt->diffForHumans();
        }
        
        // Corrección del typo: isFormat → isoFormat
        return $short ? $createdAt->isoFormat('D MMM') : $createdAt->isoFormat('D MMMM YYYY');
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

    public function scopeStaffPosts($query, $limit = 10)
    {
        return $query->with(['category', 'author'])
            ->where('status', 'published')
            ->whereHas('author', function($q) {
                $q->whereHas('roles', function($q) {
                    $q->whereIn('name', ['is_staff', 'admin']); // incluye admin y staff
                });
            })
            ->skip(3)
            ->latest()
            ->take($limit);
    }

    public function scopeNewestStaffPosts($query, $limit = 3)
    {
        return $query->with(['category', 'author'])
            ->withCount('likes')
            ->where('status', 'published')
            ->whereHas('author', function($q) {
                $q->whereHas('roles', function($q) {
                    $q->whereIn('name', ['is_staff', 'admin']); // incluye admin y staff
                });
            })
            ->latest()
            ->take($limit);
    }
    
    public function scopeRecent($query, $limit = 10)
    {
        return $query->with('author')
            ->whereDoesntHave('author', function($q) {
                $q->whereHas('roles', function($q) {
                    $q->whereIn('name', ['is_staff', 'admin']); // Excluye admin y staff
                });
            })
            ->where('status', 'published')
            ->latest()
            ->take($limit);
    }

    public function scopeMostLiked($query, $limit = 5)
    {
        return $query->with(['author', 'likes'])
            ->withCount('likes')
            ->whereDoesntHave('author.roles', function($q) {
                $q->whereIn('name', ['is_staff', 'admin']); // Excluye admin y staff
            })
            ->orderByDesc('likes_count')
            ->take($limit);
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

    public function getThumbnailUrlsAttribute()
    {
        return [
            'lg' => $this->getFirstMediaUrl('thumbnails', 'lg_thumb'),
            'md' => $this->getFirstMediaUrl('thumbnails', 'md_thumb'), 
            'sm' => $this->getFirstMediaUrl('thumbnails', 'sm_thumb')
        ];
    }
}
