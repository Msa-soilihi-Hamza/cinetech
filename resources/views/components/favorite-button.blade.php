@props([
    'id',
    'type',
    'buttonClass' => 'transition-colors duration-200',
    'activeClass' => 'text-purple-500 hover:text-purple-700',
    'inactiveClass' => 'text-gray-400 hover:text-purple-500',
    'iconClass' => 'w-6 h-6'
])

@auth
    @php
        $isFavorite = Auth::user()->favorites()
            ->where('tmdb_id', $id)
            ->where('type', $type)
            ->exists();
    @endphp

    <div x-data="{ 
        isFavorite: {{ json_encode($isFavorite) }},
        async toggleFavorite() {
            try {
                if (this.isFavorite) {
                    await axios.delete('{{ route('favorites.destroy') }}', {
                        data: {
                            tmdb_id: {{ $id }},
                            type: '{{ $type }}'
                        }
                    });
                } else {
                    await axios.post('{{ route('favorites.store') }}', {
                        tmdb_id: {{ $id }},
                        type: '{{ $type }}'
                    });
                }
                this.isFavorite = !this.isFavorite;
            } catch (error) {
                console.error('Erreur:', error);
            }
        }
    }">
        <button @click.prevent="toggleFavorite()" 
                :class="isFavorite ? '{{ $buttonClass . ' ' . $activeClass }}' : '{{ $buttonClass . ' ' . $inactiveClass }}'"
        >
            <svg xmlns="http://www.w3.org/2000/svg" 
                 fill="currentColor" 
                 viewBox="0 0 24 24" 
                 stroke-width="1.5" 
                 stroke="currentColor" 
                 class="{{ $iconClass }}">
                <path stroke-linecap="round" 
                      stroke-linejoin="round" 
                      d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
            </svg>
        </button>
    </div>
@endauth
