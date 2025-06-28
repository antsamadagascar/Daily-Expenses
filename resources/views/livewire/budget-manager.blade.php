<div class="space-y-6">
    <!-- En-tête avec boutons d'action -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Gestion des Budgets</h2>
            <p class="text-gray-600">Gérez vos budgets et suivez vos dépenses</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="openModal" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nouveau Budget
            </button>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Recherche -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search"
                       placeholder="Rechercher un budget..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Filtre statut -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select wire:model.live="filterActive" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">Tous</option>
                    <option value="active">Actifs</option>
                    <option value="inactive">Inactifs</option>
                </select>
            </div>

            <!-- Filtre catégorie -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                <select wire:model.live="filterCategory" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Messages flash -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Liste des budgets -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($budgets as $budget)
            <div class="bg-white rounded-lg shadow p-6 border {{ $budget->is_over_budget ? 'border-red-300' : 'border-gray-200' }}">
                <!-- En-tête du budget -->
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $budget->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $budget->category->name }}</p>
                        @if($budget->description)
                            <p class="text-sm text-gray-500 mt-1">{{ $budget->description }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- Statut actif/inactif -->
                        <button wire:click="toggleActive({{ $budget->id }})" 
                                class="text-sm {{ $budget->is_active ? 'text-green-600 bg-green-100' : 'text-gray-600 bg-gray-100' }} px-2 py-1 rounded-full">
                            {{ $budget->is_active ? 'Actif' : 'Inactif' }}
                        </button>
                        
                        <!-- Menu actions -->
                        <div class="relative">
                            <button class="text-gray-500 hover:text-gray-700">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Informations du budget -->
                <div class="space-y-3">
                    <!-- Montants -->
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Budget:</span>
                        <span class="font-medium">{{ number_format($budget->amount, 2) }} €</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Dépensé:</span>
                        <span class="font-medium {{ $budget->is_over_budget ? 'text-red-600' : '' }}">
                            {{ number_format($budget->spent_amount, 2) }} €
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Restant:</span>
                        <span class="font-medium {{ $budget->remaining_amount < 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($budget->remaining_amount, 2) }} €
                        </span>
                    </div>

                    <!-- Barre de progression -->
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $budget->is_over_budget ? 'bg-red-600' : 'bg-blue-600' }}" 
                             style="width: {{ min(100, $budget->progress_percentage) }}%"></div>
                    </div>
                    <div class="text-xs text-gray-500 text-center">
                        {{ number_format($budget->progress_percentage, 1) }}% utilisé
                    </div>

                    <!-- Période -->
                    <div class="text-xs text-gray-500 border-t pt-3">
                        <div class="flex justify-between">
                            <span>Du {{ $budget->start_date->format('d/m/Y') }}</span>
                            <span>Au {{ $budget->end_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="text-center mt-1">
                            Période: {{ ucfirst($budget->period) }}
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-2 mt-4 pt-4 border-t">
                    <button wire:click="edit({{ $budget->id }})" 
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Modifier
                    </button>
                    <button wire:click="confirmDelete({{ $budget->id }})" 
                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                        Supprimer
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-2 text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun budget</h3>
                <p class="mt-1 text-sm text-gray-500">Commencez par créer votre premier budget.</p>
                <div class="mt-6">
                    <button wire:click="openModal" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                        Créer un budget
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $budgets->links() }}
    </div>

    <!-- Modal de création/édition -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <!-- En-tête du modal -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $isEditing ? 'Modifier le budget' : 'Nouveau budget' }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Formulaire -->
                    <form wire:submit="save" class="space-y-4">
                        <!-- Nom -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom du budget *</label>
                            <input type="text" 
                                   wire:model="name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                   placeholder="Ex: Budget Alimentation Janvier">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea wire:model="description"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                      placeholder="Description optionnelle du budget"></textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Montant et Catégorie -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Montant (€) *</label>
                                <input type="number" 
                                       wire:model="amount"
                                       step="0.01"
                                       min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('amount') border-red-500 @enderror"
                                       placeholder="0.00">
                                @error('amount')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie *</label>
                                <select wire:model="category_id" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 @enderror">
                                    <option value="">Sélectionner une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date de début *</label>
                                <input type="date" 
                                       wire:model="start_date"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('start_date') border-red-500 @enderror">
                                @error('start_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin *</label>
                                <input type="date" 
                                       wire:model="end_date"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_date') border-red-500 @enderror">
                                @error('end_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Période et Statut -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                                <select wire:model="period" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('period') border-red-500 @enderror">
                                    <option value="monthly">Mensuel</option>
                                    <option value="weekly">Hebdomadaire</option>
                                    <option value="yearly">Annuel</option>
                                    <option value="custom">Personnalisé</option>
                                </select>
                                @error('period')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-center">
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           wire:model="is_active"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Budget actif</span>
                                </label>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex justify-between pt-4">
                            <div>
                                @if(!$isEditing)
                                    <button type="button" 
                                            wire:click="createMultipleBudgets"
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                        Créer 12 budgets mensuels
                                    </button>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <button type="button" 
                                        wire:click="closeModal"
                                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                                    Annuler
                                </button>
                                <button type="submit" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    {{ $isEditing ? 'Mettre à jour' : 'Créer' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de confirmation de suppression -->
    @if($showDeleteConfirm)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Supprimer le budget</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Êtes-vous sûr de vouloir supprimer ce budget ? Cette action est irréversible.
                        </p>
                    </div>
                    <div class="flex gap-2 justify-center mt-4">
                        <button wire:click="$set('showDeleteConfirm', false)" 
                                class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium">
                            Annuler
                        </button>
                        <button wire:click="delete" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                            Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>