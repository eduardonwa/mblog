<?php

namespace App\Models;

use Filament\Panel;
use App\Models\Like;
use BeyondCode\Comments\Traits\CanComment;
use Filament\Models\Contracts\FilamentUser;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, CanComment;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function assignMember(): void
    {
        // Verificar si ya tiene el rol de "member"
        if ($this->hasRole('member')) {
            throw new \Exception('User already has "member" role.');
        }
    
        // Asignar el rol de "member"
        $this->assignRole('member');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'member_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class)->withTimestamps();
    }

    public function likedPosts()
    {
        return $this->morphedByMany(Post::class, 'likeable', 'likes');
    }

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@sickofmetal.net') && 
               $this->hasVerifiedEmail() && 
               $this->hasRole('admin');
    }

    public function needsCommentApproval($model): bool
    {
        return false;
    }
}
