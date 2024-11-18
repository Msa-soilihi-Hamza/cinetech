@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-white">Mes Favoris</h1>
            @if(isset($totalFavorites) && $totalFavorites > 0)
                <span class="text-gray-400">{{ $totalFavorites }} {{ Str::plural('favori', $totalFavorites) }}</span>
            @endif
        </div>

        @if($favorites->isEmpty())
            <div class="bg-gray-800 rounded-lg p-8 text-center">
                <p class="text-gray-400 text-lg">Vous n'avez pas encore de favoris.</p>
                <a href="{{ route('dashboard') }}" class="mt-4 inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                    Découvrir des films et séries
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($favorites as $favorite)
                    <div id="favorite-{{ $favorite['tmdb_id'] }}" class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform hover:scale-105">
                        <a href="{{ route($favorite['type'] === 'Film' ? 'movies.show' : 'tv.show', $favorite['tmdb_id']) }}" class="block">
                            @if($favorite['poster_path'])
                                <img src="https://image.tmdb.org/t/p/w500{{ $favorite['poster_path'] }}"
                                     alt="{{ $favorite['title'] }}"
                                     class="w-full h-auto object-cover"
                                     loading="lazy">
                            @else
                                <div class="w-full h-64 bg-gray-700 flex items-center justify-center">
                                    <span class="text-gray-400">Image non disponible</span>
                                </div>
                            @endif
                        </a>
                        
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-bold text-white truncate" title="{{ $favorite['title'] }}">
                                    {{ $favorite['title'] }}
                                </h3>
                                <form action="{{ route('favorites.remove') }}" 
                                      method="POST" 
                                      class="favorite-form"
                                      data-id="{{ $favorite['tmdb_id'] }}"
                                      onsubmit="removeFavorite(event, {{ $favorite['tmdb_id'] }})">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="tmdb_id" value="{{ $favorite['tmdb_id'] }}">
                                    <input type="hidden" name="type" value="{{ $favorite['type'] === 'Film' ? 'movie' : 'tv' }}">
                                    <button type="submit" 
                                            class="text-red-500 hover:text-red-700 transition-colors"
                                            title="Retirer des favoris">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-purple-500 font-bold">{{ $favorite['vote_average'] }}/10</span>
                                <span class="text-gray-400">{{ $favorite['type'] }}</span>
                            </div>
                            @if($favorite['release_date'])
                                <div class="text-gray-400 text-sm mt-2">
                                    {{ \Carbon\Carbon::parse($favorite['release_date'])->format('Y') }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection 

@push('scripts')
<script>
function removeFavorite(event, tmdbId) {
    event.preventDefault();
    
    const form = event.target;
    const card = document.getElementById(`favorite-${tmdbId}`);
    
    fetch(form.action, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            tmdb_id: tmdbId,
            type: form.querySelector('input[name="type"]').value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Anime la suppression de la carte
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => {
                card.remove();
                
                // Met à jour le compteur de favoris
                const countElement = document.querySelector('.text-gray-400');
                if (countElement) {
                    const currentCount = parseInt(countElement.textContent);
                    if (currentCount > 1) {
                        countElement.textContent = `${currentCount - 1} favoris`;
                    } else {
                        location.reload(); // Recharge si c'était le dernier favori
                    }
                }
            }, 300);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de la suppression du favori.');
    });
}
</script>

<style>
.bg-gray-800 {
    transition: opacity 0.3s ease, transform 0.3s ease;
}
</style>
@endpush 