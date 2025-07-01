<?php

namespace App\Models;

use App\Models\Like;
use App\Models\Channel;
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
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTags, HasComments, SoftDeletes;

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
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
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

    // COLUMNAS
    public function originalUser()
    {
        return $this->belongsTo(User::class, 'original_user_id')->withTrashed();
    }

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

    // obtiene posts de usuarios activos y eliminados segun el rol
    protected function scopeWhereUserHasRole($query, array $roles)
    {
        return $query->where(function($q) use ($roles) {
            // 1. Usuarios activos o borrados con el rol
            $q->whereHas('user', function($q) use ($roles) {
                $q->where(function($subQ) use ($roles) {
                    $subQ->role($roles)
                        ->orWhere(fn($q) => $q->onlyTrashed()->whereHas('roles', fn($q) => $q->whereIn('name', $roles)));
                });
            })
            // 2. O posts huérfanos cuyo original_user tenía ese rol
            ->orWhereHas('originalUser', function($q) use ($roles) {
                $q->whereHas('roles', fn($q) => $q->whereIn('name', $roles));
            });
        });
    }

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
            ->whereUserHasRole(['staff', 'admin'])
            ->latest();

        return $limit ? $query->take($limit) : $query;
    }

    // 2. Staff/admin posts no destacados
    public function scopeStaffPosts($query, $limit = null)
    {
        $query = $query->published()
            ->where('featured', false)
            ->whereUserHasRole(['staff', 'admin'])
            ->latest();

        return $limit ? $query->take($limit) : $query;
    }
    
    // 3. Top member posts (lo que aparece en leaderboard)
    public function scopeTopMemberPosts($query, $limit = null)
    {
        $query = $query->published()
            ->whereUserHasRole(['member'])
            ->withCount('likes')
            ->having('likes_count', '>', 0)
            ->orderByDesc('likes_count');

        return $limit ? $query->take($limit) : $query;
    }

    // 4. Community feed
    public function scopeCommunityFeed($query, $limit = null)
    {
        return $query->published()
            ->where(function($q) {
                $q->where(function($subQ) {
                    // posts de miembros (activos o borrados)
                    $subQ->whereHas('user', function($q) {
                        $q->where(function($userQ) {
                            $userQ->role('member')
                                ->orWhere(fn($q) => $q->onlyTrashed()
                                    ->whereHas('roles', fn($q) => $q->where('name', 'member')));
                        });
                    })
                    // o posts donde user_id es null
                    ->orWhereNull('user_id');
                })
                // o posts sin categoria de staff/admin (activos o borrados)
                ->orWhere(function($q) {
                    $q->whereNull('category_id')
                        ->whereHas('user', function($q) {
                            $q->where(function($userQ) {
                                $userQ->role(['staff', 'admin'])
                                    ->orWhere(fn($q) => $q->onlyTrashed()
                                    ->whereHas('roles', fn($q) => $q->whereIn('name', ['staff', 'admin'])));
                            });
                        });
                });
            });
    }

    // 5. Posts recientes de miembros
    public function scopeRecentMemberPosts($query, $limit = null)
    {
        $query = $query->published()
            ->whereUserHasRole(['member'])
            ->latest();

        return $limit ? $query->take($limit) : $query;
    }

    // IMAGENES, colecciones y conversiones
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnails')
            ->singleFile()
            ->useDisk('public');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('max_thumb')
            ->fit(Fit::Max, 720, 720)
            ->format('webp')
            ->optimize()
            ->performOnCollections('thumbnails')
            ->nonQueued();

        $this->addMediaConversion('lg_thumb')
            ->fit(Fit::Max, 560, 300)
            ->format('webp')
            ->quality(90)
            ->optimize()
            ->performOnCollections('thumbnails')
            ->nonQueued();

        $this->addMediaConversion('md_thumb')
            ->fit(Fit::Max, 300, 300)
            ->format('webp')
            ->optimize()
            ->performOnCollections('thumbnails')
            ->nonQueued();
    
        $this->addMediaConversion('sm_thumb')
            ->fit(Fit::Max, 150, 150)
            ->format('webp')
            ->optimize()
            ->performOnCollections('thumbnails')
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
