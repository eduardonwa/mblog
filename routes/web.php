<?php

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserPublicProfileController;

// Grupo para rutas públicas
Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('/captcha/generate', [CaptchaController::class, 'generateMetalCaptcha']);
Route::post('/captcha/validate', [CaptchaController::class, 'validateCaptcha']);
Route::get('posts', [PostController::class, 'index'])->name('post.index');
Route::get('post/{post:slug}', [PostController::class, 'show'])->name('post.show');
Route::get('tag/{slug}', [PostController::class, 'postByTag'])->name('tag.show');
Route::get('category/{slug}', [CategoryController::class, 'index'])->name('category.index');
Route::get('members/{user:username}/posts', [UserPublicProfileController::class, 'index'])->name('author.posts');
Route::get('channels', [ChannelController::class, 'index'])->name('channel.index');
Route::get('/channels/{channel:slug}', [ChannelController::class, 'show'])->name('channel.show');
Route::get('/channel/{channel:slug}/{post:slug}', [ChannelController::class, 'showPost'])->name('channel.post.show');

// Grupo para invitados (no autenticados)
Route::middleware('guest')->group(function () {
    Route::inertia('/login', 'auth/Login')->name('login');
    Route::inertia('/register', 'auth/Register')->name('register');
});

// Grupo para usuarios autenticados y verificados
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');

    // Interacciones con posts (versión única)
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::delete('/posts/{post}/unlike', [PostController::class, 'unlike'])->name('posts.unlike');

    // Comentarios (versión única)
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/replies', [CommentController::class, 'storeReply'])->name('comments.replies.store');
});

Route::middleware('redirect.to.register')->group(function () {
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::delete('/posts/{post}/unlike', [PostController::class, 'unlike'])->name('posts.unlike');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent');
})->middleware(['auth'])->name('verification.send');