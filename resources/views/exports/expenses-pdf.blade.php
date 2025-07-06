<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des Dépenses - {{ ucfirst($monthName) }} {{ $selectedYear }}</title>
   <style>
    body {
        font-family: "Helvetica", "Arial", sans-serif;
        font-size: 11px;
        line-height: 1.5;
        color: #000;
        margin: 0;
        padding: 20px;
        background: #fff;
    }

    .header {
        text-align: center;
        margin-bottom: 25px;
        border-bottom: 1px solid #aaa;
        padding-bottom: 10px;
    }

    .header h1 {
        margin: 0;
        font-size: 20px;
        font-weight: bold;
    }

    .header p {
        margin: 4px 0 0;
        font-size: 12px;
        color: #555;
    }

    .summary {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 20px;
    }

    .summary-grid {
        display: table;
        width: 100%;
        table-layout: fixed;
    }

    .summary-item {
        display: table-cell;
        text-align: center;
        padding: 5px;
    }

    .summary-item .label {
        font-weight: bold;
        font-size: 12px;
        margin-bottom: 3px;
        color: #333;
    }

    .summary-item .value {
        font-size: 14px;
        font-weight: bold;
    }

    .categories-section {
        margin-bottom: 20px;
    }

    .categories-section h2 {
        font-size: 14px;
        border-bottom: 1px solid #bbb;
        padding-bottom: 4px;
        margin-bottom: 10px;
    }

    .categories-grid {
        display: table;
        width: 100%;
    }

    .category-item {
        display: table-row;
    }

    .category-name,
    .category-amount {
        display: table-cell;
        padding: 4px 0;
        font-size: 12px;
    }

    .category-name {
        font-weight: bold;
    }

    .category-amount {
        text-align: right;
    }

    .expenses-section h2 {
        font-size: 14px;
        margin-bottom: 10px;
        border-bottom: 1px solid #bbb;
        padding-bottom: 4px;
    }

    .expenses-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .expenses-table th,
    .expenses-table td {
        padding: 6px;
        font-size: 11px;
        border: 1px solid #ccc;
    }

    .expenses-table th {
        background: #eee;
        font-weight: bold;
        text-align: left;
    }

    .expenses-table td.amount {
        text-align: right;
        font-weight: bold;
    }

    .footer {
        margin-top: 30px;
        text-align: center;
        font-size: 10px;
        color: #666;
        border-top: 1px solid #bbb;
        padding-top: 10px;
    }

    .page-break {
        page-break-after: always;
    }
</style>

</head>
<body>
    <div class="header">
        <h1>Rapport des Dépenses</h1>
        <p>{{ ucfirst($monthName) }} {{ $selectedYear }}</p>
        <p>Généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-grid">
            @php
                $isFirstMonth = $monthStart->eq(\Carbon\Carbon::parse($budgets->first()->start_date)->startOfMonth());
                $budgetTotal = collect($budgetSummaries)->sum('budgeted');
                $resteAvant = $isFirstMonth
                    ? $budgetTotal
                    : collect($budgetSummaries)->sum(fn($b) => $b['budgeted'] - $b['spent_before']);
            @endphp

            <div class="summary-item">
                <div class="label">Budget total de départ</div>
                <div class="value">{{ number_format($budgetTotal, 2, ',', ' ') }} Ar</div>
            </div>

            <div class="summary-item">
                <div class="label">
                    @if($isFirstMonth)
                        Reste avant {{ ucfirst($monthName) }} {{ $selectedYear }} (premier mois)
                    @else
                        Reste budgétaire avant mois {{ ucfirst($monthName) }} {{ $selectedYear }}
                    @endif
                </div>
                <div class="value">{{ number_format($resteAvant, 2, ',', ' ') }} Ar</div>
            </div>

            <div class="summary-item">
                <div class="label">Reste budgétaire actuel</div>
                <div class="value">{{ number_format($totalRemaining, 2, ',', ' ') }} Ar</div>
            </div>

        </div>
    </div>

    @if($budgetSummaries && count($budgetSummaries))
        @php
            $totalAllocated = 0;
            $totalUsed = 0;
            $totalRemaining = 0;
        @endphp

        <h2 style="margin-top: 40px; font-size: 18px; font-weight: bold; color: #000; border-bottom: 2px solid #000; padding-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
            Suivi des Budgets
        </h2>

        <table class="budget-table" style="width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; border: 1px solid #000;">
            <thead>
                <tr style="background-color: whitesmoke;">
                    <th style="padding: 12px 10px; text-align: left;">Budget Utilisé</th>
                    <th style="padding: 12px 10px; text-align: right;">Montant Alloué</th>
                    <th style="padding: 12px 10px; text-align: right;">Total Utilisé</th>
                    <th style="padding: 12px 10 px; text-align: right;">Utilisé ce mois</th>
                    <th style="padding: 12px 10px; text-align: right;">Reste</th>
                    <th style="padding: 12px 10px; text-align: right;">Devise</th>
                    <th style="padding: 12px 10px; text-align: center;">Période</th>
                </tr>
            </thead>
            <tbody>
                @foreach($budgetSummaries as $b)
                    @php
                        $used = $b['spent_before'] + $b['spent_this_month'];
                        $totalAllocated += $b['budgeted'];
                        $totalUsed += $used;
                        $totalRemaining += $b['remaining_now'];
                    @endphp
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 10px; max-width: 250px; word-break: break-word;">
                            <strong>{{ $b['budget_name'] }}</strong><br>
                            <span style="color: #666; font-size: 11px; display: block; margin-top: 4px;">
                                {{ Str::limit($b['description'], 200, '...') }}
                            </span>
                        </td>
                        <td style="padding: 10px; text-align: right;">{{ number_format($b['budgeted'], 0, ',', ' ') }}</td>
                        <td style="padding: 10px; text-align: right;">{{ number_format($used, 0, ',', ' ') }}</td>
                        <td style="padding: 10px; text-align: right;">{{ number_format($b['remaining_now'], 0, ',', ' ') }} </td>
                        <td style="padding: 10px; text-align: right; color: {{ $b['remaining'] < 0 ? '#cc0000' : '#000' }};">
                            {{ number_format($b['remaining'], 0, ',', ' ') }} 
                        </td>
                        <td style="padding: 10px; text-align: right;">Ariary</td>
                        <td style="padding: 10px; text-align: center;">
                            {{ \Carbon\Carbon::parse($b['start_date'])->format('d/m/Y') }}<br>
                            <span style="color: #666;">au</span><br>
                            {{ \Carbon\Carbon::parse($b['end_date'])->format('d/m/Y') }}
                        </td>
                    </tr>
                @endforeach

                <tr style="background-color: #f5f5f5; border-top: 3px solid #000;">
                    <td style="padding: 12px 10px; font-weight: bold;">TOTAL GÉNÉRAL</td>
                    <td style="padding: 12px 10px; text-align: right;">{{ number_format($totalAllocated, 0, ',', ' ') }} </td>
                    <td style="padding: 12px 10px; text-align: right;">{{ number_format($totalUsed, 0, ',', ' ') }} </td>
                    <td style="padding: 12px 10px; text-align: right;">{{ number_format($totalRemaining, 0, ',', ' ') }} </td>
                    <td style="padding: 12px 10px; text-align: center;">—</td>
                    <td style="padding: 12px 10px; text-align: center;">—</td>
                    <td style="padding: 12px 10px; text-align: center;">—</td>
                </tr>
            </tbody>
        </table>
    @endif

    @if($categoryTotals->count() > 0)
        <div class="categories-section">
            <h2>Répartition par catégorie</h2>
            <div class="categories-grid">
                @foreach($categoryTotals as $categoryTotal)
                    <div class="category-item">
                        <div class="category-name">{{ $categoryTotal->category->name }}</div>
                        <div class="category-amount">{{ number_format($categoryTotal->total, 2) }} Ar</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="expenses-section">
        <h2>Détail des dépenses</h2>

        @if($expenses->count() > 0)
            <table class="expenses-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Catégorie</th>
                        <th>Montant</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $index => $expense)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</td>
                            <td>{{ $expense->description }}</td>
                            <td>{{ $expense->category->name }}</td>
                            <td>{{ number_format($expense->amount, 2) }} Ar</td>
                            <td>{{ $expense->notes ? strip_tags($expense->notes) : '-' }}</td>
                        </tr>
                        @if(($index + 1) % 25 == 0 && $index + 1 < $expenses->count())
                </tbody>
            </table>
            <div class="page-break"></div>
            <table class="expenses-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Catégorie</th>
                        <th>Montant</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #6B7280; font-style: italic;">
                Aucune dépense trouvée pour cette période.
            </p>
        @endif
    </div>

    <div class="footer">
        <p>Rapport généré automatiquement par l'application de Gestion des Dépenses</p>
        <p>@Aina Ny Antsa</p>
        <p>{{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>

</html>
