<div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform hover:scale-105"
     data-aos="fade-up"
     data-aos-duration="600"
     data-aos-delay="{{ $loop->index * 50 }}">
    <a href="{{ route('movies.show', $movie['id']) }}">
        @if($movie['poster_path'])
            <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                 alt="{{ $movie['title'] }}"
                 class="w-full h-auto object-cover">
        @endif
    </a>
    
    <div class="p-4">
        <div class="flex justify-between items-center mb-2">
            <a href="{{ route('movies.show', $movie['id']) }}" class="block">
                <h2 class="text-xl font-bold text-white hover:text-purple-500">{{ $movie['title'] }}</h2>
            </a>
            <x-favorite-button :id="$movie['id']" type="movie" />
        </div>
        <div class="flex justify-between items-center">
            <span class="text-purple-500 font-bold">{{ number_format($movie['vote_average'], 1) }}/10</span>
            <span class="text-gray-400">{{ \Carbon\Carbon::parse($movie['release_date'])->format('Y') }}</span>
        </div>
    </div>
</div> 