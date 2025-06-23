<?php

namespace App\Livewire\Expenses;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewExpenses extends Component
{
    use WithPagination;

    public $selectedMonth;
    public $selectedYear;
    public $selectedCategory = '';
    public $searchTerm = '';
    public $sortBy = 'expense_date';
    public $sortDirection = 'desc';
    public $categories = [];
    public $availableYears = [];

    
    public function mount()
    {
        $this->selectedMonth = Carbon::now()->month;
        $this->selectedYear = Carbon::now()->year;
        $this->loadCategories();
        $this->loadAvailableYears();
    }

    public function loadCategories()
    {
        $this->categories = Category::forUser(Auth::id())
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedSelectedMonth()
    {
        $this->resetPage();
    }

    public function updatedSelectedYear()
    {
        $this->resetPage();
    }

    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function getExpensesProperty()
    {
        $query = Expense::with('category')
            ->where('user_id', Auth::id())
            ->whereMonth('expense_date', $this->selectedMonth)
            ->whereYear('expense_date', $this->selectedYear);

        if (!empty($this->selectedCategory)) {
            $query->where('category_id', $this->selectedCategory);
        }

        if (!empty($this->searchTerm)) {
            $query->where(function($q) {
                $q->where('description', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('notes', 'like', '%' . $this->searchTerm . '%');
            });
        }

        return $query->orderBy($this->sortBy, $this->sortDirection)
                    ->paginate(10);
    }

    public function getMonthlyTotalProperty()
    {
        return Expense::where('user_id', Auth::id())
            ->whereMonth('expense_date', $this->selectedMonth)
            ->whereYear('expense_date', $this->selectedYear)
            ->when(!empty($this->selectedCategory), function($q) {
                $q->where('category_id', $this->selectedCategory);
            })
            ->when(!empty($this->searchTerm), function($q) {
                $q->where(function($query) {
                    $query->where('description', 'like', '%' . $this->searchTerm . '%')
                          ->orWhere('notes', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->sum('amount');
    }

    public function getCategoryTotalsProperty()
    {
        return Expense::with('category')
            ->where('user_id', Auth::id())
            ->whereMonth('expense_date', $this->selectedMonth)
            ->whereYear('expense_date', $this->selectedYear)
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->get();
    }

    public function exportPdf()
    {
        $expenses = Expense::with('category')
            ->where('user_id', Auth::id())
            ->whereMonth('expense_date', $this->selectedMonth)
            ->whereYear('expense_date', $this->selectedYear)
            ->when(!empty($this->selectedCategory), function($q) {
                $q->where('category_id', $this->selectedCategory);
            })
            ->when(!empty($this->searchTerm), function($q) {
                $q->where(function($query) {
                    $query->where('description', 'like', '%' . $this->searchTerm . '%')
                          ->orWhere('notes', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->orderBy('expense_date', 'asc')
            ->get();

        $monthName = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->locale('fr')->monthName;
        $total = $expenses->sum('amount');
        $categoryTotals = $this->categoryTotals;

        $selectedMonth = $this->selectedMonth;
        $selectedYear = $this->selectedYear;

        $pdf = Pdf::loadView('exports.expenses-pdf', compact(
            'expenses', 
            'monthName', 
            'total', 
            'categoryTotals',
            'selectedMonth',
            'selectedYear'
        ));

        $filename = "depenses_{$monthName}_{$this->selectedYear}.pdf";
        
        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function deleteExpense($expenseId)
    {
        $expense = Expense::where('id', $expenseId)
            ->where('user_id', Auth::id())
            ->first();

        if ($expense) {
            $expense->delete();
            session()->flash('success', 'Dépense supprimée avec succès !');
            $this->dispatch('$refresh');
        }
    }

    public function resetFilters()
    {
        $this->selectedCategory = '';
        $this->searchTerm = '';
        $this->selectedMonth = Carbon::now()->month;
        $this->selectedYear = Carbon::now()->year;
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.expenses.view-expenses', [
            'expenses' => $this->expenses,
            'monthlyTotal' => $this->monthlyTotal,
            'categoryTotals' => $this->categoryTotals,
            'monthName' => Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->locale('fr')->monthName
        ])->layout('layouts.app');
    }

    public function loadAvailableYears()
    {
        $this->availableYears = Expense::where('user_id', Auth::id())
            ->selectRaw('YEAR(expense_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();
    }

}