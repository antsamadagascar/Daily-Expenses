<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Expenses\AddExpense;
use App\Livewire\Expenses\ViewExpenses;

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
    Route::get('/expenses/add', AddExpense::class)->name('expenses.add');
    Route::get('/expenses', ViewExpenses::class)->name('expenses.view');
});
Route::get('/budgets', App\Livewire\BudgetManager::class)->name('budgets.index');
// Redirection automatique vers dashboard si connecté
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->middleware('auth');