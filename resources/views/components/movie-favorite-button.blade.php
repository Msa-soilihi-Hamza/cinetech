@props(['id'])

@auth
    <div x-data="{
        id: {{ $id }},
        isFavorite: false,
        async init() {
            try {
                const response = await axios.get('/favorites/check', {
                    params: {
                        tmdb_id: this.id,
                        type: 'movie'
                    }
                });
                this.isFavorite = response.data.exists;
            } catch (error) {
                console.error('Erreur:', error);
            }
        },
        async toggleFavorite() {
            try {
                if (this.isFavorite) {
                    await axios.delete('{{ route('favorites.destroy') }}', {
                        data: {
                            tmdb_id: this.id,
                            type: 'movie'
                        }
                    });
                } else {
                    await axios.post('{{ route('favorites.store') }}', {
                        tmdb_id: this.id,
                        type: 'movie'
                    });
                }
                this.isFavorite = !this.isFavorite;
            } catch (error) {
                console.error('Erreur:', error);
            }
        }
    }" x-init="init()">
        <button @click.prevent="toggleFavorite()" 
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all duration-200"
                :class="isFavorite ? 'bg-purple-500 text-white hover:bg-purple-600' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 fill="none" 
                 viewBox="0 0 24 24" 
                 stroke-width="1.5" 
                 stroke="currentColor" 
                 class="w-5 h-5">
                <path stroke-linecap="round" 
                      stroke-linejoin="round" 
                      d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
            </svg>
            <span x-text="isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris'"></span>
        </button>
    </div>
@endauth
