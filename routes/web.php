<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\WelcomeController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::delete('/posts/{post}/unlike', [PostController::class, 'unlike'])->name('posts.unlike');
});

Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('posts', [PostController::class, 'index'])->name('post.index');
Route::get('post/{post:slug}', [PostController::class, 'show'])->name('post.show');
Route::get('tag/{slug}', [PostController::class, 'postByTag'])->name('tag.show');
Route::get('category/{slug}', [PostController::class, 'postByCategory'])->name('category.show');
Route::get('author/{user:name}/posts', [PostController::class, 'postByAuthor'])->name('author.posts');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
