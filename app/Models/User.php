<?php

namespace App\Models;

use Filament\Panel;
use App\Models\Like;
use App\Models\CustomComment;
use App\Traits\UserHasSameSlug;
use Spatie\MediaLibrary\HasMedia;
use Filament\Models\Contracts\HasName;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use BeyondCode\Comments\Traits\CanComment;
use Filament\Models\Contracts\FilamentUser;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser, HasMedia, HasName
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, SoftDeletes, Notifiable, HasRoles, CanComment, InteractsWithMedia, UserHasSameSlug;

    protected $appends = [
        'avatar_url',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getRouteKeyName()
    {
        return 'username';
    }

/*     protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->slug)) {
                $user->slug = Str::slug($user->name);
            }
        });
    } */

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    // incluir posts eliminados al cargar la relacion
    public function postsWithTrashed()
    {
        return $this->posts()->withTrashed();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedPosts()
    {
        return $this->morphedByMany(Post::class, 'likeable', 'likes');
    }
    
    public function likesReceivedCount()
    {
        return $this->hasManyThrough(
            Like::class,
            Post::class,
            'user_id', // FK en posts
            'likeable_id', // FK en likes
            'id', // PK en users
            'id' // PK en posts
        )->where('likeable_type', Post::class)->count();
    }
    
    public function comments()
    {
        return $this->hasMany(CustomComment::class, 'user_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => str_ends_with($this->email, '@sickofmetal.net') &&
                    $this->hasVerifiedEmail() &&
                    $this->hasRole('admin'),

            'member' => $this->hasVerifiedEmail() &&
                        $this->hasRole('member'),

            default => false,
        };
    }

    public function needsCommentApproval($model): bool
    {
        return false;
    }

    public function assignMember(): void
    {
        // Verificar si ya tiene el rol de "member"
        if ($this->hasRole('member')) {
            throw new \Exception('User already has "member" role.');
        }
    
        // Asignar el rol de "member"
        $this->assignRole('member');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('user_avatar')
            ->singleFile()
            ->useDisk('public')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100)
            ->sharpen(10)
            ->format('webp');
            
        $this->addMediaConversion('medium')
            ->width(300)
            ->height(300)
            ->format('webp');
    }

    public function getAvatarUrlAttribute()
    {
        return $this->getFirstMediaUrl('user_avatar', 'thumb') 
            ?: 'https://www.gravatar.com/avatar/' . md5($this->email) . '?d=identicon';
    }

    public function getFilamentName(): string
    {
        return "{$this->username}";
    }
}
