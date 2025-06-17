<?php

namespace App\Livewire\Expenses;

use Livewire\Component;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AddExpense extends Component
{
    public $expenses = [];
    public $categories = [];

    protected $rules = [
        'expenses.*.amount' => 'required|numeric|min:0.01|max:999999.99',
        'expenses.*.description' => 'required|string|max:255',
        'expenses.*.category_id' => 'required|exists:categories,id',
        'expenses.*.expense_date' => 'required|date|before_or_equal:today',
        'expenses.*.notes' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'expenses.*.amount.required' => 'Le montant est requis',
        'expenses.*.amount.numeric' => 'Le montant doit être un nombre',
        'expenses.*.amount.min' => 'Le montant doit être supérieur à 0',
        'expenses.*.amount.max' => 'Le montant ne peut pas dépasser 999 999,99 €',
        'expenses.*.description.required' => 'La description est requise',
        'expenses.*.description.max' => 'La description ne peut pas dépasser 255 caractères',
        'expenses.*.category_id.required' => 'La catégorie est requise',
        'expenses.*.category_id.exists' => 'La catégorie sélectionnée n\'existe pas',
        'expenses.*.expense_date.required' => 'La date est requise',
        'expenses.*.expense_date.date' => 'La date doit être valide',
        'expenses.*.expense_date.before_or_equal' => 'La date ne peut pas être dans le futur',
        'expenses.*.notes.max' => 'Les notes ne peuvent pas dépasser 1000 caractères',
    ];

    public function mount()
    {
        $this->loadCategories();
        $this->addExpenseRow();
    }

    public function loadCategories()
    {
        $this->categories = Category::forUser(Auth::id())
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get();
    }

    public function addExpenseRow()
    {
        $this->expenses[] = [
            'amount' => '',
            'description' => '',
            'category_id' => '',
            'expense_date' => Carbon::today()->format('Y-m-d'),
            'notes' => '',
        ];
    }

    public function removeExpenseRow($index)
    {
        if (count($this->expenses) > 1) {
            unset($this->expenses[$index]);
            $this->expenses = array_values($this->expenses);
        }
    }

    public function duplicateExpenseRow($index)
    {
        $expense = $this->expenses[$index];
        $expense['description'] = $expense['description'] . ' (copie)';
        $this->expenses[] = $expense;
    }

    public function saveExpenses()
    {
        $this->validate();

        $savedCount = 0;
        foreach ($this->expenses as $expenseData) {
            if (!empty($expenseData['amount']) && !empty($expenseData['description'])) {
                Expense::create([
                    'user_id' => Auth::id(),
                    'category_id' => $expenseData['category_id'],
                    'amount' => $expenseData['amount'],
                    'description' => $expenseData['description'],
                    'expense_date' => $expenseData['expense_date'],
                    'notes' => $expenseData['notes'],
                ]);
                $savedCount++;
            }
        }

        session()->flash('success', "{$savedCount} dépense(s) ajoutée(s) avec succès !");
        
        // Réinitialise le formulaire
        $this->expenses = [];
        $this->addExpenseRow();
    }

    public function render()
    {
        return view('livewire.expenses.add-expense')
            ->layout('layouts.app');
    }
}