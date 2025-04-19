<?php

use Inertia\Inertia;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\WelcomeController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/captcha/generate', [CaptchaController::class, 'generateMetalCaptcha']);
Route::post('/captcha/validate', [CaptchaController::class, 'validateCaptcha']);
Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('posts', [PostController::class, 'index'])->name('post.index');
Route::get('post/{post:slug}', [PostController::class, 'show'])->name('post.show');
Route::get('tag/{slug}', [PostController::class, 'postByTag'])->name('tag.show');
Route::get('category/{slug}', [PostController::class, 'postByCategory'])->name('category.show');
Route::get('author/{user:name}/posts', [PostController::class, 'postByAuthor'])->name('author.posts');

// grupo para invitados (no autenticados)
Route::middleware('guest')->group(function () {
    // Ruta de login (vista Inertia)
    Route::get('/login', function () {
        return Inertia::render('auth/Login');
    })->name('login');
    
    // Ruta de registro (opcional)
    Route::get('/register', function () {
        return Inertia::render('auth/Register');
    })->name('register');
});

// Grupo para usuarios autenticados
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    
    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
});

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::delete('/posts/{post}/unlike', [PostController::class, 'unlike'])->name('posts.unlike');
});

Route::middleware('redirect.to.register')->group(function () {
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::delete('/posts/{post}/unlike', [PostController::class, 'unlike'])->name('posts.unlike');
});

Route::middleware(['auth', 'kreator'])->group(function () {
    //
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent');
})->middleware(['auth'])->name('verification.send');
