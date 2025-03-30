<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike(Post $post)
    {
        $user = Auth::user();

        // verificar si ya existe un like
        $existingLike = Like::where([
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => get_class($post)
        ])->first();

        if ($existingLike) {
            $existingLike->delete();
            return response()->json([
                'liked' => false,
                'count' => $post->likes()->count()
            ]);
        } else {
            $like = new Like(['user_id' => $user->id]);
            $post->likes()->save($like);
            return response()->json([
                'liked' => true,
                'count' => $post->likes()->count()
            ]);
        }
    }
}
