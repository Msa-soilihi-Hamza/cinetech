@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8"
     x-data="{ 
        selectedGenre: '{{ request('genre') }}',
        async filterMovies(genreId) {
            this.selectedGenre = genreId;
            try {
                const response = await fetch(`/dashboard?genre=${genreId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const html = await response.text();
                document.querySelector('.movies-grid .grid').innerHTML = html;
                
                // Forcer une réinitialisation complète de AOS
                AOS.refreshHard();
                
                // Réinitialiser les attributs AOS sur les nouveaux éléments
                document.querySelectorAll('[data-aos]').forEach(el => {
                    el.setAttribute('data-aos-delay', '0');
                    el.removeAttribute('data-aos-animate');
                });
                
                // Réinitialiser AOS après un court délai pour s'assurer que le DOM est mis à jour
                setTimeout(() => {
                    AOS.refresh();
                }, 100);
                
                window.history.pushState({}, '', `/dashboard${genreId ? `?genre=${genreId}` : ''}`);
            } catch (error) {
                console.error('Erreur lors du filtrage:', error);
            }
        }
     }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-aos-wrapper animation="fade-down" duration="800">
            <div class="flex justify-center flex-wrap gap-2 mb-8">
                @foreach($genres as $genre)
                    <button 
                        @click="filterMovies('{{ $genre['id'] }}')"
                        :class="selectedGenre === '{{ $genre['id'] }}' ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-purple-600 hover:text-white'"
                        class="px-3 py-1 rounded-full text-sm">
                        {{ $genre['name'] }}
                    </button>
                @endforeach
                <button 
                    @click="filterMovies('')"
                    :class="!selectedGenre ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-purple-600 hover:text-white'"
                    class="px-3 py-1 rounded-full text-sm">
                    Tout voir
                </button>
            </div>
        </x-aos-wrapper>

        <x-aos-wrapper animation="fade-up" duration="800">
            <div class="movies-grid">
                <x-movies-grid :movies="$movies" />
            </div>
        </x-aos-wrapper>
    </div>
</div>
@endsection
