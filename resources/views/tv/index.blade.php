@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8"
     x-data="{ 
        selectedGenre: '{{ request('genre') }}',
        async filterShows(genreId) {
            this.selectedGenre = genreId;
            try {
                const response = await fetch(`/tv?genre=${genreId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const html = await response.text();
                document.querySelector('.shows-grid').innerHTML = html;
                
                // Mettre à jour l'URL sans recharger la page
                window.history.pushState({}, '', `/tv${genreId ? `?genre=${genreId}` : ''}`);
            } catch (error) {
                console.error('Erreur lors du filtrage:', error);
            }
        }
     }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filtres par genre -->
        <div class="flex justify-center flex-wrap gap-2 mb-8">
            @foreach($genres as $genre)
                <button 
                    @click="filterShows('{{ $genre['id'] }}')"
                    :class="selectedGenre === '{{ $genre['id'] }}' ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-purple-600 hover:text-white'"
                    class="px-3 py-1 rounded-full text-sm">
                    {{ $genre['name'] }}
                </button>
            @endforeach
            <button 
                @click="filterShows('')"
                :class="!selectedGenre ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-purple-600 hover:text-white'"
                class="px-3 py-1 rounded-full text-sm">
                Tout voir
            </button>
        </div>

        <!-- Grille de séries -->
        <div class="shows-grid">
            <x-shows-grid :shows="$shows" />
        </div>
    </div>
</div>
@endsection 