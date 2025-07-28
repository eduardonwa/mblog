<?php

namespace App\Models;

use App\Models\Like;
use App\Models\Report;
use App\Models\Channel;
use App\Models\Category;
use Spatie\Tags\HasTags;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use App\Traits\PurifiesAttributes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use BeyondCode\Comments\Traits\HasComments;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTags, HasComments, SoftDeletes;

    protected $appends = [
        'smart_date',
        'short_date',
        'thumbnail_urls',
        'excerpt',
        'is_liked_by_user',
        'likes_count'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'published_at' => 'datetime',
        'list_data_json' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * RELACIONES
     */
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
    
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function originalUser()
    {
        return $this->belongsTo(User::class, 'original_user_id')->withTrashed();
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
    
    /**
     * LIKES
     */
    // accesores para obtener la cuenta y el usuario que le dio like
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    public function getIsLikedByUserAttribute()
    {
        return $this->likes()->where('user_id', Auth::id())->exists();
    }

    /**
     * FORMATEOS ESPECIALES
     */
    // FECHAS -- crear formato para organizar fechas 
    protected function formatDate($date, $short = false)
    {
        // $date = $this->published_at ?? $this->created_at;
        $now = now();

        if (!$date || !$date instanceof \Carbon\Carbon) {
            return $short ? '—' : 'Not available';
        }

        $minutes = floor($date->diffInMinutes($now));
        $hours = floor($date->diffInHours($now));
        $days = floor($date->diffInDays($now));

        if ($short) {
            if ($minutes < 1) return 'Now';
            if ($minutes < 60) return $minutes . 'm';
            if ($hours < 24) return $hours . 'h';
            if ($days < 7) return $days . 'd';
            return $date->isoFormat('D MMM');
        }

        return $date->diffForHumans();
    }

    public function smartDate($date)
    {
        $now = now();

        if (!$date || !$date instanceof \Carbon\Carbon) {
            return 'Not available';
        }

        $days = $date->diffInDays($now);

        // si es menor de 7 dias, mostrar "x days ago"

        if ($days < 7) {
            return $date->diffForHumans();
        }

        return $date->isoFormat('D MMMM YYYY');
    }

    // formato largo de fecha
    public function getSmartDateAttribute()
    {
        return $this->published_at
            ? $this->smartDate($this->published_at, true)
            : '-';
    }
    
    // formato corto de fecha
    public function getShortDateAttribute()
    {
        return $this->published_at
            ? $this->formatDate($this->published_at, true)
            : '-';
    }

    // interpretacion de "STATUS"
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($post) {
            // created_at siempre debe tener valor
            if (is_null($post->created_at)) {
                $post->created_at = now();
            }

            if ($post->status === 'published') {
                $post->published_at = now();
            } elseif ($post->status === 'scheduled') {
                // si no hay published_at, asignar fecha futura
                if (is_null($post->published_at)) {
                    $post->published_at = now()->addHour();
                }
            } elseif ($post->status === 'draft') {
                // no debería tener fecha de publicación
                $post->published_at = null;
            }
        });
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
            ->latest('published_at');

        return $limit ? $query->take($limit) : $query;
    }

    // 2. Staff/admin posts no destacados
    public function scopeStaffPosts($query, $limit = null)
    {
        $query = $query->published()
            ->where('featured', false)
            ->whereUserHasRole(['staff', 'admin'])
            ->latest('published_at');

        return $limit ? $query->take($limit) : $query;
    }
    
    // 3. Top member posts (lo que aparece en leaderboard)
    public function scopeTopMemberPosts($query, $limit = null)
    {
        if (app()->environment('testing')) {
            return $query->published()
                ->whereUserHasRole(['member'])
                ->withCount('likes')
                ->get()
                ->filter(fn($post) => $post->likes_count > 0)
                ->sortByDesc('likes_count')
                ->take($limit);
        } else {
            return $query->published()
                ->whereUserHasRole(['member'])
                ->withCount('likes')
                ->having('likes_count', '>', 0)
                ->orderByDesc('likes_count')
                ->take($limit);
        }
    }

    // 4. Community feed
    public function scopeCommunityFeed($query, $limit = null)
    {
        return $query->published()
            ->where(function ($q) {
                $q->whereHas('user', function ($userQ) {
                    $userQ->role('member')
                        ->orWhere(fn($q) => $q->onlyTrashed()
                            ->whereHas('roles', fn($q) => $q->where('name', 'member')));
                })->whereHas('channel');
            })
            ->orderBy('published_at', 'DESC')
            ->when($limit, fn($q) => $q->take($limit));
    }

    // 5. Posts recientes de miembros
    public function scopeRecentMemberPosts($query, $limit = null)
    {
        $query = $query->published()
            ->whereUserHasRole(['member'])
            ->latest('published_at');

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
