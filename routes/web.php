<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;

// Routes publiques
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', Login::class)
    ->name('login')
    ->middleware('guest');

// Routes protégées
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
});

// Redirection automatique vers dashboard si connecté
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->middleware('auth');
