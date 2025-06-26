<?php

namespace App\Models;

use App\Models\Post;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Channel extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    // RELACIONES
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // IMAGENES, colecciones y conversiones
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('channel_sticker')
            ->singleFile()
            ->useDisk('public');
    }

        public function registerMediaConversions(?Media $media = null): void
        {
            $this->addMediaConversion('lg_thumb')
                ->fit(Fit::Max, 560, 300)
                ->format('webp')
                ->quality(90)
                ->optimize()
                ->performOnCollections('channel_sticker')
                ->nonQueued();

            $this->addMediaConversion('sm_thumb')
                ->fit(Fit::Max, 150, 150)
                ->format('webp')
                ->optimize()
                ->performOnCollections('channel_sticker')
                ->nonQueued();
        }
        
        public function getThumbnailUrlsAttribute()
        {
            return [
                'lg' => $this->getFirstMediaUrl('channel_sticker', 'lg_thumb'),
                'sm' => $this->getFirstMediaUrl('channel_sticker', 'sm_thumb')
            ];
        }

}
