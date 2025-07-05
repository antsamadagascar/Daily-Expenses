<?php
namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Expense;
use App\Models\Category;
use App\Models\Budget;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class Dashboard extends Component
{
    public $selectedYear;
    public $selectedMonth;
    public $availableYears;

    public function mount()
    {
        $this->selectedYear = Carbon::now()->year;
        $this->selectedMonth = Carbon::now()->month;
        $this->availableYears = Expense::where('user_id', Auth::id())
            ->selectRaw('DISTINCT YEAR(expense_date) as year')
            ->pluck('year')
            ->sort()
            ->values();
        if ($this->availableYears->isEmpty()) {
            $this->availableYears = collect([Carbon::now()->year]);
        }
    }

    public function updated($propertyName)
    {
        // Ensure valid month and year
        if ($propertyName === 'selectedMonth' || $propertyName === 'selectedYear') {
            $this->selectedMonth = max(1, min(12, (int)$this->selectedMonth));
            $this->selectedYear = max(2000, min(Carbon::now()->year, (int)$this->selectedYear));
        }
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard', [
            'currentMonthExpenses' => $this->currentMonthExpenses,
            'currentMonthCount' => $this->currentMonthCount,
            'dailyAverage' => $this->dailyAverage,
            'topCategories' => $this->topCategories,
            'recentExpenses' => $this->recentExpenses,
            'monthlyEvolution' => $this->monthlyEvolution,
            'yearlyComparison' => $this->yearlyComparison,
            'weeklyTrend' => $this->weeklyTrend,
            'categoryDistribution' => $this->categoryDistribution,
            'budgetProgress' => $this->budgetProgress,
            'expensesByDay' => $this->expensesByDay,
            'previousMonthComparison' => $this->previousMonthComparison,
            'availableYears' => $this->availableYears,
            'selectedYear' => $this->selectedYear,
            'selectedMonth' => $this->selectedMonth,
        ])->layout('layouts.app');
    }

    public function getCurrentMonthExpensesProperty()
    {
        return Expense::where('user_id', Auth::id())
            ->whereMonth('expense_date', $this->selectedMonth)
            ->whereYear('expense_date', $this->selectedYear)
            ->sum('amount');
    }

    public function getCurrentMonthCountProperty()
    {
        return Expense::where('user_id', Auth::id())
            ->whereMonth('expense_date', $this->selectedMonth)
            ->whereYear('expense_date', $this->selectedYear)
            ->count();
    }

    public function getDailyAverageProperty()
    {
        $daysInMonth = Carbon::create($this->selectedYear, $this->selectedMonth)->daysInMonth;
        $currentDay = min(Carbon::now()->day, $daysInMonth);
        $monthExpenses = $this->currentMonthExpenses;
        return $currentDay > 0 ? $monthExpenses / $currentDay : 0;
    }

    public function getTopCategoriesProperty()
    {
        return Expense::with('category')
            ->where('user_id', Auth::id())
            ->whereMonth('expense_date', $this->selectedMonth)
            ->whereYear('expense_date', $this->selectedYear)
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
            ->limit(10)
            ->get();
    }

    public function getMonthlyEvolutionProperty()
    {
        $months = [];
        $endDate = Carbon::create($this->selectedYear, $this->selectedMonth);
        $startDate = $endDate->copy()->subMonths(11);
        
        while ($startDate <= $endDate) {
            $total = Expense::where('user_id', Auth::id())
                ->whereMonth('expense_date', $startDate->month)
                ->whereYear('expense_date', $startDate->year)
                ->sum('amount');
            
            $months[] = [
                'month' => $startDate->format('M Y'),
                'total' => $total,
                'month_num' => $startDate->month,
                'year' => $startDate->year
            ];
            $startDate->addMonth();
        }
        
        return $months;
    }

    public function getYearlyComparisonProperty()
    {
        $previousYear = $this->selectedYear - 1;
        
        $currentYearTotal = Expense::where('user_id', Auth::id())
            ->whereYear('expense_date', $this->selectedYear)
            ->sum('amount');
            
        $previousYearTotal = Expense::where('user_id', Auth::id())
            ->whereYear('expense_date', $previousYear)
            ->sum('amount');
            
        return [
            'current_year' => $currentYearTotal,
            'previous_year' => $previousYearTotal,
            'difference' => $currentYearTotal - $previousYearTotal,
            'percentage' => $previousYearTotal > 0 ? (($currentYearTotal - $previousYearTotal) / $previousYearTotal) * 100 : 0
        ];
    }

    public function getWeeklyTrendProperty()
    {
        $weeks = [];
        $endDate = Carbon::create($this->selectedYear, $this->selectedMonth)->endOfMonth();
        $startDate = $endDate->copy()->subWeeks(7);
        
        for ($i = 0; $i < 8; $i++) {
            $weekStart = $startDate->copy()->addWeeks($i)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();
            
            $total = Expense::where('user_id', Auth::id())
                ->whereBetween('expense_date', [$weekStart, $weekEnd])
                ->sum('amount');
                
            $weeks[] = [
                'week' => $weekStart->format('d/m') . ' - ' . $weekEnd->format('d/m'),
                'total' => $total,
                'start_date' => $weekStart->format('Y-m-d')
            ];
        }
        
        return $weeks;
    }

    public function getCategoryDistributionProperty()
    {
        return Expense::with('category')
            ->where('user_id', Auth::id())
            ->whereMonth('expense_date', $this->selectedMonth)
            ->whereYear('expense_date', $this->selectedYear)
            ->selectRaw('category_id, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category->name ?? 'Sans catégorie',
                    'color' => $item->category->color ?? '#6B7280',
                    'total' => $item->total,
                    'count' => $item->count,
                    'percentage' => 0 
                ];
            });
    }

    public function getBudgetProgressProperty()
    {
        $currentMonth = Carbon::create($this->selectedYear, $this->selectedMonth);
        
        return Budget::where('user_id', Auth::id())
            ->where('is_active', true)
            ->where('start_date', '<=', $currentMonth)
            ->where('end_date', '>=', $currentMonth)
            ->get()
            ->map(function ($budget) use ($currentMonth) {
                $spent = Expense::where('user_id', Auth::id())
                    ->whereBetween('expense_date', [$budget->start_date, $budget->end_date])
                    ->sum('amount');
                    
                return [
                    'name' => $budget->name,
                    'budgeted' => $budget->amount,
                    'spent' => $spent,
                    'remaining' => $budget->amount - $spent,
                    'percentage' => $budget->amount > 0 ? ($spent / $budget->amount) * 100 : 0,
                    'status' => $spent > $budget->amount ? 'exceeded' : ($spent > $budget->amount * 0.8 ? 'warning' : 'good')
                ];
            });
    }

    public function getExpensesByDayProperty()
    {
        $days = [];
        $startOfMonth = Carbon::create($this->selectedYear, $this->selectedMonth)->startOfMonth();
        $endOfMonth = Carbon::create($this->selectedYear, $this->selectedMonth)->endOfMonth();
        
        $expenses = Expense::where('user_id', Auth::id())
            ->whereBetween('expense_date', [$startOfMonth, $endOfMonth])
            ->selectRaw('DATE(expense_date) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');
            
        for ($date = $startOfMonth->copy(); $date <= $endOfMonth; $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            $days[] = [
                'date' => $dateStr,
                'day' => $date->format('d'),
                'total' => $expenses->get($dateStr)->total ?? 0,
                'is_today' => $date->isToday()
            ];
        }
        
        return $days;
    }

    public function getPreviousMonthComparisonProperty()
    {
        $currentMonth = Carbon::create($this->selectedYear, $this->selectedMonth);
        $previousMonth = $currentMonth->copy()->subMonth();
        
        $currentTotal = Expense::where('user_id', Auth::id())
            ->whereMonth('expense_date', $currentMonth->month)
            ->whereYear('expense_date', $currentMonth->year)
            ->sum('amount');
            
        $previousTotal = Expense::where('user_id', Auth::id())
            ->whereMonth('expense_date', $previousMonth->month)
            ->whereYear('expense_date', $previousMonth->year)
            ->sum('amount');
            
        return [
            'current' => $currentTotal,
            'previous' => $previousTotal,
            'difference' => $currentTotal - $previousTotal,
            'percentage' => $previousTotal > 0 ? (($currentTotal - $previousTotal) / $previousTotal) * 100 : 0,
            'trend' => $currentTotal > $previousTotal ? 'up' : 'down'
        ];
    }
}
?>