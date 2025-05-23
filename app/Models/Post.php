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

        // calcular todos los valores con floor
        $minutes = floor($createdAt->diffInMinutes($now));
        $hours = floor($createdAt->diffInHours($now));
        $days = floor($createdAt->diffInDays($now));
    
        if ($short) {
            if ($minutes < 1) {
                return 'Now';
            }
            if ($minutes < 60) {
                return $minutes . 'm';
            }
            if ($hours < 24) {
                return $hours . 'h';
            }
            if ($days < 7) {
                return $days . 'd';
            }
            return $createdAt->isoFormat('D MMM');
        }
        // Versión larga
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

    // scope base para relaciones y condiciones comunes
    public function scopeWithCommonRelations($query)
    {
        return $query->with(['user', 'category', 'media'])
                    ->withCount('likes');
    }

    // scope base para posts publicados
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->withCommonRelations();
    }

    // 1. Featured posts (solo staff/admin)
    public function scopeFeatured($query, $limit = null)
    {
        $query = $query->published()
                    ->where('featured', true)
                    ->whereHas('user', function($q) {
                        $q->role(['staff', 'admin']);
                    })
                    ->latest();

        return $limit ? $query->take($limit) : $query;
    }

    // 2. Staff/admin posts no destacados
    public function scopeStaffPosts($query, $limit = null)
    {
        $query = $query->published()
                    ->where('featured', false)
                    ->whereHas('user', function($q) {
                        $q->role(['staff', 'admin']);
                    })
                    ->latest();

        return $limit ? $query->take($limit) : $query;
    }
    
    // 3. Top member posts (por likes)
    public function scopeTopMemberPosts($query, $limit = null)
    {
        $query = $query->published()
                    ->whereHas('user', function($q) {
                        $q->role('member');
                    })
                    ->orderByDesc('likes_count');

        return $limit ? $query->take($limit) : $query;
    }

    // 4. Community feed (posts en grupos o sin categoría - cualquier rol)
    public function scopeCommunityFeed($query, $limit = null)
    {
        $query = $query->published()
                    ->whereNull('category_id')
                    ->latest();

        return $limit ? $query->take($limit) : $query;
    }

    // 5. Posts recientes de miembros
    public function scopeRecentMemberPosts($query, $limit = null)
    {
        $query = $query->published()
                    ->whereHas('user', function($q) {
                        $q->role('member');
                    })
                    ->latest();

        return $limit ? $query->take($limit) : $query;
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
