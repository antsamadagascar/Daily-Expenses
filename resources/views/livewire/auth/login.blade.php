<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Gestion des Dépenses
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Connectez-vous à votre compte
            </p>
        </div>
        
        <form wire:submit="authenticate" class="mt-8 space-y-6">
            <div class="space-y-4">
                <div>
                    <label for="login" class="block text-sm font-medium text-gray-700">
                        Email ou nom d'utilisateur
                    </label>
                    <input 
                        wire:model="login" 
                        id="login" 
                        type="text" 
                        required 
                        class="mt-1 appearance-none relative block w-full px-3 py-2 border @error('login') border-red-300 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                        placeholder="Email ou nom d'utilisateur"
                    >
                    @error('login')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Mot de passe
                    </label>
                    <input 
                        wire:model="password" 
                        id="password" 
                        type="password" 
                        required 
                        class="mt-1 appearance-none relative block w-full px-3 py-2 border @error('password') border-red-300 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                        placeholder="Mot de passe"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input 
                        wire:model="remember" 
                        id="remember" 
                        type="checkbox" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    >
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                        Se souvenir de moi
                    </label>
                </div>
            </div>

            <div>
                <button 
                    type="submit" 
                    wire:loading.attr="disabled"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                >
                    <span wire:loading.remove>
                        Se connecter
                    </span>
                    <span wire:loading>
                        Connexion en cours...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>