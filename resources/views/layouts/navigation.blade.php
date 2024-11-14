<nav class="bg-black border-b border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-red-600 font-bold text-2xl">
                        CINETECH
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                <x-nav-link :href="route('all.media')" :active="request()->routeIs('all.media')" class="text-gray-300 hover:text-white">
                        {{ __('Films & Séries') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-300 hover:text-white">
                        {{ __('Films') }}
                    </x-nav-link>
                    <x-nav-link :href="route('tv.index')" :active="request()->routeIs('tv.*')" class="text-gray-300 hover:text-white">
                        {{ __('Séries') }}
                    </x-nav-link>
                   
                    <x-nav-link href="#" class="text-gray-300 hover:text-white">
                        {{ __('Favoris') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Barre de recherche -->
            <div class="flex-1 flex items-center justify-center px-4 max-w-xl mx-auto">
                <div class="relative w-full">
                    <form action="{{ route('search') }}" method="GET" id="search-form">
                        <input type="text" 
                               id="search-input"
                               name="query"
                               class="w-full bg-gray-800 text-white rounded-lg pl-4 pr-10 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Rechercher un film ou une série..."
                               autocomplete="off">
                        <div id="search-results" class="absolute z-50 w-full mt-1 bg-gray-800 rounded-lg shadow-lg hidden">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <!-- Utilisateur connecté -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-300 hover:text-white focus:outline-none transition ease-in-out duration-150">
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
                            <x-dropdown-link href="#" class="text-gray-700">
                                {{ __('Mes Favoris') }}
                            </x-dropdown-link>
                            <x-dropdown-link href="#" class="text-gray-700">
                                {{ __('Historique') }}
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
                    <!-- Utilisateur non connecté -->
                    <div class="flex space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm">
                            {{ __('Connexion') }}
                        </a>
                        <a href="{{ route('register') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm">
                            {{ __('Inscription') }}
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
