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
        'deftones' => [
            'name' => 'Deftones',
            'albums' => ['deftones']
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
        'pantera' => [
            'name' => 'Pantera',
            'albums' => ['cowboys-from-hell'],
        ],
        'slipknot' => [
            'name' => 'Slipknot',
            'albums' => ['iowa'],
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
            $imagePath = public_path("images/albums/{$album}.jpg");
            
            if (!file_exists($imagePath)) {
                throw new \Exception("Album image not found: {$album}");
            }

            // Procesar imagen
            $image = $manager->read($imagePath)
                ->greyscale()
                ->contrast(rand(20, 40))
                ->blur(rand(1, 3))
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
                'error' => '¡Incorrecto! Respuesta correcta: '.$this->bands[$correctAnswer]['name'],
                'correct_answer' => $this->bands[$correctAnswer]['name'] // Solo para desarrollo
            ], 422);
        }

        Cache::forget($cacheKey);
        return response()->json([
            'success' => true,
            'message' => '¡Correcto! 🤘'
        ]);
    }
}