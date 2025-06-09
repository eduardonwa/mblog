<?php

namespace App\Models;

use App\Models\Like;
use App\Models\Category;
use Spatie\Tags\HasTags;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use BeyondCode\Comments\Traits\HasComments;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTags, HasComments;

    protected $appends = [
        'smart_date',
        'short_date',
        'thumbnail_urls',
        'excerpt'
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /* RELACIONES */
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

    /* LIKES */
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

    /* FORMATEOS */

    // FECHAS
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

    // formato largo
    public function getSmartDateAttribute()
    {
        return $this->formatDate(false);
    }
    
    // formato corto
    public function getShortDateAttribute()
    {
        return $this->formatDate(true);
    }
    
    // crear extracto corto
    public function getExcerptAttribute(): string
    {
        $words = 30;
        $stripped = strip_tags($this->body);
        $excerpt = Str::words($stripped, $words);
        
        return $excerpt;
    }

    // SCOPES
    // relaciones y condiciones comunes
    public function scopeWithCommonRelations($query)
    {
        return $query->with(['user', 'category', 'media'])
                    ->withCount('likes');
    }

    // posts publicados
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
    
    // 3. Top member posts (lo que aparece en leaderboard)
    public function scopeTopMemberPosts($query, $limit = null)
    {
        $query = $query->published()
                    ->whereHas('user', fn($q) => $q->role('member'))
                    ->withCount('likes')
                    ->having('likes_count', '>', 0)
                    ->orderByDesc('likes_count');

        return $limit ? $query->take($limit) : $query;
    }

    // 4. Community feed
    public function scopeCommunityFeed($query, $limit = null)
    {
        $query = $query->published()
                    ->where(function($q) {
                        // 1. Todos los posts de miembros (rol 'member')
                        $q->whereHas('user', function($q) {
                            $q->role('member');
                        })
                        // 2. O posts SIN categoría de staff/admin
                        ->orWhere(function($q) {
                            $q->whereNull('category_id')
                            ->whereHas('user', function($q) {
                                $q->role(['staff', 'admin']);
                            });
                        });
                    })
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

    // IMAGENES, colecciones y conversiones
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
