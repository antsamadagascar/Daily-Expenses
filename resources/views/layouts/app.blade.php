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
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-xl font-bold text-gray-900">Gestion Dépenses</h1>
                        </div>
                        <!-- Menu principal -->
                        <div class="hidden md:ml-10 md:flex md:space-x-8">
                            <a href="{{ route('dashboard') }}"
                               class="text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-600' : '' }}">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0"></path>
                                </svg>
                                Tableau de bord
                            </a>
                            <a href="{{ route('budgets.index') }}"
                            class="flex items-center px-4 py-2 rounded-md text-sm font-medium transition
                                    {{ request()->routeIs('budgets.index') ? 'bg-blue-100 text-blue-600' : 'text-gray-900 hover:text-blue-600 hover:bg-gray-100' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v2m14 0H3m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2h14z"/>
                                </svg>
                                Budget
                            </a>
                            <a href="{{ route('expenses.view') }}"
                               class="text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('expenses.view') ? 'bg-blue-100 text-blue-600' : '' }}">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Mes dépenses
                            </a>
                            <a href="{{ route('expenses.add') }}"
                               class="text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('expenses.add') ? 'bg-blue-100 text-blue-600' : '' }}">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Ajouter dépense
                            </a>
                            <a href="{{ route('system.reset-data') }}"
                               class="text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('reports') ? 'bg-blue-100 text-blue-600' : '' }}">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Data Reset
                            </a>
                            <!-- <a href="#"
                               class="text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('reports') ? 'bg-blue-100 text-blue-600' : '' }}">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Rapports
                            </a> -->
                        </div>
                    </div>
                    <!-- Menu utilisateur -->
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700 text-sm">
                            Bonjour, {{ Auth::user()->name }}
                        </span>
                        <livewire:auth.logout />
                    </div>
                </div>
            </div>
            
            <!-- Menu mobile -->
            <div class="md:hidden">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-gray-50">
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium">
                        Tableau de bord
                    </a>
                    <a href="{{ route('expenses.view') }}" class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium">
                        Mes dépenses
                    </a>
                    <a href="{{ route('expenses.add') }}" class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium">
                        Ajouter dépense
                    </a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium">
                        Rapports
                    </a>
                </div>
            </div>
        </nav>

        <!-- Contenu principal -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>
    @livewireScripts
</body>
</html>