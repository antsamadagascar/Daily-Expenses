<?php

namespace App\Livewire\Budget;

use App\Models\Budget;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class BudgetManager extends Component
{
    use WithPagination;

    public $budgetId;
    public $name = '';
    public $description = '';
    public $amount = '';
    public $start_date = '';
    public $end_date = '';
    public $period = 'monthly';
    public $is_active = true;

    public $showModal = false;
    public $isEditing = false;
    public $showDeleteConfirm = false;
    public $budgetToDelete;

    public $filterActive = 'all';
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'amount' => 'required|numeric|min:0',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'period' => 'required|in:monthly,weekly,yearly,custom',
        'is_active' => 'boolean'
    ];

    protected $messages = [
        'name.required' => 'Le nom du budget est obligatoire.',
        'amount.required' => 'Le montant est obligatoire.',
        'amount.numeric' => 'Le montant doit être un nombre.',
        'amount.min' => 'Le montant doit être positif.',
        'start_date.required' => 'La date de début est obligatoire.',
        'end_date.required' => 'La date de fin est obligatoire.',
        'end_date.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.'
    ];

    public function mount()
    {
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        $budgets = Budget::forUser(Auth::id())
            ->when($this->filterActive !== 'all', function ($query) {
                $query->where('is_active', $this->filterActive === 'active');
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.budget-manager', [
            'budgets' => $budgets
        ])->layout('layouts.app');
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        $budgetData = [
            'user_id' => Auth::id(),
            'name' => $this->name,
            'description' => $this->description,
            'amount' => $this->amount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'period' => $this->period,
            'is_active' => $this->is_active
        ];

        if ($this->isEditing) {
            Budget::find($this->budgetId)->update($budgetData);
            session()->flash('success', 'Budget mis à jour avec succès !');
        } else {
            Budget::create($budgetData);
            session()->flash('success', 'Budget créé avec succès !');
        }

        $this->closeModal();
    }

    public function edit($budgetId)
    {
        $budget = Budget::findOrFail($budgetId);

        if ($budget->user_id !== Auth::id()) {
            session()->flash('error', 'Accès non autorisé.');
            return;
        }

        $this->budgetId = $budget->id;
        $this->name = $budget->name;
        $this->description = $budget->description;
        $this->amount = $budget->amount;
        $this->start_date = $budget->start_date->format('Y-m-d');
        $this->end_date = $budget->end_date->format('Y-m-d');
        $this->period = $budget->period;
        $this->is_active = $budget->is_active;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function confirmDelete($budgetId)
    {
        $this->budgetToDelete = $budgetId;
        $this->showDeleteConfirm = true;
    }

    public function delete()
    {
        $budget = Budget::findOrFail($this->budgetToDelete);

        if ($budget->user_id !== Auth::id()) {
            session()->flash('error', 'Accès non autorisé.');
            return;
        }

        $budget->delete();
        session()->flash('success', 'Budget supprimé avec succès !');

        $this->showDeleteConfirm = false;
        $this->budgetToDelete = null;
    }

    public function toggleActive($budgetId)
    {
        $budget = Budget::findOrFail($budgetId);

        if ($budget->user_id !== Auth::id()) {
            session()->flash('error', 'Accès non autorisé.');
            return;
        }

        $budget->update(['is_active' => !$budget->is_active]);
        session()->flash('success', 'Statut du budget mis à jour !');
    }

    public function createMultipleBudgets()
    {
        $this->validate([
            'name' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'period' => 'required|in:monthly,weekly,yearly'
        ]);

        $startDate = now()->startOfMonth();
        $budgets = [];

        for ($i = 0; $i < 12; $i++) {
            $budgetStartDate = $startDate->copy()->addMonths($i);
            $budgetEndDate = $budgetStartDate->copy()->endOfMonth();

            $budgets[] = [
                'user_id' => Auth::id(),
                'name' => $this->name . ' - ' . $budgetStartDate->format('M Y'),
                'description' => $this->description,
                'amount' => $this->amount,
                'start_date' => $budgetStartDate->format('Y-m-d'),
                'end_date' => $budgetEndDate->format('Y-m-d'),
                'period' => $this->period,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        Budget::insert($budgets);
        session()->flash('success', '12 budgets mensuels créés avec succès !');
        $this->closeModal();
    }

    private function resetForm()
    {
        $this->budgetId = null;
        $this->name = '';
        $this->description = '';
        $this->amount = '';
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->endOfMonth()->format('Y-m-d');
        $this->period = 'monthly';
        $this->is_active = true;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterActive()
    {
        $this->resetPage();
    }
}
