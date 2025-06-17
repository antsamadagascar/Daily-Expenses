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
    <!-- En-tête -->
    <div class="header">
        <h1>Rapport des Dépenses</h1>
        <p>{{ ucfirst($monthName) }} {{ $selectedYear }}</p>
        <p>Généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</p>
    </div>

    <!-- Résumé -->
    <div class="summary">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">Total des dépenses</div>
                <div class="value">{{ number_format($total, 2) }}Ar</div>
            </div>
            <div class="summary-item">
                <div class="label">Nombre de dépenses</div>
                <div class="value">{{ $expenses->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Répartition par catégorie -->
    @if($categoryTotals->count() > 0)
    <div class="categories-section">
        <h2>Répartition par catégorie</h2>
        <div class="categories-grid">
            @foreach($categoryTotals as $categoryTotal)
            <div class="category-item">
                <div class="category-name">{{ $categoryTotal->category->name }}</div>
                <div class="category-amount">{{ number_format($categoryTotal->total, 2) }}Ar</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Liste détaillée des dépenses -->
    <div class="expenses-section">
        <h2>Détail des dépenses</h2>
        
        @if($expenses->count() > 0)
        <table class="expenses-table">
            <thead>
                <tr>
                    <th class="date-col">Date</th>
                    <th class="desc-col">Description</th>
                    <th class="category-col">Catégorie</th>
                    <th class="amount-col">Montant</th>
                    <th class="notes-col">Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $index => $expense)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</td>
                    <td>{{ $expense->description }}</td>
                    <td>
                        <span class="category-badge">{{ $expense->category->name }}</span>
                    </td>
                    <td class="amount">{{ number_format($expense->amount, 2) }}Ar</td>
                    <td>{{ $expense->notes ? Str::limit($expense->notes, 30) : '-' }}</td>
                </tr>
                @if(($index + 1) % 25 == 0 && $index + 1 < $expenses->count())
                </tbody>
            </table>
            <div class="page-break"></div>
            <table class="expenses-table">
                <thead>
                    <tr>
                        <th class="date-col">Date</th>
                        <th class="desc-col">Description</th>
                        <th class="category-col">Catégorie</th>
                        <th class="amount-col">Montant</th>
                        <th class="notes-col">Notes</th>
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

    <!-- Pied de page -->
    <div class="footer">
        <p>Rapport généré automatiquement par l'application de Gestion des Dépenses</p>
        <p>{{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>