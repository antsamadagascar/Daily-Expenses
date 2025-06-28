<div class="max-w-xl mx-auto py-10">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Réinitialiser les données</h2>

    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('message') }}
        </div>
    @endif

    <button wire:click="reset"
            onclick="return confirm('Êtes-vous sûr de vouloir supprimer toutes les données de dépenses et budgets ?')"
            class="px-4 py-2 bg-red-600 text-white rounded shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
        Réinitialiser les données
    </button>
</div>
