<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UserHasSameName
{
    /**
     * Generate a unique slug for the user.
     *
     * @param string $name
     * @return string
     */
    public static function generateUniqueUsername(string $base): string
    {
        $username = Str::slug($base);
        $original = $username;
        $counter = 1;

        while (self::where('username', $username)->exists()) {
            $username = $original . $counter;
            $counter++;
        }

        return $username;
    }
}