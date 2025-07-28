<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Solo el admin o los autores pueden crear posts
        return $user->hasAnyRole(['admin', 'staff', 'member']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        // Admin puede editar cualquier post
        if ($user->hasRole('admin')) {
            return true;
        }
        
        // Staff solo puede editar sus propios posts
        if ($user->hasRole('staff')) {
            return $user->id === $post->user_id;
        }
        
        // Autores regulares solo sus posts
        if ($user->hasRole('member')) {
            return $user->id === $post->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        // El admin puede borrar cualquier post, o el autor puede borrar su propio post
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->id === $post->user_id && $user->hasAnyRole(['staff', 'member']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return $user->hasRole('admin');
    }
}
