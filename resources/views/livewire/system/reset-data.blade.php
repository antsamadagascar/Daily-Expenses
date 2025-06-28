<div class="max-w-3xl mx-auto p-6">
    <div class="bg-white shadow rounded-lg p-6 space-y-6">
        <div class="flex items-center space-x-3">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 8v4m0 4h.01M4.93 4.93l14.14 14.14M12 2a10 10 0 100 20 10 10 0 000-20z" />
            </svg>
            <h2 class="text-xl font-semibold text-gray-800">Réinitialisation des données</h2>
        </div>

        <p class="text-gray-600">
            Cette action supprimera <span class="font-semibold text-red-600">toutes les données des budgets et dépenses</span>.
            Elle est irréversible. Assurez-vous d’avoir une sauvegarde si nécessaire.
        </p>

        @if (session()->has('message'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md">
                {{ session('message') }}
            </div>
        @endif

        <div class="flex justify-end">
            <button wire:click="resetData"
                    onclick="return confirm('Confirmez-vous la suppression de toutes les données ?')"
                    class="inline-flex items-center px-5 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                Réinitialiser les données
            </button>
        </div>
    </div>
</div>
