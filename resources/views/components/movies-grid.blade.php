<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
    @forelse($movies as $movie)
        <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition-transform hover:scale-105">
            <a href="{{ route('movies.show', $movie['id']) }}">
                @if($movie['poster_path'])
                    <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                         alt="{{ $movie['title'] }}"
                         class="w-full h-auto">
                @else
                    <div class="w-full h-100px bg-gray-700 flex items-center justify-center">
                        <span class="text-gray-400">No Image</span>
                    </div>
                @endif
                <div class="p-4">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-xl font-bold text-white hover:text-purple-500">
                            {{ $movie['title'] }}
                        </h2>
                        <x-favorite-button :id="$movie['id']" type="movie" />
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-purple-500 font-bold">
                            {{ number_format($movie['vote_average'], 1) }}/10
                        </span>
                        <span class="text-gray-400">
                            {{ \Carbon\Carbon::parse($movie['release_date'])->format('Y') }}
                        </span>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-span-full text-center text-gray-400 py-10">
            Aucun film disponible.
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-8">
    {{ $movies->links('vendor.pagination.tailwind') }}
</div> 