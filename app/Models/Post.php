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

    public function member(): BelongsTo
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

    // utiliza el formato largo
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
        $diffInMinutes = $createdAt->diffInMinutes($now);
        $diffInHours = $createdAt->diffInHours($now);
        $diffInDays = $createdAt->diffInDays($now);
    
        if ($short) {
            if ($diffInMinutes < 1) {
                return 'Now';
            }
            if ($diffInMinutes < 60) {
                return $diffInMinutes . 'm';
            }
            if ($diffInHours < 24) {
                return floor($diffInHours) . 'h';
            }
            if ($diffInDays < 7) {
                return $diffInDays . 'd';
            }
            return $createdAt->isoFormat('D MMM');
        }
    
        // Versión larga (diffForHumans)
        return $createdAt->diffForHumans();
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
                $q->role('member');
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
            ->whereHas('member', function($q) {
                $q->role('member');
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
