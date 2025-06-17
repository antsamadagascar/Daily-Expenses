<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Dashboard extends Component
{
 
    public function render()
    {
        return view('livewire.dashboard', [
            'currentMonthExpenses' => $this->currentMonthExpenses,
            'currentMonthCount' => $this->currentMonthCount,
            'dailyAverage' => $this->dailyAverage,
            'topCategories' => $this->topCategories,
            'recentExpenses' => $this->recentExpenses,
        ])->layout('layouts.app');
    }

    public function getCurrentMonthExpensesProperty()
    {
        return Expense::where('user_id', Auth::id())
            ->whereMonth('expense_date', Carbon::now()->month)
            ->whereYear('expense_date', Carbon::now()->year)
            ->sum('amount');
    }

    public function getCurrentMonthCountProperty()
    {
        return Expense::where('user_id', Auth::id())
            ->whereMonth('expense_date', Carbon::now()->month)
            ->whereYear('expense_date', Carbon::now()->year)
            ->count();
    }

    public function getDailyAverageProperty()
    {
        $daysInMonth = Carbon::now()->daysInMonth;
        $currentDay = Carbon::now()->day;
        $monthExpenses = $this->currentMonthExpenses;
        
        return $currentDay > 0 ? $monthExpenses / $currentDay : 0;
    }

    public function getTopCategoriesProperty()
    {
        return Expense::with('category')
            ->where('user_id', Auth::id())
            ->whereMonth('expense_date', Carbon::now()->month)
            ->whereYear('expense_date', Carbon::now()->year)
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
    }

    public function getRecentExpensesProperty()
    {
        return Expense::with('category')
            ->where('user_id', Auth::id())
            ->orderBy('expense_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

}