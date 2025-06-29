<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Gestion Dépenses') }}</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Livewire -->
    @livewireStyles
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg border-b border-gray-200" x-data="{ mobileMenuOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo et menu principal -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gradient-to-r from-primary-600 to-primary-700 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <h1 class="text-xl font-bold text-gray-900">Gestion Dépenses</h1>
                        </div>
                        
                        <!-- Menu principal - Desktop -->
                        <div class="hidden md:ml-10 md:flex md:space-x-1">
                            <!-- Section Navigation -->
                            <div class="flex items-center space-x-1">
                                <a href="{{ route('dashboard') }}"
                                   class="group flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-primary-100 text-primary-700 shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-primary-600' }}">
                                    <svg class="w-4 h-4 mr-2 {{ request()->routeIs('dashboard') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0"></path>
                                    </svg>
                                    Tableau de bord
                                </a>
                                
                                <a href="{{ route('budgets.index') }}"
                                   class="group flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('budgets.index') ? 'bg-primary-100 text-primary-700 shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-primary-600' }}">
                                    <svg class="w-4 h-4 mr-2 {{ request()->routeIs('budgets.index') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v2m14 0H3m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2h14z"/>
                                    </svg>
                                    Budget
                                </a>
                            </div>

                            <!-- Séparateur -->
                            <div class="w-px h-8 bg-gray-300 mx-2"></div>

                            <!-- Section Dépenses -->
                            <div class="flex items-center space-x-1">
                                <a href="{{ route('expenses.view') }}"
                                   class="group flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('expenses.view') ? 'bg-primary-100 text-primary-700 shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-primary-600' }}">
                                    <svg class="w-4 h-4 mr-2 {{ request()->routeIs('expenses.view') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Mes dépenses
                                </a>
                                
                                <a href="{{ route('expenses.add') }}"
                                   class="group flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('expenses.add') ? 'bg-green-100 text-green-700 shadow-sm' : 'text-gray-700 hover:bg-green-50 hover:text-green-600' }}">
                                    <svg class="w-4 h-4 mr-2 {{ request()->routeIs('expenses.add') ? 'text-green-600' : 'text-gray-400 group-hover:text-green-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Ajouter dépense
                                </a>
                            </div>

                            <!-- Séparateur -->
                            <div class="w-px h-8 bg-gray-300 mx-2"></div>

                            <!-- Section Administration -->
                            <div class="flex items-center space-x-1">
                                <a href="{{ route('system.reset-data') }}"
                                   class="group flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('system.reset-data') ? 'bg-red-100 text-red-700 shadow-sm' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">
                                    <svg class="w-4 h-4 mr-2 {{ request()->routeIs('system.reset-data') ? 'text-red-600' : 'text-gray-400 group-hover:text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Reset Data
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Menu utilisateur et bouton mobile -->
                    <div class="flex items-center space-x-4">
                        <!-- Info utilisateur - Desktop -->
                        <div class="hidden md:flex items-center space-x-3">
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ now()->format('d/m/Y') }}</p>
                            </div>
                            <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                <span class="text-primary-600 font-medium text-sm">
                                    {{ substr(Auth::user()->name, 0, 2) }}
                                </span>
                            </div>
                            <livewire:auth.logout />
                        </div>

                        <!-- Bouton menu mobile -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen" 
                                class="md:hidden p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors">
                            <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Menu mobile -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="md:hidden bg-white border-t border-gray-200 shadow-lg"
                 style="display: none;">
                <div class="px-4 py-3 space-y-1">
                    <!-- Section Navigation Mobile -->
                    <div class="pb-3">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Navigation</p>
                        <a href="{{ route('dashboard') }}" 
                           class="group flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary-100 text-primary-700' : 'text-gray-700 hover:bg-gray-100 hover:text-primary-600' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0"></path>
                            </svg>
                            Tableau de bord
                        </a>
                        <a href="{{ route('budgets.index') }}" 
                           class="group flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('budgets.index') ? 'bg-primary-100 text-primary-700' : 'text-gray-700 hover:bg-gray-100 hover:text-primary-600' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('budgets.index') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v2m14 0H3m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2h14z"/>
                            </svg>
                            Budget
                        </a>
                    </div>

                    <!-- Section Dépenses Mobile -->
                    <div class="pb-3 border-t border-gray-200 pt-3">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Dépenses</p>
                        <a href="{{ route('expenses.view') }}" 
                           class="group flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('expenses.view') ? 'bg-primary-100 text-primary-700' : 'text-gray-700 hover:bg-gray-100 hover:text-primary-600' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('expenses.view') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Mes dépenses
                        </a>
                        <a href="{{ route('expenses.add') }}" 
                           class="group flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('expenses.add') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-green-50 hover:text-green-600' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('expenses.add') ? 'text-green-600' : 'text-gray-400 group-hover:text-green-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Ajouter dépense
                        </a>
                    </div>

                    <!-- Section Administration Mobile -->
                    <div class="pb-3 border-t border-gray-200 pt-3">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Administration</p>
                        <a href="{{ route('system.reset-data') }}" 
                           class="group flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('system.reset-data') ? 'bg-red-100 text-red-700' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('system.reset-data') ? 'text-red-600' : 'text-gray-400 group-hover:text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset Data
                        </a>
                    </div>

                    <!-- Info utilisateur Mobile -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                    <span class="text-primary-600 font-medium text-sm">
                                        {{ substr(Auth::user()->name, 0, 2) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ now()->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <livewire:auth.logout />
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Contenu principal -->
        <main class="flex-1 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>