<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
//Crud Route
Route::get('/crud', [PostController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('crud');

//Route::delete('/post/{post}', [PostController::class, 'destroy'])->middleware(['auth', 'verified']);

Route::middleware(['auth'])->group(function(){
    Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('post.destroy');
    Route::post('/post', [PostController::class, 'store'])->name('post.store');
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::put('/post/{id}', [PostController::class, 'update'])->name('post.update');
});

//Auth Route
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
