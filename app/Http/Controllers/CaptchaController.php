<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\Conversions\ImageGenerators\Image;

class CaptchaController extends Controller
{
    private array $bands = [
        'sabbath' => [
            'name' => 'Black Sabbath',
            'albums' => ['master-of-reality']
        ],
        'cattle-decapitation' => [
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
        'dream-theater' => [
            'name' => 'Dream Theater',
            'albums' => ['images-and-words'],
        ],
        'iron-maiden' => [
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
        'in-flames' => [
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
        'the-zenith-passage' => [
            'name' => 'The Zenith Passage',
            'albums' => ['datalysium'],
        ],
    ];

    public function generateMetalCaptcha()
    {
        try {
            $bandKey = array_rand($this->bands);
            $band = $this->bands[$bandKey];
            $album = $band['albums'][array_rand($band['albums'])];
            $imagePath = public_path("images/albums/{$album}.webp");
    
            if (!file_exists($imagePath)) {
                throw new \Exception("Image not found");
            }
    
            // Almacena la KEY de la banda, no el nombre del álbum
            Cache::put(
                'captcha_'.request()->ip(),
                $bandKey, // <- Cambio crucial aquí
                now()->addMinutes(10)
            );
    
            // Procesamiento mínimo
            $image = (new ImageManager(new Driver()))
                ->read($imagePath)
                ->greyscale()
                ->contrast(rand(40, 60))
                ->colorize(
                    rand(-30, 30),
                    rand(-30, 30),
                    rand(-30, 30)
                )
                ->pixelate(24); // Solo efecto esencial
    
            return response($image->toWebp(75))
                ->header('Content-Type', 'image/webp')
                ->header('Cache-Control', 'no-store, max-age=0');
    
        } catch (\Exception $e) {
            Log::error("CAPTCHA Error: {$e->getMessage()}");
            return response()->json(['error' => 'CAPTCHA failed'], 500);
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
            return response()->json([
                'success' => false,
                'error' => 'Wrong answer. Poser detected.'
            ], 422);
        }

        return response()->json(['success' => true]);
    }
}