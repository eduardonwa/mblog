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
use Spatie\MediaLibrary\Conversions\Manipulations;
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

    public function kreator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
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

    public function likesCount()
    {
        return $this->likes()->count();
    }

    public function isLikedByUser()
    {
        return $this->likes()->where('user_id', Auth::id())->exists();
    }

    // scope para posts de staff/admin (no necesita el rol de kreator)
    public function scopeStaffBase($query)
    {
        return $query->with(['user', 'category', 'media'])
            ->withCount('likes')
            ->where('status', 'published')
            ->whereHas('user', function($q) {
                $q->role(['staff', 'admin']);
            });
    }

    // posts regulares del staff
    public function scopeStaffPosts($query, $limit = 10)
    {
        return $query->staffBase()
            ->where('featured', false)
            ->latest()
            ->take($limit);
    }
    
    // featured posts del staff
    public function scopeFeaturedPosts($query, $limit = 6)
    {
        return $query->staffBase()
            ->where('featured', true)
            ->latest()
            ->take($limit);
    }
       
    // Scope para posts de "kreators" (no staff/admin)
    public function scopeCommunityBase($query)
    {
        return $query->with(['user', 'media'])
            ->withCount('likes')
            ->where('status', 'published')
            ->whereHas('user', function($q) {
                $q->role('kreator');
            });
    }

    // Versión para posts recientes de la comunidad
    public function scopeRecent($query, $limit = 10)
    {
        return $query->communityBase()
            ->latest()
            ->take($limit);
    }

    public function scopeMostLiked($query, $limit = 5)
    {
        return $query->with(['likes', 'user'])
            ->withCount('likes')
            ->whereHas('kreator', function($q) {
                $q->role('kreator');
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
        $this->addMediaConversion('max_thumb')
            ->fit(Fit::Max, 720, 720)
            ->format('webp')
            ->nonQueued()
            ->performOnCollections('thumbnails');

        $this->addMediaConversion('lg_thumb')
            ->fit(Fit::Crop, 560, 300)
            ->format('webp')
            ->performOnCollections('thumbnails')
            ->nonQueued();
    
        // Luego las más pequeñas
        $this->addMediaConversion('md_thumb')
            ->fit(Fit::Max, 300, 300)
            ->format('webp')
            ->nonQueued();
    
        $this->addMediaConversion('sm_thumb')
            ->fit(Fit::Contain, 150, 150)
            ->format('webp')
            ->nonQueued();
    }

    public function getThumbnailUrlsAttribute()
    {
        return [
            'max' => $this->getFirstMediaUrl('thumbnails', 'max_thumb'),
            'lg' => $this->getFirstMediaUrl('thumbnails', 'lg_thumb'),
            'md' => $this->getFirstMediaUrl('thumbnails', 'md_thumb'), 
            'sm' => $this->getFirstMediaUrl('thumbnails', 'sm_thumb')
        ];
    }
}
