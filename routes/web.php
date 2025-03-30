<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('post/{post:slug}', [PostController::class, 'show'])->name('post.show');
Route::get('tag/{slug}', [PostController::class, 'postByTag'])->name('tag.show');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
