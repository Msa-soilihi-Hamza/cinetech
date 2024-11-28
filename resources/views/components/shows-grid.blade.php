<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
    @forelse ($shows as $show)
        <a href="{{ route('tv.show', ['id' => $show['id']]) }}" class="block">
            <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition-transform hover:scale-105">
                @if($show['poster_path'])
                    <img src="https://image.tmdb.org/t/p/w500{{ $show['poster_path'] }}"
                         alt="{{ $show['name'] }}"
                         class="w-full h-100px object-cover">
                @else
                    <div class="w-full h-100px bg-gray-700 flex items-center justify-center">
                        <span class="text-gray-400">No Image</span>
                    </div>
                @endif
                <div class="p-4 bg-gray-800">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-white text-lg font-semibold truncate">{{ $show['name'] }}</h3>
                        <x-favorite-button :id="$show['id']" type="tv" />
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-purple-500 font-bold">{{ number_format($show['vote_average'], 1) }}/10</span>
                        <span class="text-gray-400">{{ \Carbon\Carbon::parse($show['first_air_date'])->format('Y') }}</span>
                    </div>
                </div>
            </div>
        </a>
    @empty
        <div class="col-span-full text-center text-gray-400 py-10">
            Aucune série disponible.
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-8">
    {{ $shows->links('vendor.pagination.tailwind') }}
</div> 