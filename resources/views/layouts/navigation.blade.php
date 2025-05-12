<nav class="bg-gray-900 border-b border-gray-800 relative z-50"
     x-data="{ 
        searchModalOpen: false,
        closeSearchModal() {
            this.searchModalOpen = false;
            document.body.style.overflow = 'auto';
        },
        openSearchModal() {
            this.searchModalOpen = true;
            document.body.style.overflow = 'hidden';
            this.$nextTick(() => {
                this.$refs.mobileSearchInput.focus();
            });
        }
     }">
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
                    <x-nav-link :href="route('film')" :active="request()->routeIs('film')" class="text-white hover:text-white" data-turbo-frame="content">
                        {{ __('Films') }}
                    </x-nav-link>
                    <x-nav-link :href="route('tv.index')" :active="request()->routeIs('tv.*')" class="text-white hover:text-white" data-turbo-frame="content">
                        {{ __('Séries') }}
                    </x-nav-link>
                    @auth
                    <x-nav-link :href="route('favorites.index')" :active="request()->routeIs('favorites.index')" class="text-white hover:text-white" data-turbo-frame="content">
                        {{ __('Favoris') }}
                    </x-nav-link>
                    @if(Auth::user()->isAdmin())
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-white hover:text-white" data-turbo-frame="content">
                        {{ __('Administration') }}
                    </x-nav-link>
                    @endif
                    @endauth
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

            <!-- Boutons Mobile (Recherche et Menu) -->
            <div class="flex items-center sm:hidden space-x-2">
                <button @click="openSearchModal" class="text-white p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center px-3 py-2 text-sm font-medium text-white hover:text-white">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="open" 
                         @click.away="open = false"
                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-gray-800 ring-1 ring-black ring-opacity-5"
                         style="display: none;">
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-white hover:bg-gray-700">
                                Mon Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-700">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endauth
            </div>

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
                    <div class="hidden sm:flex space-x-4">
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

    <!-- Modale de recherche Mobile -->
    <div x-show="searchModalOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-gray-900 sm:hidden"
         style="display: none;">
        <div class="flex flex-col h-full">
            <!-- En-tête de la modale -->
            <div class="flex items-center justify-between p-4 border-b border-gray-800">
                <h2 class="text-xl font-bold text-white">Rechercher</h2>
                <button @click="closeSearchModal" class="text-white p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Formulaire de recherche -->
            <div class="p-4">
                <div class="relative">
                    <input type="text" 
                           x-ref="mobileSearchInput"
                           id="mobile-search-input"
                           class="w-full bg-gray-800 text-white rounded-lg pl-4 pr-10 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="Rechercher un film ou une série..."
                           autocomplete="off">
                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Zone de résultats -->
            <div id="mobile-search-results" class="flex-1 overflow-y-auto p-4 bg-gray-900">
                <!-- Les résultats de recherche seront injectés ici -->
            </div>
        </div>
    </div>
</nav>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileSearchInput = document.getElementById('mobile-search-input');
    const mobileSearchResults = document.getElementById('mobile-search-results');
    let debounceTimer;

    if (mobileSearchInput && mobileSearchResults) {
        mobileSearchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = this.value.trim();

            debounceTimer = setTimeout(() => {
                if (query.length >= 2) {
                    fetch(`/search/autocomplete?query=${encodeURIComponent(query)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Données reçues de l\'API:', data);
                        let html = '';
                        if (data.length > 0) {
                            data.forEach(item => {
                                console.log('Item en cours:', item);
                                const image = item.poster_path;
                                const title = item.title || item.name;
                                const year = item.year || 'Date inconnue';
                                const type = item.media_type === 'movie' ? 'Film' : 'Série';
                                const rating = item.vote_average ? `${item.vote_average}/10` : 'Non noté';
                                
                                console.log('Données formatées:', {
                                    title,
                                    year,
                                    rating
                                });

                                html += `
                                    <a href="${item.media_type === 'movie' ? '/movie/' : '/tv/'}${item.id}" 
                                       class="flex items-start space-x-4 p-4 rounded-lg hover:bg-gray-800 transition-colors mb-4">
                                        <img src="${image}" 
                                             alt="${title}" 
                                             class="w-16 h-24 object-cover rounded-lg shadow-lg"
                                             onerror="this.src='/images/no-image.png'">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-white font-semibold text-lg truncate">${title}</h3>
                                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-800 text-purple-400">
                                                    ${type}
                                                </span>
                                                <span class="text-sm text-gray-400">${year}</span>
                                            </div>
                                            <p class="text-sm text-gray-400 mt-2 line-clamp-2">${item.overview || 'Aucune description disponible'}</p>
                                        </div>
                                    </a>
                                `;
                            });
                        } else {
                            html = `
                                <div class="text-center py-8">
                                    <p class="text-gray-400 text-lg">Aucun résultat trouvé pour "${query}"</p>
                                </div>
                            `;
                        }
                        mobileSearchResults.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        mobileSearchResults.innerHTML = `
                            <div class="text-center py-8">
                                <p class="text-red-500">Une erreur est survenue lors de la recherche</p>
                            </div>
                        `;
                    });
                } else {
                    mobileSearchResults.innerHTML = `
                        <div class="text-center py-8">
                            <p class="text-gray-400">Commencez à taper pour rechercher...</p>
                        </div>
                    `;
                }
            }, 300);
        });

        // Message initial
        mobileSearchResults.innerHTML = `
            <div class="text-center py-8">
                <p class="text-gray-400">Commencez à taper pour rechercher...</p>
            </div>
        `;
    }
});
</script>
@endpush

<!-- Navigation Mobile (Bottom) -->
<div class="sm:hidden block">
    <nav class="fixed inset-x-0 bottom-0 bg-gray-800 border-t border-gray-800 z-[9999]" style="height: 64px; min-height: 64px; transform: translateZ(0);">
        <div class="grid grid-cols-5 h-full">
            <a href="{{ route('all.media') }}" 
               class="flex flex-col items-center justify-center {{ request()->routeIs('all.media') ? 'text-purple-500' : 'text-white hover:text-purple-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-xs mt-1">Accueil</span>
            </a>
            <a href="{{ route('film') }}" 
               class="flex flex-col items-center justify-center {{ request()->routeIs('film') ? 'text-purple-500' : 'text-white hover:text-purple-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <span class="text-xs mt-1">Film</span>
            </a>
            <a href="{{ route('tv.index') }}" 
               class="flex flex-col items-center justify-center {{ request()->routeIs('tv.*') ? 'text-purple-500' : 'text-white hover:text-purple-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="text-xs mt-1">Séries</span>
            </a>
            @auth
            <a href="{{ route('favorites.index') }}" 
               class="flex flex-col items-center justify-center {{ request()->routeIs('favorites.*') ? 'text-purple-500' : 'text-white hover:text-purple-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span class="text-xs mt-1">Favoris</span>
            </a>
            <a href="{{ route('profile.edit') }}" 
               class="flex flex-col items-center justify-center {{ request()->routeIs('profile.*') ? 'text-purple-500' : 'text-white hover:text-purple-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs mt-1">Profil</span>
            </a>
            @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" 
               class="flex flex-col items-center justify-center {{ request()->routeIs('admin.*') ? 'text-purple-500' : 'text-white hover:text-purple-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-xs mt-1">Administration</span>
            </a>
            @endif
            @else
            <a href="{{ route('login') }}" 
               class="flex flex-col items-center justify-center text-white hover:text-purple-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                <span class="text-xs mt-1">Connexion</span>
            </a>
            <a href="{{ route('register') }}" 
               class="flex flex-col items-center justify-center text-white hover:text-purple-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                <span class="text-xs mt-1">Inscription</span>
            </a>
            @endauth
        </div>
    </nav>
</div>
