<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PostController;
use App\Http\Controllers\InventoryController;

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

/*coba inventory
Route::get('/inventory', function () {
    return Inertia::render('Inventory');
})->middleware(['auth', 'verified'])->name('inventory');
*/
//Crud Route
Route::get('/crud', [PostController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('crud');

Route::get('/inventory', [InventoryController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('inventory');

//Auth Route
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*Route::prefix('api')->middleware(['auth'])->group(function () {
    Route::prefix('post')->group(function () {
        Route::get('/page', [PostController::class, 'getTable']);
        Route::delete('/destroy/{id}', [PostController::class, 'destroy']);
        Route::post('/store', [PostController::class, 'store']);
    });
});
//Route inventory
Route::prefix('api')->middleware(['auth'])->group(function () {
    Route::prefix('inventory')->group(function () {
        Route::get('/page', [InventoryController::class, 'getTable']);
        Route::delete('/destroy/{name}', [InventoryController::class, 'destroy']);
        Route::post('/store', [InventoryController::class, 'store']);
    });
});
*/
//new route
Route::middleware(['auth'])->prefix('api')->group(function(){
    Route::prefix('post')->group(function () {
        Route::get('/page', [PostController::class, 'getTable']);
        Route::delete('/destroy/{id}', [PostController::class, 'destroy']);
        Route::post('/store', [PostController::class, 'store']);
    });
    Route::prefix('inventory')->group(function () {
        Route::get('/page', [InventoryController::class, 'getTable']);
        Route::delete('/destroy/{name}', [InventoryController::class, 'destroy']);
        Route::post('/store', [InventoryController::class, 'store']);
    });
});
require __DIR__.'/auth.php';