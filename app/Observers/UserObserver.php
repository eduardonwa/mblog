<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleting" event.
     */
    public function deleting(User $user): void
    {
        // 1. Guardar referencia original ANTES de desvincular
        $user->posts()->update([
            'original_user_id' => $user->id, // ← Conservamos el ID original
            'user_id' => null                // ← Desvinculamos
        ]);
    }
    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        // Revincular posts que fueron desvinculados al borrar
        Post::withTrashed()
            ->where('original_user_id', $user->id)
            ->update([
                'user_id' => $user->id,
                'original_user_id' => null,
                'deleted_at' => null // Restaurar posts borrados también
            ]);
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
