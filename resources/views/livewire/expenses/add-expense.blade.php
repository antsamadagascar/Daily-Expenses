<div class="max-w-4xl mx-auto">
    <!-- En-tête -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Ajouter des dépenses</h1>
        <p class="mt-1 text-sm text-gray-600">
            Ajoutez une ou plusieurs dépenses en même temps
        </p>
    </div>

    <!-- Message de succès -->
    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulaire -->
    <form wire:submit="saveExpenses">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <!-- En-tête du formulaire -->
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">
                        Détails des dépenses ({{ count($expenses) }})
                    </h3>
                    <button 
                        type="button" 
                        wire:click="addExpenseRow"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-blue-600 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Ajouter une ligne
                    </button>
                </div>

                <!-- Liste des dépenses -->
                <div class="space-y-4">
                    @foreach($expenses as $index => $expense)
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="text-sm font-medium text-gray-900">
                                    Dépense #{{ $index + 1 }}
                                </h4>
                                <div class="flex space-x-2">
                                    <button 
                                        type="button" 
                                        wire:click="duplicateExpenseRow({{ $index }})"
                                        class="text-gray-400 hover:text-gray-600"
                                        title="Dupliquer"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                    @if(count($expenses) > 1)
                                        <button 
                                            type="button" 
                                            wire:click="removeExpenseRow({{ $index }})"
                                            class="text-red-400 hover:text-red-600"
                                            title="Supprimer"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <!-- Montant -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Montant *
                                    </label>
                                    <div class="relative">
                                        <input 
                                            wire:model="expenses.{{ $index }}.amount"
                                            type="number" 
                                            step="0.01"
                                            min="0"
                                            class="block w-full px-3 py-2 border @error('expenses.'.$index.'.amount') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                            placeholder="0,00"
                                        >
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">€</span>
                                        </div>
                                    </div>
                                    @error('expenses.'.$index.'.amount')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Catégorie -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Catégorie *
                                    </label>
                                    <select 
                                        wire:model="expenses.{{ $index }}.category_id"
                                        class="block w-full px-3 py-2 border @error('expenses.'.$index.'.category_id') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    >
                                        <option value="">Sélectionner...</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}
                                                @if($category->is_default) (Système) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('expenses.'.$index.'.category_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Budget *
                                    </label>
                                    <select wire:model="expenses.{{ $index }}.budget_id"  class="block w-full px-3 py-2 border @error('expenses.'.$index.'.category_id') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    >
                                        <option value="">-- Sélectionner un budget --</option>
                                        @foreach($budgets as $budget)
                                            <option value="{{ $budget->id }}">{{ $budget->name }} ({{ $budget->amount }} )</option>
                                        @endforeach
                                    </select>                       
                                </div>
                                <!-- Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Date *
                                    </label>
                                    <input 
                                        wire:model="expenses.{{ $index }}.expense_date"
                                        type="date"
                                        max="{{ date('Y-m-d') }}"
                                        class="block w-full px-3 py-2 border @error('expenses.'.$index.'.expense_date') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    >
                                    @error('expenses.'.$index.'.expense_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-2 lg:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Description *
                                    </label>
                                    <input 
                                        wire:model="expenses.{{ $index }}.description"
                                        type="text"
                                        class="block w-full px-3 py-2 border @error('expenses.'.$index.'.description') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                        placeholder="Description de la dépense"
                                    >
                                    @error('expenses.'.$index.'.description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Notes (optionnel) -->
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Notes (optionnel)
                                </label>
                                <textarea 
                                    wire:model="expenses.{{ $index }}.notes"
                                    rows="2"
                                    class="block w-full px-3 py-2 border @error('expenses.'.$index.'.notes') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                    placeholder="Notes supplémentaires..."
                                ></textarea>
                                @error('expenses.'.$index.'.notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Actions -->
                <div class="mt-6 flex justify-between">
                    <a 
                        href="{{ route('dashboard') }}" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Annuler
                    </a>
                    
                    <button 
                        type="submit"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                    >
                        <span wire:loading.remove>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Enregistrer les dépenses
                        </span>
                        <span wire:loading>
                            Enregistrement...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>