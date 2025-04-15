<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CaptchaController extends Controller
{
    private array $bands = [
        'sabbath' => [
            'name' => 'Black Sabbath',
            'albums' => ['master-of-reality']
        ],
        'cattle' => [
            'name' => 'Cattle Decapitation',
            'albums' => ['terrasite'],
        ],
        'crowbar' => [
            'name' => 'Crowbar',
            'albums' => ['sever-the-wicked-hand'],
        ],
        'cryptopsy' => [
            'name' => 'Cryptopsy',
            'albums' => ['as-gomorrah-burns']
        ],
        'deftones' => [
            'name' => 'Deftones',
            'albums' => ['deftones']
        ],
        'dream' => [
            'name' => 'Dream Theater',
            'albums' => ['images-and-words'],
        ],
        'maiden' => [
            'name' => 'Iron Maiden',
            'albums' => ['piece-of-mind'],
        ],
        'korn' => [
            'name' => 'Korn',
            'albums' => ['follow-the-leader'],
        ],
        'megadeth' => [
            'name' => 'Megadeth',
            'albums' => ['rust-in-peace'],
        ],
        'metallica' => [
            'name' => 'Metallica',
            'albums' => ['ride-the-lighting'],
        ],
        'flames' => [
            'name' => 'In Flames',
            'albums' => ['clayman'],
        ],
        'pantera' => [
            'name' => 'Pantera',
            'albums' => ['cowboys-from-hell'],
        ],
        'slipknot' => [
            'name' => 'Slipknot',
            'albums' => ['iowa'],
        ],
        'tool' => [
            'name' => 'Tool',
            'albums' => ['lateralus'],
        ],
        'zenith' => [
            'name' => 'The Zenith Passage',
            'albums' => ['datalysium'],
        ],
    ];

    public function generateMetalCaptcha()
    {
        try {
            $manager = new ImageManager(new Driver());
            
            // Seleccionar banda y álbum aleatorio
            $bandKey = array_rand($this->bands);
            $band = $this->bands[$bandKey];
            $album = $band['albums'][array_rand($band['albums'])];
            
            // Ruta de la imagen
            $imagePath = public_path("images/albums/{$album}.webp");
            
            if (!file_exists($imagePath)) {
                throw new \Exception("Album image not found: {$album}");
            }

            // Procesar imagen
            $image = $manager->read($imagePath)
                ->greyscale()
                ->contrast(rand(30, 50))
                ->blur(rand(1, 7))
                ->pixelate(rand(10, 15))
                ->colorize(rand(-20, 20), rand(-20, 20), rand(-20, 20))
                ->rotate(rand(-15, 15));

            // Guardar respuesta en caché (banda correcta)
            $cacheKey = 'captcha_'.request()->ip();
            Cache::put($cacheKey, $bandKey, now()->addMinutes(10));
            
            return response($image->toPng())
                ->header('Content-Type', 'image/png');

        } catch (\Exception $e) {
            Log::error('CAPTCHA generation failed: '.$e->getMessage());
            return response()->json(['error' => 'Failed to generate CAPTCHA'], 500);
        }
    }

    public function validateCaptcha(Request $request)
    {
        $validated = $request->validate([
            'captcha_answer' => 'required|string|max:50',
        ]);

        $cacheKey = 'captcha_'.request()->ip();
        $correctAnswer = Cache::get($cacheKey);

        if (!$correctAnswer) {
            return response()->json([
                'success' => false,
                'error' => 'CAPTCHA expired. Please refresh and try again.'
            ], 422);
        }

        $normalizedUserAnswer = strtolower(trim($validated['captcha_answer']));
        $normalizedCorrectAnswer = strtolower($correctAnswer);

        $isCorrect = $normalizedUserAnswer === $normalizedCorrectAnswer 
                   || $normalizedUserAnswer === strtolower($this->bands[$correctAnswer]['name']);

        if (!$isCorrect) {
            Cache::forget($cacheKey);
            return response()->json([
                'success' => false,
                'error' => 'Poser detected. Goodbye.'
            ], 422);
        }

        Cache::forget($cacheKey);
        return response()->json([
            'success' => true,
            'verified' => true,
        ]);
    }
}