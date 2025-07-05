<?php

namespace App\Livewire\Expenses;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Expense;
use App\Models\Budget;
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
    public $dateFrom = '';
    public $dateTo = '';

    // Propriétés pour la modal d'édition
    public $showEditModal = false;
    public $editingExpenseId = null;
    public $editForm = [
        'description' => '',
        'amount' => '',
        'expense_date' => '',
        'category_id' => '',
        'notes' => ''
    ];

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }


    protected $rules = [
        'editForm.description' => 'required|string|max:255',
        'editForm.amount' => 'required|numeric|min:0',
        'editForm.expense_date' => 'required|date',
        'editForm.category_id' => 'required|exists:categories,id',
        'editForm.notes' => 'nullable|string|max:1000'
    ];

    protected $messages = [
        'editForm.description.required' => 'La description est obligatoire.',
        'editForm.amount.required' => 'Le montant est obligatoire.',
        'editForm.amount.numeric' => 'Le montant doit être un nombre.',
        'editForm.amount.min' => 'Le montant doit être positif.',
        'editForm.expense_date.required' => 'La date est obligatoire.',
        'editForm.category_id.required' => 'La catégorie est obligatoire.',
    ];

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

    public function sortByColumn($field)
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
        $query = Expense::with(['category', 'budget.expenses']);



        // Filtre par plage de dates OU par mois/année
        if (!empty($this->dateFrom) || !empty($this->dateTo)) {
            if (!empty($this->dateFrom)) {
                $query->whereDate('expense_date', '>=', $this->dateFrom);
            }
            if (!empty($this->dateTo)) {
                $query->whereDate('expense_date', '<=', $this->dateTo);
            }
        } else {
            $query->whereMonth('expense_date', $this->selectedMonth)
                  ->whereYear('expense_date', $this->selectedYear);
        }

        if (!empty($this->selectedCategory)) {
            $query->where('expenses.category_id', $this->selectedCategory);
        }

        if (!empty($this->searchTerm)) {
            $query->where(function($q) {
                $q->where('description', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('notes', 'like', '%' . $this->searchTerm . '%');
            });
        }
        switch ($this->sortBy) {
            case 'category_name':
                $query->leftJoin('categories', 'expenses.category_id', '=', 'categories.id')
                      ->orderBy('categories.name', $this->sortDirection)
                      ->select('expenses.*');
                break;
            default:
                $query->orderBy($this->sortBy, $this->sortDirection);
                break;
        }

        return $query->paginate(10);
    }

    public function getMonthlyTotalProperty()
    {
        $query = Expense::where('user_id', Auth::id());

        if (!empty($this->dateFrom) || !empty($this->dateTo)) {
            if (!empty($this->dateFrom)) {
                $query->whereDate('expense_date', '>=', $this->dateFrom);
            }
            if (!empty($this->dateTo)) {
                $query->whereDate('expense_date', '<=', $this->dateTo);
            }
        } else {
            $query->whereMonth('expense_date', $this->selectedMonth)
                ->whereYear('expense_date', $this->selectedYear);
        }

        if (!empty($this->selectedCategory)) {
            $query->where('category_id', $this->selectedCategory);
        }

        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('description', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('notes', 'like', '%' . $this->searchTerm . '%');
            });
        }

        return $query->sum('amount');
    }

    public function getCategoryTotalsProperty()
    {
        $query = Expense::with('category')
            ->where('user_id', Auth::id());

        if (!empty($this->dateFrom)) {
            $query->whereDate('expense_date', '>=', $this->dateFrom);
        }

        if (!empty($this->dateTo)) {
            $query->whereDate('expense_date', '<=', $this->dateTo);
        }

        if (empty($this->dateFrom) && empty($this->dateTo)) {
            $query->whereMonth('expense_date', $this->selectedMonth)
                ->whereYear('expense_date', $this->selectedYear);
        }

        if (!empty($this->selectedCategory)) {
            $query->where('category_id', $this->selectedCategory);
        }

        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('description', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('notes', 'like', '%' . $this->searchTerm . '%');
            });
        }

        return $query->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->get();
    }


    //  méthode pour ouvrir la modal d'édition
    public function editExpense($expenseId)
    {
        $expense = Expense::where('id', $expenseId)
            ->where('user_id', Auth::id())
            ->first();

        if ($expense) {
            $this->editingExpenseId = $expense->id;
            $this->editForm = [
                'description' => $expense->description,
                'amount' => $expense->amount,
                'expense_date' => $expense->expense_date->format('Y-m-d'),
                'category_id' => $expense->category_id,
                'notes' => $expense->notes ?? '',
                'budget_id' => $expense->budget_id
            ];
            $this->showEditModal = true;
        }
    }

    //  méthode pour sauvegarder les modifications
    public function updateExpense()
    {
        $this->validate();

        $expense = Expense::where('id', $this->editingExpenseId)
            ->where('user_id', Auth::id())
            ->first();

        if ($expense) {
            $expense->update([
                'description' => $this->editForm['description'],
                'amount' => $this->editForm['amount'],
                'expense_date' => $this->editForm['expense_date'],
                'category_id' => $this->editForm['category_id'],
                'notes' => $this->editForm['notes'],
                'budget_id' => $this->editForm['budget_id']
            ]);

            $this->closeEditModal();
            session()->flash('success', 'Dépense mise à jour avec succès !');
            $this->dispatch('$refresh');
        }
    }

    //  méthode pour fermer la modal
    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingExpenseId = null;
        $this->editForm = [
            'description' => '',
            'amount' => '',
            'expense_date' => '',
            'category_id' => '',
            'notes' => '',
            'budget_id' => '', 
        ];
        $this->resetErrorBag();
    }

    public function exportPdf()
    {
        $monthStart = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $expenses = Expense::with('category')
            ->where('user_id', Auth::id())
            ->whereBetween('expense_date', [$monthStart, $monthEnd])
            ->when($this->selectedCategory, fn($q) => $q->where('category_id', $this->selectedCategory))
            ->when($this->searchTerm, fn($q) =>
                $q->where(function($query) {
                    $query->where('description', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('notes', 'like', '%' . $this->searchTerm . '%');
                }))
            ->orderBy('expense_date', 'asc')
            ->get();

        $budgetIds = $expenses->pluck('budget_id')->unique()->filter();
        $budgets = \App\Models\Budget::whereIn('id', $budgetIds)->get();

        $today = Carbon::now();

        $budgetSummaries = $budgets->map(function ($budget) use ($monthStart, $monthEnd, $today) {
            $spentBefore = Expense::where('user_id', Auth::id())
                ->where('budget_id', $budget->id)
                ->whereBetween('expense_date', [$budget->start_date, $monthStart->copy()->subDay()])
                ->sum('amount');

            $spentThisMonth = Expense::where('user_id', Auth::id())
                ->where('budget_id', $budget->id)
                ->whereBetween('expense_date', [$monthStart, $monthEnd])
                ->sum('amount');

            $usedUntilNow = Expense::where('user_id', Auth::id())
                ->where('budget_id', $budget->id)
                ->whereBetween('expense_date', [$budget->start_date, $today])
                ->sum('amount');

            $remaining = max($budget->amount - $spentBefore - $spentThisMonth, 0);
            $remainingNow = max($budget->amount - $usedUntilNow, 0);

            return [
                'budget_name' => $budget->name,
                'budgeted' => $budget->amount,
                'spent_before' => $spentBefore,
                'spent_this_month' => $spentThisMonth,
                'used_until_now' => $usedUntilNow,
                'remaining' => $remaining,
                'remaining_now' => $remainingNow,
                'start_date' => $budget->start_date,
                'end_date' => $budget->end_date,
            ];
        });

        $monthName = $monthStart->locale('fr')->monthName;
        $total = $expenses->sum('amount');
        $categoryTotals = $this->categoryTotals;
        $selectedMonth = $this->selectedMonth;
        $selectedYear = $this->selectedYear;
        $totalRemaining = $budgetSummaries->sum('remaining');
        $monthStart = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->startOfMonth();


        $pdf = Pdf::loadView('exports.expenses-pdf', compact(
            'expenses',
            'monthName',
            'total',
            'categoryTotals',
            'selectedMonth',
            'selectedYear',
            'budgets',
            'budgetSummaries',
            'totalRemaining',
            'monthStart'
        ))->setPaper('a4', 'portrait')
        ->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true
        ]);

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, "depenses_{$monthName}_{$this->selectedYear}.pdf");
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
        $this->dateFrom = '';      
        $this->dateTo = '';       
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

    public function filterExpense($expense)
    {
        $date = \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d');

        if ($this->dateFrom && $this->dateTo && $this->dateFrom === $this->dateTo) {
            if ($date !== $this->dateFrom) return false;
        } elseif ($this->dateFrom && $this->dateTo) {
            if ($date < $this->dateFrom || $date > $this->dateTo) return false;
        } elseif ($this->dateFrom) {
            if ($date < $this->dateFrom) return false;
        } elseif ($this->dateTo) {
            if ($date > $this->dateTo) return false;
        } else {
            if ((int) \Carbon\Carbon::parse($expense->expense_date)->month !== (int) $this->selectedMonth) return false;
            if ((int) \Carbon\Carbon::parse($expense->expense_date)->year !== (int) $this->selectedYear) return false;
        }

        if (!empty($this->selectedCategory) && $expense->category_id != $this->selectedCategory) return false;

        if (!empty($this->searchTerm)) {
            $content = strtolower($expense->description . ' ' . $expense->notes);
            if (!str_contains($content, strtolower($this->searchTerm))) return false;
        }

        return true;
    }

    public function getBudgetUsage($budget)
    {
        $filteredExpenses = $budget->expenses->filter(fn($e) => $this->filterExpense($e));
        $usedAmount = $filteredExpenses->sum('amount');
        $remainingAmount = $budget->amount - $usedAmount;

        return [
            'used' => $usedAmount,
            'remaining' => $remainingAmount,
        ];
    }

}