<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Expenses\AddExpense;
use App\Livewire\Expenses\ViewExpenses;
use App\Livewire\System\ResetData;
use App\Livewire\Budget\BudgetManager;
use App\Livewire\Dashboard\Dashboard;

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
Route::get('/budgets', BudgetManager::class)->name('budgets.index');


Route::get('/system/reset-data', ResetData::class)
    ->middleware(['auth'])  
    ->name('system.reset-data');

// Redirection automatique vers dashboard si connecté
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->middleware('auth');