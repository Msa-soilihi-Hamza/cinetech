<nav class="bg-black border-b border-gray-800 relative z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo et Navigation Desktop -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-purple-500 font-bold text-2xl">
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
                <div class="hidden sm:flex sm:items-center">
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

                <!-- Bouton Menu Mobile -->
                <div class="block desktop:hidden">
                    <button type="button" 
                            onclick="toggleMobileMenu()"
                            class="mobile:inline-flex desktop:hidden items-center justify-center p-2 rounded-md text-white hover:text-white focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" 
                                  stroke-linejoin="round" 
                                  stroke-width="2" 
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menu Mobile -->
        <div class="hidden mobile:block desktop:hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('all.media') }}" 
                   class="text-white hover:text-purple-500 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('all.media') ? 'bg-purple-600' : '' }}"
                   data-turbo-frame="content">
                    {{ __('Films & Séries') }}
                </a>
                <a href="{{ route('dashboard') }}" 
                   class="text-white hover:text-purple-500 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('dashboard') ? 'bg-purple-600' : '' }}"
                   data-turbo-frame="content">
                    {{ __('Films') }}
                </a>
                <a href="{{ route('tv.index') }}" 
                   class="text-white hover:text-purple-500 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('tv.*') ? 'bg-purple-600' : '' }}"
                   data-turbo-frame="content">
                    {{ __('Séries') }}
                </a>
                <a href="{{ route('favorites.index') }}" 
                   class="text-white hover:text-purple-500 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('favorites.index') ? 'bg-purple-600' : '' }}"
                   data-turbo-frame="content">
                    {{ __('Favoris') }}
                </a>

                @auth
                    <a href="{{ route('profile.edit') }}" 
                       class="text-white hover:text-purple-500 block px-3 py-2 rounded-md text-base font-medium">
                        {{ __('Mon Profil') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="text-white hover:text-purple-500 block w-full text-left px-3 py-2 rounded-md text-base font-medium">
                            {{ __('Déconnexion') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" 
                       class="text-white hover:text-purple-500 block px-3 py-2 rounded-md text-base font-medium">
                        {{ __('Connexion') }}
                    </a>
                    <a href="{{ route('register') }}" 
                       class="text-white hover:text-purple-500 block px-3 py-2 rounded-md text-base font-medium">
                        {{ __('Inscription') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
<!-- Script pour le menu mobile -->
<script>
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenu.classList.toggle('hidden');
}

// Cacher le menu mobile quand l'écran dépasse 950px
window.addEventListener('resize', function() {
    const mobileMenu = document.getElementById('mobile-menu');
    if (window.innerWidth >= 950) {
        mobileMenu.classList.add('hidden');
    }
});
</script>

