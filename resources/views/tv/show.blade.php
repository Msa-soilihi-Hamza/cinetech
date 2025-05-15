@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            {{-- Image de fond --}}
            <div class="relative h-100">
                @if(isset($tvShow->backdrop_path) && $tvShow->backdrop_path)
                    <img src="https://image.tmdb.org/t/p/original{{ $tvShow->backdrop_path }}"
                         alt="{{ $tvShow->name ?? '' }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent"></div>
                @endif
            </div>

            {{-- Contenu --}}
            <div class="relative -mt-32 px-6 md:px-12 pb-12">
                <div class="flex flex-col md:flex-row gap-8">
                    {{-- Affiche --}}
                    <div class="flex-shrink-0 w-full md:w-1/3">
                        @if(isset($tvShow->poster_path))
                            <img src="https://image.tmdb.org/t/p/w500{{ $tvShow->poster_path }}"
                                 alt="{{ $tvShow->name }}"
                                 class="w-full rounded-lg shadow-xl">
                        @endif
                    </div>

                    {{-- Informations --}}
                    <div class="flex-grow text-white">
                        <h1 class="text-4xl font-bold mb-4">{{ $tvShow->name }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-4 mb-6">
                            <span class="text-purple-500 font-bold text-xl">
                                {{ number_format($tvShow->vote_average, 1) }}/10
                            </span>
                            @if(isset($tvShow->first_air_date))
                                <span class="text-gray-400">
                                    {{ \Carbon\Carbon::parse($tvShow->first_air_date)->format('d/m/Y') }}
                                </span>
                            @endif
                            @if(isset($tvShow->genres))
                                <div class="flex flex-wrap gap-2">
                                    @foreach($tvShow->genres as $genre)
                                        <span class="px-3 py-1 bg-gray-700 rounded-full text-sm">
                                            {{ $genre['name'] }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-4 mb-6">
                            <x-tv-favorite-button :id="$tvShow->id" />
                        </div>

                        @if(isset($tvShow->overview))
                            <p class="text-gray-300 leading-relaxed mb-8">{{ $tvShow->overview }}</p>
                        @endif

                        @if(isset($credits) && isset($credits['cast']))
                            <div class="cast-section mb-8">
                                <h2 class="text-xl font-semibold mb-4">Distribution principale</h2>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach(array_slice($credits['cast'], 0, 8) as $actor)
                                        <div class="group">
                                            @if(isset($actor['profile_path']))
                                                <img src="https://image.tmdb.org/t/p/w300{{ $actor['profile_path'] }}"
                                                     alt="{{ $actor['name'] }}"
                                                     class="w-full rounded-lg shadow-lg transition-transform duration-200 group-hover:scale-105">
                                            @else
                                                <div class="w-full h-48 bg-gray-700 rounded-lg flex items-center justify-center">
                                                    <span class="text-gray-400">Aucune image</span>
                                                </div>
                                            @endif
                                            <div class="mt-2 text-center">
                                                <h3 class="font-semibold text-gray-200">{{ $actor['name'] }}</h3>
                                                <p class="text-sm text-gray-400">{{ $actor['character'] ?? 'Non spécifié' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(isset($credits) && isset($credits['crew']))
                            <div class="crew-section mb-8">
                                <h2 class="text-xl font-semibold mb-4">Équipe technique</h2>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach(array_slice($credits['crew'], 0, 8) as $crewMember)
                                        <div class="group">
                                            @if(isset($crewMember['profile_path']))
                                                <img src="https://image.tmdb.org/t/p/w300{{ $crewMember['profile_path'] }}"
                                                     alt="{{ $crewMember['name'] }}"
                                                     class="w-full rounded-lg shadow-lg transition-transform duration-200 group-hover:scale-105">
                                            @else
                                                <div class="w-full h-48 bg-gray-700 rounded-lg flex items-center justify-center">
                                                    <span class="text-gray-400">Aucune image</span>
                                                </div>
                                            @endif
                                            <div class="mt-2 text-center">
                                                <h3 class="font-semibold text-gray-200">{{ $crewMember['name'] }}</h3>
                                                <p class="text-sm text-gray-400">{{ $crewMember['job'] ?? 'Non spécifié' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @php
                            // Filtrer pour obtenir toutes les bandes-annonces YouTube
                            $trailers = collect($tvShow->videos->results ?? [])->filter(function($video) {
                                return $video['type'] === 'Trailer' && $video['site'] === 'YouTube';
                            })->take(4);
                        @endphp

                        <div class="mt-8">
                            <h2 class="text-2xl font-semibold text-gray-200 mb-4">Bandes Annonces</h2>
                            @if(isset($trailers) && !empty($trailers))
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($trailers as $trailer)
                                        <div class="relative group">
                                            <img src="https://img.youtube.com/vi/{{ $trailer['key'] }}/hqdefault.jpg"
                                                 alt="{{ $trailer['name'] }}"
                                                 class="w-full rounded-lg shadow-lg">
                                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                <a href="https://www.youtube.com/watch?v={{ $trailer['key'] }}"
                                                   target="_blank"
                                                   class="text-white text-xl">
                                                    <i class="fas fa-play-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-400">Aucune bande-annonce disponible.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Équipe technique --}}
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-white mb-6">Équipe technique</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if(isset($credits['crew']))
                    @foreach(array_slice($credits['crew'], 0, 8) as $crewMember)
                        <div class="bg-gray-800 rounded-lg p-4">
                            <h3 class="font-semibold text-white mb-2">{{ $crewMember['name'] }}</h3>
                            <p class="text-gray-400 mb-2">{{ $crewMember['job'] ?? 'Non spécifié' }}</p>
                            @if(isset($crewMember['profile_path']))
                                <img src="https://image.tmdb.org/t/p/w185{{ $crewMember['profile_path'] }}"
                                     alt="{{ $crewMember['name'] }}"
                                     class="w-24 h-24 rounded-full object-cover mt-2">
                            @else
                                <div class="w-24 h-24 rounded-full bg-gray-700 flex items-center justify-center mt-2">
                                    <span class="text-gray-400">Aucune image</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-400">Aucune information sur l'équipe technique disponible.</p>
                @endif
            </div>
        </div>
        
        {{-- Section des commentaires --}}
        <x-comments :mediaType="$mediaType" :mediaId="$mediaId" :comments="$comments" />
    </div>
</div>

<!-- Modal Filmographie -->
<div id="filmographyModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 overflow-y-auto">
    <div class="min-h-screen px-4 text-center">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="hideFilmographyModal()">
            <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
        </div>

        <span class="inline-block h-screen align-middle" aria-hidden="true">&#8203;</span>

        <div class="inline-block w-full max-w-6xl p-6 my-8 text-left align-middle transition-all transform bg-gray-800 shadow-xl rounded-lg">
            <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
                <h3 class="text-2xl font-bold text-white" id="actorName"></h3>
                <button onclick="hideFilmographyModal()" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div id="filmographyContent" class="mt-4">
                <div class="flex items-center justify-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-500"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleReplyForm(commentId) {
    const form = document.getElementById(`reply-form-${commentId}`);
    form.classList.toggle('hidden');
}

function showAllCast() {
    document.getElementById('castModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function hideCastModal() {
    document.getElementById('castModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

async function showActorFilmography(actorId, actorName) {
    const modal = document.getElementById('filmographyModal');
    const nameElement = document.getElementById('actorName');
    const content = document.getElementById('filmographyContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    nameElement.textContent = `Filmographie de ${actorName}`;
    content.innerHTML = `
        <div class="flex items-center justify-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-500"></div>
        </div>
    `;
    
    try {
        const response = await fetch(`/api/actor/${actorId}/movies`);
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.error || `Erreur ${response.status}`);
        }
        
        if (!data.cast || data.cast.length === 0) {
            content.innerHTML = '<p class="text-center text-gray-400">Aucun film trouvé pour cet acteur.</p>';
            return;
        }
        
        let html = '<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">';
        
        data.cast
            .sort((a, b) => new Date(b.release_date || '1900') - new Date(a.release_date || '1900'))
            .forEach(movie => {
                html += `
                    <div onclick="window.location.href='/movie/${movie.id}'" 
                         class="bg-gray-700 rounded-lg overflow-hidden shadow-md transform hover:scale-105 transition-all duration-200 hover:shadow-purple-500/20 cursor-pointer">
                        <div class="relative aspect-[2/3] group">
                            ${movie.poster_path 
                                ? `<img src="https://image.tmdb.org/t/p/w342${movie.poster_path}" 
                                       alt="${movie.title}" 
                                       class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-110">`
                                : `<div class="w-full h-full bg-gray-800 flex items-center justify-center">
                                       <span class="text-gray-600 text-2xl">?</span>
                                   </div>`
                            }
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-end">
                                <div class="p-2 text-white">
                                    <p class="text-xs font-medium">${movie.character || 'Rôle non spécifié'}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2">
                            <h4 class="font-semibold text-sm text-white mb-1 line-clamp-1">${movie.title}</h4>
                            <div class="flex items-center justify-between text-xs">
                                <p class="text-gray-400">${movie.release_date ? new Date(movie.release_date).getFullYear() : 'Date inconnue'}</p>
                                ${movie.vote_average 
                                    ? `<div class="flex items-center bg-gray-800 px-1.5 py-0.5 rounded-full">
                                           <span class="text-yellow-400 mr-0.5">★</span>
                                           <span class="text-gray-300">${movie.vote_average.toFixed(1)}</span>
                                       </div>`
                                    : ''
                                }
                            </div>
                        </div>
                    </div>
                `;
            });
        
        html += '</div>';
        content.innerHTML = html;
        
    } catch (error) {
        console.error('Error:', error);
        content.innerHTML = `
            <div class="text-center py-8">
                <p class="text-red-500 mb-4">${error.message || 'Une erreur est survenue lors du chargement de la filmographie.'}</p>
                <button onclick="showActorFilmography(${actorId}, '${actorName}')" 
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    Réessayer
                </button>
            </div>
        `;
    }
}

function hideFilmographyModal() {
    const modal = document.getElementById('filmographyModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Fermer le modal en appuyant sur Echap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideFilmographyModal();
        hideCastModal();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Fermer les modals en cliquant sur l'overlay
    document.querySelector('#filmographyModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideFilmographyModal();
        }
    });
    
    document.querySelector('#castModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            hideCastModal();
        }
    });
});
</script>
@endsection
