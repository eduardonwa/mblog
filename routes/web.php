<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/posts/{post}/like', [LikeController::class, 'toggleLike'])
    ->middleware(['auth'])
    ->name('posts.like');

Route::get('posts', [PostController::class, 'index'])->name('post.index');
Route::get('post/{post:slug}', [PostController::class, 'show'])->name('post.show');
Route::get('tag/{slug}', [PostController::class, 'postByTag'])->name('tag.show');
Route::get('category/{slug}', [PostController::class, 'postByCategory'])->name('category.show');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
