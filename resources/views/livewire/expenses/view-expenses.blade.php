<div class="space-y-6">
    <!-- En-tête avec titre et résumé -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Mes Dépenses - {{ ucfirst($monthName) }} {{ $selectedYear }}
                </h1>
                <div class="flex space-x-3">
                    <button wire:click="exportPdf" 
                            wire:loading.attr="disabled"
                            wire:target="exportPdf"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50">
                        <svg wire:loading.remove wire:target="exportPdf" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <svg wire:loading wire:target="exportPdf" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="exportPdf">Export PDF</span>
                        <span wire:loading wire:target="exportPdf">Génération...</span>
                    </button>
                    <button wire:click="resetFilters" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Réinitialiser
                    </button>
                </div>
            </div>

            <!-- Résumé du mois -->
            <div class="bg-blue-50 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-blue-900">Total du mois</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($monthlyTotal, 2) }}Ar</p>
                    </div>
                    <div class="text-blue-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Résumé des budgets utilisés -->
        <div class="bg-green-50 rounded-lg p-4 mb-6">
            <h3 class="text-lg font-medium text-green-900 mb-4">Budgets utilisés ce mois</h3>

            @php
                $budgetsUtilises = $expenses->pluck('budget')->filter()->unique('id');
            @endphp

            @forelse ($budgetsUtilises as $budget)
                <div class="mb-3 border border-green-200 rounded-md p-3 bg-white shadow-sm">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Budget : <span class="font-semibold text-gray-800">{{ $budget->name }}</span></p>
                            <p class="text-sm text-gray-500">Montant alloué : <span class="font-semibold text-gray-800">{{ number_format($budget->amount, 0, ',', ' ') }} Ar</span></p>
                            <p class="text-sm text-gray-500">Dépensé : 
                                <span class="font-semibold text-gray-800">
                                    {{ number_format($budget->expenses->whereBetween('expense_date', [
                                        \Carbon\Carbon::create($selectedYear, $selectedMonth, 1),
                                        \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth()
                                    ])->sum('amount'), 0, ',', ' ') }} Ar
                                </span>
                            </p>
                            <p class="text-sm text-gray-500">Reste :
                                <span class="font-semibold text-gray-800">
                                    {{ number_format($budget->amount - $budget->expenses->whereBetween('expense_date', [
                                        \Carbon\Carbon::create($selectedYear, $selectedMonth, 1),
                                        \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth()
                                    ])->sum('amount'), 0, ',', ' ') }} Ar
                                </span>
                            </p>
                        </div>
                        <div class="text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h11M9 21V3m0 0L5 7m4-4l4 4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">Aucun budget utilisé ce mois.</p>
            @endforelse
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-6 py-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Filtres et Recherche</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-6">
                <!-- Date de début  -->
                <div>
                    <label for="date_from" class="block text-base font-medium text-gray-800 mb-2">Du</label>
                    <input wire:model.live="dateFrom" 
                        type="date" 
                        id="date_from"
                        class="form-input w-full px-4 py-2 text-base border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Date de fin -->
                <div>
                    <label for="date_to" class="block text-base font-medium text-gray-800 mb-2">Au</label>
                    <input wire:model.live="dateTo" 
                        type="date" 
                        id="date_to"
                        class="form-input w-full px-4 py-2 text-base border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Mois (désactivé si plage de dates utilisée) -->
                <div>
                    <label for="month" class="block text-base font-medium text-gray-800 mb-2">Mois</label>
                    <select wire:model.live="selectedMonth" 
                            id="month" 
                            class="form-input w-full px-4 py-2 text-base border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            {{ (!empty($dateFrom) || !empty($dateTo)) ? 'disabled' : '' }}>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ Carbon\Carbon::create()->month($i)->locale('fr')->monthName }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Année (désactivée si plage de dates utilisée) -->
                <div>
                    <label for="year" class="block text-base font-medium text-gray-800 mb-2">Année</label>
                    <select wire:model.live="selectedYear" 
                            id="year" 
                            class="form-input w-full px-4 py-2 text-base border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            {{ (!empty($dateFrom) || !empty($dateTo)) ? 'disabled' : '' }}>
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Catégorie -->
                <div>
                    <label for="category" class="block text-base font-medium text-gray-800 mb-2">Catégorie</label>
                    <select wire:model.live="selectedCategory" id="category" class="form-input w-full px-4 py-2 text-base border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Recherche -->
                <div>
                    <label for="search" class="block text-base font-medium text-gray-800 mb-2">Recherche</label>
                    <input wire:model.live.debounce.500ms="searchTerm" type="text" id="search"
                        placeholder="Description, notes..."
                        class="form-input w-full px-4 py-2 text-base border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Indication du mode de filtre actif -->
            <div class="mt-4">
                @if(!empty($dateFrom) || !empty($dateTo))
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                        <p class="text-sm text-blue-800">
                            <strong>Filtre par dates :</strong>
                            @if($dateFrom && $dateTo)
                                Du {{ Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} au {{ Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}
                            @elseif($dateFrom)
                                À partir du {{ Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }}
                            @elseif($dateTo)
                                Jusqu'au {{ Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-md p-3">
                        <p class="text-sm text-gray-600">
                            <strong>Filtre par mois/année :</strong> {{ ucfirst($monthName) }} {{ $selectedYear }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Totaux par catégorie -->
    @if($categoryTotals->count() > 0)
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Répartition par catégorie</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($categoryTotals as $categoryTotal)
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">{{ $categoryTotal->category->name }}</span>
                        <span class="text-sm font-bold text-gray-900">{{ number_format($categoryTotal->total, 2) }}Ar</span>
                    </div>
                    <div class="mt-2 bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $monthlyTotal > 0 ? ($categoryTotal->total / $monthlyTotal) * 100 : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Messages de succès -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" 
             x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)">
            {{ session('success') }}
        </div>
    @endif

    <!-- Indicateur de chargement -->
    <div wire:loading wire:target="selectedMonth,selectedYear,selectedCategory,searchTerm" class="fixed top-4 right-4 z-50">
        <div class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center">
            <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Chargement...
        </div>
    </div>

    <!-- Liste des dépenses -->
    <div class="bg-white shadow overflow-hidden rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    Liste des dépenses ({{ $expenses->total() }} résultat{{ $expenses->total() > 1 ? 's' : '' }})
                </h3>
            </div>

            @if($expenses->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th wire:click="sortByColumn('expense_date')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                    Date
                                    @if($sortBy === 'expense_date')
                                        <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </th>
                                <th wire:click="sortByColumn('description')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                    Description
                                    @if($sortBy === 'description')
                                        <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </th>
                                <!-- NOUVEAU : Colonne catégorie maintenant triable -->
                                <th wire:click="sortByColumn('category_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                    Catégorie
                                    @if($sortBy === 'category_name')
                                        <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </th>
                                <th wire:click="sortByColumn('amount')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                    Montant
                                    @if($sortBy === 'amount')
                                        <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($expenses as $expense)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div>
                                        <div class="font-medium">{{ $expense->description }}</div>
                                        @if($expense->notes)
                                            <div class="text-gray-500 text-xs mt-1">{{ Str::limit($expense->notes, 50) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $expense->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ number_format($expense->amount, 2) }}Ar
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <!-- Bouton Modifier -->
                                        <button wire:click="editExpense({{ $expense->id }})" 
                                                class="text-blue-600 hover:text-blue-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        
                                        <!-- Bouton Supprimer -->
                                        <button wire:click="deleteExpense({{ $expense->id }})" 
                                                wire:confirm="Êtes-vous sûr de vouloir supprimer cette dépense ?"
                                                wire:loading.attr="disabled"
                                                wire:target="deleteExpense({{ $expense->id }})"
                                                class="text-red-600 hover:text-red-900 disabled:opacity-50">
                                            <svg wire:loading.remove wire:target="deleteExpense({{ $expense->id }})" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <svg wire:loading wire:target="deleteExpense({{ $expense->id }})" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $expenses->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune dépense trouvée</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Aucune dépense ne correspond aux critères sélectionnés.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('expenses.add') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Ajouter une dépense
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal d'édition -->
    @if($showEditModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 wire:click="closeEditModal"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="updateExpense">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                    Modifier la dépense
                                </h3>
                                
                                <div class="space-y-4">
                                    <!-- Description -->
                                    <div>
                                        <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                                        <input type="text" 
                                               wire:model="editForm.description" 
                                               id="edit_description"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('editForm.description') border-red-500 @enderror">
                                        @error('editForm.description')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Montant -->
                                    <div>
                                        <label for="edit_amount" class="block text-sm font-medium text-gray-700 mb-1">Montant (Ar) *</label>
                                        <input type="number" 
                                               step="0.01" 
                                               wire:model="editForm.amount" 
                                               id="edit_amount"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('editForm.amount') border-red-500 @enderror">
                                        @error('editForm.amount')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Date -->
                                    <div>
                                        <label for="edit_expense_date" class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                                        <input type="date" 
                                               wire:model="editForm.expense_date" 
                                               id="edit_expense_date"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('editForm.expense_date') border-red-500 @enderror">
                                        @error('editForm.expense_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Catégorie -->
                                    <div>
                                        <label for="edit_category" class="block text-sm font-medium text-gray-700 mb-1">Catégorie *</label>
                                        <select wire:model="editForm.category_id" 
                                                id="edit_category"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('editForm.category_id') border-red-500 @enderror">
                                            <option value="">Sélectionner une catégorie</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('editForm.category_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                     <!-- Budget lié -->
                                    <div>
                                        <label for="edit_budget" class="block text-sm font-medium text-gray-700 mb-1">Budget lié (optionnel)</label>
                                        <select wire:model="editForm.budget_id" 
                                                id="edit_budget"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('editForm.budget_id') border-red-500 @enderror">
                                            <option value="">Aucun budget</option>
                                            @foreach(App\Models\Budget::forUser(Auth::id())->orderBy('start_date', 'desc')->get() as $budget)
                                                <option value="{{ $budget->id }}">{{ $budget->name }} ({{ \Carbon\Carbon::parse($budget->start_date)->format('d/m/Y') }})</option>
                                            @endforeach
                                        </select>
                                        @error('editForm.budget_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <!-- Notes -->
                                    <div>
                                        <label for="edit_notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                        <textarea wire:model="editForm.notes" 
                                                  id="edit_notes" 
                                                  rows="3"
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('editForm.notes') border-red-500 @enderror"
                                                  placeholder="Notes supplémentaires (optionnel)"></textarea>
                                        @error('editForm.notes')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                wire:target="updateExpense"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                            <span wire:loading.remove wire:target="updateExpense">Mettre à jour</span>
                            <span wire:loading wire:target="updateExpense">Mise à jour...</span>
                        </button>
                        <button type="button" 
                                wire:click="closeEditModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>