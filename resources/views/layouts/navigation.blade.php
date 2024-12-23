<nav class="bg-black border-b border-gray-800 relative z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo et Navigation Desktop -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('all.media') }}" class="text-purple-500 font-bold text-2xl">
                        CINETECH
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-8 sm:flex sm:ml-10">
                    <x-nav-link :href="route('all.media')" :active="request()->routeIs('all.media')" class="text-purple-500 hover:text-purple-50" data-turbo-frame="content">
                        {{ __('Films & Séries') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-white" data-turbo-frame="content">
                        {{ __('Films') }}
                    </x-nav-link>
                    <x-nav-link :href="route('tv.index')" :active="request()->routeIs('tv.*')" class="text-white hover:text-white" data-turbo-frame="content">
                        {{ __('Séries') }}
                    </x-nav-link>
                    <x-nav-link :href="route('favorites.index')" :active="request()->routeIs('favorites.index')" class="text-white hover:text-white" data-turbo-frame="content">
                        {{ __('Favoris') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Barre de recherche (Desktop) -->
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-center px-4 max-w-xl">
                <div class="relative w-full">
                    <form action="{{ route('search') }}" method="GET" id="search-form">
                        <input type="text" 
                               id="search-input"
                               name="query"
                               class="w-full bg-gray-800 text-white rounded-lg pl-4 pr-10 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="Rechercher un film ou une série..."
                               autocomplete="off">
                        <div id="search-results" class="absolute z-50 w-full mt-1 bg-gray-800 rounded-lg shadow-lg hidden">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bouton Menu Mobile et User Menu -->
            <div class="flex items-center">
                <!-- User Menu (Desktop) -->
                <div class="sm:flex sm:items-center">
                    @auth
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-white hover:text-white focus:outline-none transition ease-in-out duration-150">
                                    <div class="flex items-center">
                                        <div>{{ Auth::user()->name }}</div>
                                        
                                    </div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')" class="text-gray-700">
                                    {{ __('Mon Profil') }}
                                </x-dropdown-link>
                               

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();" class="text-gray-700">
                                        {{ __('Déconnexion') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <div class="flex space-x-4">
                            <a href="{{ route('login') }}" class="text-white hover:text-white px-3 py-2 rounded-md text-sm">
                                {{ __('Connexion') }}
                            </a>
                            <a href="{{ route('register') }}" class="text-white hover:text-white px-3 py-2 rounded-md text-sm">
                                {{ __('Inscription') }}
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Navigation Mobile (Bottom) -->
<nav class="sm:hidden fixed bottom-0 left-0 right-0 bg-gray-800 border-t border-gray-800 z-50">
    <div class="grid grid-cols-5 h-16">
        <a href="{{ route('all.media') }}" class="flex flex-col items-center justify-center text-white hover:text-purple-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-xs mt-1">Accueil</span>
        </a>
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center text-white hover:text-purple-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            <span class="text-xs mt-1">Film</span>
        </a>
        <a href="{{ route('tv.index') }}" class="flex flex-col items-center justify-center text-white hover:text-purple-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span class="text-xs mt-1">Séries</span>
        </a>
        <a href="{{ route('favorites.index') }}" class="flex flex-col items-center justify-center text-white hover:text-purple-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span class="text-xs mt-1">Favoris</span>
        </a>
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center text-white hover:text-purple-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-xs mt-1">Profil</span>
        </a>
    </div>
</nav>

