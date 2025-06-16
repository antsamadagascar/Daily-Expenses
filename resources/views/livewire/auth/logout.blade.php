<button 
    wire:click="logout"
    wire:loading.attr="disabled"
    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50"
>
    <span wire:loading.remove>
        Déconnexion
    </span>
    <span wire:loading>
        Déconnexion...
    </span>
</button>