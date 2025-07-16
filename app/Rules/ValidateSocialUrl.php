<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateSocialUrl implements ValidationRule
{
    protected ?string $platform;

    protected array $allowedDomains = [
        'instagram' => 'instagram.com',
        'bandcamp'  => 'bandcamp.com',
        'youtube'   => 'youtube.com',
    ];

    public function __construct(string $platform)
    {
        $this->platform = $platform;
    }
    
    public static function for(string $platform): self
    {
        return new self($platform);
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->platform) {
            $domain = $this->allowedDomains[$this->platform] ?? null;

            if (! $domain || ! str_contains($value, $domain)) {
                $fail("Invalid {$this->platform} URL.");
            }

            return;
        }

        // Validación general: que pertenezca a cualquiera de los dominios aceptados
        foreach ($this->allowedDomains as $domain) {
            if (str_contains($value, $domain)) {
                return;
            }
        }

        $fail("Invalid social URL (Instagram, Bandcamp, or YouTube).");
    }
}