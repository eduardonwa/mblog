<?php

namespace App\Traits;

use Stevebauman\Purify\Facades\Purify;

trait PurifiesAttributes
{
    public static function bootPurifiesAttributes()
    {
        static::saving(function ($model) {
            foreach ($model->getPurifiable() as $field) {
                if (!empty($model->{$field})) {
                    $model->{$field} = self::purifyValue($model->{$field});
                }
            }
        });
    }

    public function getPurifiable(): array
    {
        return property_exists($this, 'purifiable') ? $this->purifiable : [];
    }

    protected static function purifyValue($value)
    {
        if (is_array($value)) {
            // Recursivamente purificar cada valor en el array
            foreach ($value as $key => $item) {
                $value[$key] = self::purifyValue($item);
            }
            return $value;
        } elseif (is_string($value)) {
            // Limpiar el string usando Purify
            return Purify::clean($value);
        }

        // Otros tipos (int, bool, null) devolver sin cambio
        return $value;
    }
}
