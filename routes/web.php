<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HeroController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\GameInfoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CommunityController;
use Illuminate\Support\Facades\Route;


// --- HALAMAN UTAMA ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// --- DATABASE HEROES & ITEMS (Public Access) ---
Route::prefix('heroes')->name('heroes.')->group(function () {
    Route::get('/', [HeroController::class, 'index'])->name('index');
    Route::get('/{id}', [HeroController::class, 'show'])->name('show');
});

Route::prefix('items')->name('items.')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('index');
    Route::get('/{id}', [ItemController::class, 'show'])->name('show');
});

Route::get('/patch/{version}', [GameInfoController::class, 'showPatch'])->name('patch.show');
Route::get('/match/{match_id}', [GameInfoController::class, 'showMatch'])->name('match.show');

// --- FITUR KOMUNITAS / DASHBOARD (Auth Required) ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('community')->name('community.')->group(function () {
    // Public
    Route::get('/', [CommunityController::class, 'index'])->name('index');
    Route::get('/post/{slug}', [CommunityController::class, 'show'])->name('show');
    
    // Auth Required
    Route::middleware('auth')->group(function () {
        Route::get('/create', [CommunityController::class, 'create'])->name('create');
        Route::post('/store', [CommunityController::class, 'store'])->name('store');
        Route::post('/post/{post}/comment', [CommunityController::class, 'comment'])->name('comment.store');
        Route::post('/post/{post}/like', [CommunityController::class, 'likePost'])->name('like');
    });
});

require __DIR__.'/auth.php';