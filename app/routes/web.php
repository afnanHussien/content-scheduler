<?php

use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::view('/', 'welcome');
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/register', 'auth.register')->name('register');
});
Route::middleware('auth')->group(function () {
   Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/posts', 'posts.index')->name('posts.index');
    Route::view('/posts/create', 'posts.create')->name('posts.create');
    Route::get('/posts/{id}/edit', function ($id) {
        return view('posts.edit', ['postId' => $id]);
    })->name('posts.edit');
    // Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
});