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
    // mostrar formato de fechas 
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

    // horario al crear
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($post) {
            // Asegura coherencia entre status y published_at
            if ($post->status === 'published' && is_null($post->published_at)) {
                $post->published_at = now();
            }

            if ($post->status === 'draft') {
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
        return $query->published() // Solo posts publicados
            ->where(function($q) {
                // Posts de MIEMBROS (users) en CHANNELS
                $q->whereHas('user', function($userQ) {
                    $userQ->role('member')
                        ->orWhere(fn($q) => $q->onlyTrashed()
                            ->whereHas('roles', fn($q) => $q->where('name', 'member')));
                })
                ->whereHas('channel'); // ¡Asegúrate de que pertenezca a un channel!
            })
            ->orWhere(function($q) {
                // Posts de STAFF/ADMIN en CATEGORIES (o sin categoría)
                $q->whereHas('user', function($userQ) {
                    $userQ->role(['staff', 'admin'])
                        ->orWhere(fn($q) => $q->onlyTrashed()
                            ->whereHas('roles', fn($q) => $q->whereIn('name', ['staff', 'admin'])));
                })
                ->where(function($subQ) {
                    $subQ->whereNull('category_id') // Posts sin categoría
                        ->orWhereHas('category'); // O con categoría
                });
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
