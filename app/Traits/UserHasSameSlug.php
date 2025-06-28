<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UserHasSameSlug
{
    /**
     * Generate a unique slug for the user.
     *
     * @param string $name
     * @return string
     */
    public static function generateSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }
}