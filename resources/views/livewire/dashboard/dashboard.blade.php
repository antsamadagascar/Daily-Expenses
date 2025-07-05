<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestion des Dépenses</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .metric-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .expense-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .budget-card {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .trend-card {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        .chart-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- En-tête du tableau de bord -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Tableau de bord</h1>
                    <p class="text-gray-600 mt-2">Aperçu de vos dépenses et budgets</p>
                </div>
                <div class="flex space-x-2 mt-4 sm:mt-0">
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Exporter
                    </button>
                    <!-- <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                        Filtrer
                    </button> -->
                </div>
            </div>
        </div>

        <!-- Cartes de métriques principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Dépenses du mois -->
            <div class="metric-card rounded-xl p-6 text-white card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Dépenses ce mois</p>
                        <p class="text-2xl font-bold">{{ number_format($currentMonthExpenses, 2) }} Ar</p>
                        <div class="flex items-center mt-2">
                            @if($previousMonthComparison['trend'] === 'up')
                                <svg class="w-4 h-4 text-red-300 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                                <span class="text-red-300 text-sm">+{{ number_format(abs($previousMonthComparison['percentage']), 1) }}%</span>
                            @else
                                <svg class="w-4 h-4 text-green-300 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                                <span class="text-green-300 text-sm">-{{ number_format(abs($previousMonthComparison['percentage']), 1) }}%</span>
                            @endif
                        </div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Nombre de dépenses -->
            <div class="expense-card rounded-xl p-6 text-white card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-pink-100 text-sm font-medium">Nombre de dépenses</p>
                        <p class="text-2xl font-bold">{{ $currentMonthCount }}</p>
                        <p class="text-pink-100 text-sm mt-2">Ce mois</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Moyenne journalière -->
            <div class="budget-card rounded-xl p-6 text-white card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Moyenne journalière</p>
                        <p class="text-2xl font-bold">{{ number_format($dailyAverage, 2) }} Ar</p>
                        <p class="text-blue-100 text-sm mt-2">Depuis le début du mois</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Budget restant -->
            <div class="trend-card rounded-xl p-6 text-white card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Budget restant</p>
                        @php
                            $totalBudget = $budgetProgress->sum('budgeted');
                            $totalSpent = $budgetProgress->sum('spent');
                            $remaining = $totalBudget - $totalSpent;
                        @endphp
                        <p class="text-2xl font-bold">{{ number_format($remaining, 2) }} Ar</p>
                        <p class="text-green-100 text-sm mt-2">{{ $budgetProgress->count() }} budget(s) actif(s)</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>


        <div class="mb-8 p-6 bg-white rounded-xl shadow-sm max-w-md">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Comparaison Annuelle</h3>
                <p><strong>Année en cours :</strong> {{ number_format($yearlyComparison['current_year'], 2, ',', ' ') }} Ar</p>
                <p><strong>Année précédente :</strong> {{ number_format($yearlyComparison['previous_year'], 2, ',', ' ') }} Ar</p>
                <p><strong>Différence :</strong> {{ number_format($yearlyComparison['difference'], 2, ',', ' ') }} Ar</p>
                <p>
                    <strong>Variation en % :</strong>
                    @php
                        $percentage = $yearlyComparison['percentage'];
                        $absPrevYear = abs($yearlyComparison['previous_year']);
                    @endphp

                    @if($absPrevYear < 1000)
                        <span class="text-red-500 font-semibold">Variation trop élevée ou non significative</span>
                    @else
                        <span class="{{ $percentage >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                            {{ number_format($percentage, 2, ',', ' ') }}%
                        </span>
                    @endif
                </p>

                @if($absPrevYear < 1000)
                    <p class="mt-4 text-sm text-gray-600 italic">
                        Note : La variation en pourcentage est élevée car le total de l’année précédente est très faible, ce qui amplifie le pourcentage.  
                        Il est conseillé de considérer surtout la différence absolue pour une meilleure compréhension de l’évolution.
                    </p>
                @endif
        </div>

        <!-- Graphiques et analyses -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Évolution mensuelle -->
            <div class="chart-container p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Évolution mensuelle</h3>
                    <div class="flex space-x-2">
                        <button class="text-sm px-3 py-1 bg-blue-100 text-blue-700 rounded-md">12 mois</button>
                    </div>
                </div>
               <div class="chart-container" style="height: 300px;">
                    <canvas id="monthlyChart"></canvas>
                </div>

            </div>

            <!-- Distribution par catégorie -->
            <div class="chart-container p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Répartition par catégorie</h3>
                <div class="chart-container" style="height: 300px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tendance hebdomadaire et progression des budgets -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Tendance hebdomadaire -->
            <div class="lg:col-span-2 chart-container p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tendance hebdomadaire</h3>
                <div class="chart-container" style="height: 300px;">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>

            <!-- Progression des budgets -->
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Progression des budgets</h3>
                <div class="space-y-4">
                    @foreach($budgetProgress as $budget)
                    <div class="p-4 border rounded-lg {{ $budget['status'] === 'exceeded' ? 'border-red-200 bg-red-50' : ($budget['status'] === 'warning' ? 'border-yellow-200 bg-yellow-50' : 'border-green-200 bg-green-50') }}">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium text-gray-900">{{ $budget['name'] }}</span>
                            <span class="text-sm text-gray-600">{{ number_format($budget['percentage'], 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $budget['status'] === 'exceeded' ? 'bg-red-500' : ($budget['status'] === 'warning' ? 'bg-yellow-500' : 'bg-green-500') }}" 
                                 style="width: {{ min($budget['percentage'], 100) }}%"></div>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600 mt-1">
                            <span>{{ number_format($budget['spent'], 2) }} Ar</span>
                            <span>{{ number_format($budget['budgeted'], 2) }} Ar</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Dépenses récentes et calendrier -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Dépenses récentes -->
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Dépenses récentes</h3>
                    <button class="text-sm text-blue-600 hover:text-blue-800">Voir tout</button>
                </div>
                <div class="space-y-3">
                    @foreach($recentExpenses as $expense)
                    <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center" 
                                 style="background-color: {{ $expense->category->color ?? '#6B7280' }}20;">
                                <svg class="w-5 h-5" style="color: {{ $expense->category->color ?? '#6B7280' }}" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $expense->description }}</p>
                                <p class="text-sm text-gray-500">{{ $expense->category->name ?? 'Sans catégorie' }} • {{ $expense->expense_date->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">{{ number_format($expense->amount, 2) }} Ar</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Calendrier des dépenses -->
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Calendrier des dépenses</h3>
                <div class="grid grid-cols-7 gap-1 text-center text-sm">
                    <!-- En-têtes des jours -->
                    <div class="p-2 font-medium text-gray-500">Dim</div>
                    <div class="p-2 font-medium text-gray-500">Lun</div>
                    <div class="p-2 font-medium text-gray-500">Mar</div>
                    <div class="p-2 font-medium text-gray-500">Mer</div>
                    <div class="p-2 font-medium text-gray-500">Jeu</div>
                    <div class="p-2 font-medium text-gray-500">Ven</div>
                    <div class="p-2 font-medium text-gray-500">Sam</div>
                    
                    <!-- Jours du mois -->
                    @foreach($expensesByDay as $day)
                    <div class="p-2 rounded-lg relative {{ $day['is_today'] ? 'bg-blue-100 text-blue-800' : 'hover:bg-gray-50' }} 
                                {{ $day['total'] > 0 ? 'font-semibold' : '' }}">
                        <div>{{ $day['day'] }}</div>
                        @if($day['total'] > 0)
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions rapides</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                 <a href="{{ route('budgets.index') }}"class="flex items-center justify-center px-6 py-4 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Créer un budget
                 </a>
                <a href="{{ route('expenses.view') }}" class="flex items-center justify-center px-6 py-4 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Voir mes dépenses
                </a>
                <a href="{{ route('expenses.add') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ajouter une dépense
                </a>
            </div>
        </div>
    </div>

    <script>
    (() => {
        Chart.defaults.font.family = 'system-ui, -apple-system, sans-serif';
        Chart.defaults.color = '#6B7280';

        const monthlyData = @json($monthlyEvolution);
        const categoryData = @json($categoryDistribution);
        const weeklyData = @json($weeklyTrend);

        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: monthlyData.map(item => item.month),
                datasets: [{
                    label: 'Dépenses mensuelles',
                    data: monthlyData.map(item => item.total),
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: context => context.parsed.y.toLocaleString() + ' Ar'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { maxRotation: 45 }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            callback: value => value.toLocaleString() + ' Ar'
                        }
                    }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });

        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        categoryData.forEach(item => item.total = Number(item.total || 0));
        const totalCategoryAmount = categoryData.reduce((sum, item) => sum + item.total, 0);
        
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: categoryData.map(item => item.category),
                datasets: [{
                    data: categoryData.map(item => item.total),
                    backgroundColor: categoryData.map(item => item.color),
                    borderWidth: 0,
                    hoverBorderWidth: 2,
                    hoverBorderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            generateLabels: chart => {
                                const data = chart.data;
                                return data.labels.map((label, index) => {
                                    const value = data.datasets[0].data[index];
                                    const percentage = totalCategoryAmount > 0
                                        ? ((value / totalCategoryAmount) * 100).toFixed(1)
                                        : '0.0';

                                    return {
                                        text: `${label} (${percentage}%)`,
                                        fillStyle: data.datasets[0].backgroundColor[index],
                                        hidden: false,
                                        index
                                    };
                                });
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: context => {
                                const percentage = ((context.parsed / totalCategoryAmount) * 100).toFixed(1);
                                return `${context.label}: ${context.parsed.toLocaleString()} Ar (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
        new Chart(weeklyCtx, {
            type: 'bar',
            data: {
                labels: weeklyData.map(item => item.week),
                datasets: [{
                    label: 'Dépenses hebdomadaires',
                    data: weeklyData.map(item => item.total),
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: '#10B981',
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: context => context.parsed.y.toLocaleString() + ' Ar'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { maxRotation: 45, font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            callback: value => value.toLocaleString() + ' Ar'
                        }
                    }
                }
            }
        });

        function animateCards() {
            const cards = document.querySelectorAll('.card-hover');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            animateCards();
        });

        function exportData(format) {
            const data = {
                monthly: monthlyData,
                categories: categoryData,
                weekly: weeklyData,
                format
            };
            const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `dashboard-export-${new Date().toISOString().split('T')[0]}.${format}`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        document.addEventListener('click', e => {
            if (e.target.textContent.trim() === 'Exporter') {
                e.preventDefault();
                const format = prompt('Format d\'export (json, csv, xlsx):', 'json');
                if (format) exportData(format);
            }
            if (e.target.classList.contains('period-btn')) {
                e.preventDefault();
                Livewire.emit('changePeriod', e.target.dataset.period);
            }
        });

        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300 ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                type === 'warning' ? 'bg-yellow-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => document.body.removeChild(toast), 300);
            }, 3000);
        }
    })();
</script>
</body>
</html>