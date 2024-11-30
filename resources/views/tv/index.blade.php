@extends('layouts.app')

@section('content')
<style>
    .animated-bg {
        background: linear-gradient(180deg, #111827, #111827, #132241);
        background-size: 100% 600%;

        -webkit-animation: AnimationName 10s ease infinite;
        -moz-animation: AnimationName 10s ease infinite;
        -o-animation: AnimationName 10s ease infinite;
        animation: AnimationName 10s ease infinite;
    }

    @-webkit-keyframes AnimationName {
        0%{background-position:50% 0%}
        50%{background-position:50% 100%}
        100%{background-position:50% 0%}
    }
    @-moz-keyframes AnimationName {
        0%{background-position:50% 0%}
        50%{background-position:50% 100%}
        100%{background-position:50% 0%}
    }
    @-o-keyframes AnimationName {
        0%{background-position:50% 0%}
        50%{background-position:50% 100%}
        100%{background-position:50% 0%}
    }
    @keyframes AnimationName {
        0%{background-position:50% 0%}
        50%{background-position:50% 100%}
        100%{background-position:50% 0%}
    }
</style>

<div class="min-h-screen animated-bg py-8"
     x-data="{ 
        selectedGenre: '{{ request('genre') }}',
        async filterShows(genre) {
            this.selectedGenre = genre;
            try {
                const response = await fetch(`/tv?genre=${genre}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const html = await response.text();
                document.querySelector('.shows-grid').innerHTML = html;
                
                window.history.pushState({}, '', `/tv${genre ? `?genre=${genre}` : ''}`);
                
                AOS.refreshHard();
                setTimeout(() => {
                    AOS.refresh();
                }, 100);
            } catch (error) {
                console.error('Erreur lors du filtrage:', error);
            }
        }
     }"
     x-init="AOS.init({
        once: false,
        mirror: true,
        offset: 50
     })">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-aos-wrapper animation="fade-down" duration="800">
            <div class="flex justify-center flex-wrap gap-2 mb-8">
                <button 
                    @click="filterShows('')"
                    :class="!selectedGenre ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-purple-600 hover:text-white'"
                    class="px-4 py-2 rounded-full text-sm font-medium transition-colors duration-200">
                    Tout voir
                </button>

                @foreach($genres as $key => $id)
                    <button 
                        @click="filterShows('{{ $key }}')"
                        :class="selectedGenre === '{{ $key }}' ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-purple-600 hover:text-white'"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-colors duration-200">
                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                    </button>
                @endforeach
            </div>
        </x-aos-wrapper>

        @if(empty($shows))
            <div class="text-white">Aucune série n'a été trouvée.</div>
        @endif

        <div class="shows-grid">
            @include('tv._filtered-grid', ['shows' => $shows])
        </div>
    </div>
</div>
@endsection 