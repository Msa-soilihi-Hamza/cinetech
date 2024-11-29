@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            {{-- Image de fond --}}
            <div class="relative h-100">
                @if(isset($movie['backdrop_path']) && $movie['backdrop_path'])
                    <img src="https://image.tmdb.org/t/p/original{{ $movie['backdrop_path'] }}"
                         alt="{{ $movie['title'] ?? '' }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent"></div>
                @endif
            </div>

            {{-- Contenu --}}
            <div class="relative -mt-32 px-6 md:px-12 pb-12">
                <div class="flex flex-col md:flex-row gap-8">
                    {{-- Affiche --}}
                    <div class="flex-shrink-0 w-full md:w-1/3">
                        @if(isset($movie['poster_path']))
                            <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                                 alt="{{ $movie['title'] }}"
                                 class="w-full rounded-lg shadow-xl">
                        @endif
                    </div>

                    {{-- Informations --}}
                    <div class="flex-grow text-white">
                        <h1 class="text-4xl font-bold mb-4">{{ $movie['title'] }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-4 mb-6">
                            <span class="text-purple-500 font-bold text-xl">
                                {{ number_format($movie['vote_average'], 1) }}/10
                            </span>
                            @if(isset($movie['release_date']))
                                <span class="text-gray-400">
                                    {{ \Carbon\Carbon::parse($movie['release_date'])->format('d/m/Y') }}
                                </span>
                            @endif
                            @if(isset($movie['genres']))
                                <div class="flex flex-wrap gap-2">
                                    @foreach($movie['genres'] as $genre)
                                        <span class="px-3 py-1 bg-gray-700 rounded-full text-sm">
                                            {{ $genre['name'] }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @if(isset($movie['overview']))
                            <div class="mb-8">
                                <h2 class="text-xl font-semibold mb-2">Synopsis</h2>
                                <p class="text-gray-300 leading-relaxed">{{ $movie['overview'] }}</p>
                            </div>
                        @endif

                        @if(isset($movie['credits']['cast']))
                            <div class="cast-section mb-8">
                                <h2 class="text-xl font-semibold mb-4">Distribution principale</h2>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach(array_slice($movie['credits']['cast'], 0, 8) as $actor)
                                        <div class="bg-gray-700  rounded-lg text-center hover:bg-gray-600 transition-colors cursor-pointer"
                                             onclick="showActorFilmography({{ $actor['id'] }}, '{{ $actor['name'] }}')">
                                            <div class="relative w-32 h-32 mx-auto mb-3">
                                                @if(isset($actor['profile_path']))
                                                    <img src="https://image.tmdb.org/t/p/w185{{ $actor['profile_path'] }}"
                                                         alt="{{ $actor['name'] }}"
                                                         class="w-full h-full  object-cover object-center shadow-lg">
                                                @else
                                                    <div class="w-full h-full rounded-full bg-gray-800 flex items-center justify-center">
                                                        <span class="text-gray-400 text-3xl">?</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <p class="font-semibold truncate">{{ $actor['name'] }}</p>
                                            <p class="text-sm text-gray-400 truncate">{{ $actor['character'] }}</p>
                                           
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if(isset($movie['credits']['crew']))
                                    <div class="mt-8">
                                        <h2 class="text-xl font-semibold mb-4">Équipe technique principale</h2>
                                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                            @foreach(array_slice($movie['credits']['crew'], 0, 4) as $crewMember)
                                                <div class="bg-gray-700 p-4 rounded-lg">
                                                    <p class="font-semibold">{{ $crewMember['name'] }}</p>
                                                    <p class="text-sm text-purple-400">{{ $crewMember['job'] }}</p>
                                                    <p class="text-xs text-gray-400">{{ $crewMember['department'] }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                @if(count($movie['credits']['cast']) > 8)
                                    <div class="mt-4 text-center">
                                        <button onclick="showAllCast()" class="text-purple-500 hover:text-purple-400 transition-colors">
                                            Voir tous les acteurs ({{ count($movie['credits']['cast']) }})
                                        </button>
                                    </div>
                                    
                                    <!-- Modal pour tous les acteurs -->
                                    <div id="castModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-40">
                                        <div class="container mx-auto h-full overflow-y-auto py-8">
                                            <div class="bg-gray-800 p-6 rounded-lg max-w-4xl mx-auto">
                                                <div class="flex justify-between items-center mb-6">
                                                    <h3 class="text-2xl font-bold">Distribution complète</h3>
                                                    <button onclick="hideCastModal()" class="text-gray-400 hover:text-white">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                                    @foreach($movie['credits']['cast'] as $actor)
                                                        <div class="bg-gray-700 p-4 rounded-lg hover:bg-gray-600 transition-colors cursor-pointer"
                                                             onclick="showActorFilmography({{ $actor['id'] }}, '{{ $actor['name'] }}')">
                                                            <div class="relative w-28 h-28 mx-auto mb-3">
                                                                @if(isset($actor['profile_path']))
                                                                    <img src="https://image.tmdb.org/t/p/w185{{ $actor['profile_path'] }}"
                                                                         alt="{{ $actor['name'] }}"
                                                                         class="w-full h-full rounded-full object-cover object-center shadow-lg">
                                                                @else
                                                                    <div class="w-full h-full rounded-full bg-gray-800 flex items-center justify-center">
                                                                        <span class="text-gray-400 text-3xl">?</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <p class="font-semibold truncate">{{ $actor['name'] }}</p>
                                                            <p class="text-sm text-gray-400 truncate">{{ $actor['character'] }}</p>
                                                            @if(isset($actor['known_for_department']))
                                                                <p class="text-xs text-purple-400 mt-1">{{ $actor['known_for_department'] }}</p>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Filmographie -->
        <div id="filmographyModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 overflow-y-auto">
            <div class="min-h-screen px-4 text-center">
                <!-- Overlay -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="hideFilmographyModal()">
                    <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                </div>

                <!-- Centrage vertical -->
                <span class="inline-block h-screen align-middle" aria-hidden="true">&#8203;</span>

                <!-- Contenu du modal -->
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

        {{-- Section commentaires --}}
        <div class="mt-8 bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="container mx-auto">
                <h2 class="text-2xl font-bold text-white mb-6">Commentaires</h2>

                {{-- Formulaire d'ajout de commentaire --}}
                @auth
                    <form action="{{ route('comments.store') }}" method="POST" class="mb-8">
                        @csrf
                        <input type="hidden" name="media_type" value="movie">
                        <input type="hidden" name="media_id" value="{{ $movie['id'] }}">
                        
                        <div class="mb-4">
                            <textarea 
                                name="content" 
                                rows="3" 
                                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500"
                                placeholder="Ajouter un commentaire..."></textarea>
                        </div>
                        
                        <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            Publier
                        </button>
                    </form>
                @else
                    <div class="text-center mb-8">
                        <p class="text-gray-300">
                            <a href="{{ route('login') }}" class="text-purple-500 hover:underline">Connectez-vous</a> 
                            pour laisser un commentaire
                        </p>
                    </div>
                @endauth

                {{-- Messages de succès/erreur --}}
                @if(session('success'))
                    <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Liste des commentaires --}}
                <div class="space-y-4">
                    @if($comments->isNotEmpty())
                        @foreach($comments as $comment)
                            <div class="bg-gray-700 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-bold text-white">{{ $comment->user->name }}</h4>
                                        <p class="text-sm text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                                    </div>
                                    
                                    @if(Auth::id() === $comment->user_id)
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-600">
                                                Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                
                                <p class="mt-2 text-gray-300">{{ $comment->content }}</p>

                                @auth
                                    <button onclick="toggleReplyForm({{ $comment->id }})" 
                                            class="text-purple-500 hover:text-purple-600 text-sm mt-3 flex items-center gap-2 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                        </svg>
                                        Répondre
                                    </button>

                                    <div id="reply-form-{{ $comment->id }}" class="mt-3 hidden">
                                        <form action="{{ route('comments.reply', $comment) }}" method="POST" 
                                              class="bg-gray-750 p-4 rounded-lg border border-gray-600">
                                            @csrf
                                            <textarea 
                                                name="content" 
                                                rows="2" 
                                                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500"
                                                placeholder="Votre réponse..."
                                                required></textarea>
                                            <div class="mt-3 flex gap-2">
                                                <button type="submit" 
                                                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                                    </svg>
                                                    Envoyer
                                                </button>
                                                <button type="button" 
                                                        onclick="toggleReplyForm({{ $comment->id }})" 
                                                        class="px-4 py-2 text-gray-400 hover:text-white border border-gray-600 rounded-lg hover:bg-gray-700 transition-colors">
                                                    Annuler
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endauth

                                @if($comment->replies->count() > 0)
                                    <div class="ml-8 mt-4 space-y-3 border-l-2 border-purple-500 pl-4">
                                        @foreach($comment->replies as $reply)
                                            <div class="bg-gray-800 rounded-lg p-4 shadow-lg transform hover:scale-[1.02] transition-transform">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                        </svg>
                                                        
                                                        <div>
                                                            <h5 class="font-bold text-white flex items-center gap-2">
                                                                {{ $reply->user->name }}
                                                                <span class="text-xs text-purple-400 font-normal">Réponse</span>
                                                            </h5>
                                                            <p class="text-xs text-gray-400">{{ $reply->created_at->diffForHumans() }}</p>
                                                        </div>
                                                    </div>
                                                    
                                                    @if(Auth::id() === $reply->user_id)
                                                        <form action="{{ route('comments.destroy', $reply) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="text-red-500 hover:text-red-600 text-sm bg-gray-700 px-2 py-1 rounded-lg transition-colors">
                                                                Supprimer
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                                
                                                <div class="mt-2 text-gray-300 bg-gray-750 p-3 rounded-lg">
                                                    {{ $reply->content }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-center text-gray-400">Aucun commentaire pour le moment. Soyez le premier à commenter !</p>
                    @endif
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
        console.log('Fetching filmography for actor:', actorId);
        const response = await fetch(`/api/actor/${actorId}/movies`);
        const data = await response.json();
        
        console.log('API Response:', data);
        
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
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Fermer le modal en cliquant sur l'overlay
    document.querySelector('#filmographyModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideFilmographyModal();
        }
    });
});
</script>
@endsection 