@if(!empty($movies))
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        @foreach($movies->chunk(8) as $chunkIndex => $chunk)
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 col-span-full" 
                 data-aos="fade-up"
                 data-aos-duration="800"
                 data-aos-delay="{{ $chunkIndex * 200 }}">
                @foreach($chunk as $movie)
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform hover:scale-105">
                        <a href="{{ route('movies.show', $movie['id']) }}">
                            @if($movie['poster_path'])
                                <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                                     alt="{{ $movie['title'] }}"
                                     class="w-full h-[250px] sm:h-[400px] object-cover"
                                     loading="lazy">
                            @else
                                <div class="w-full h-[250px] sm:h-[400px] bg-gray-700 flex items-center justify-center">
                                    <span class="text-gray-400">Image non disponible</span>
                                </div>
                            @endif
                        </a>
                        
                        <div class="p-2 sm:p-4">
                            <div class="flex justify-between items-center mb-2">
                                <a href="{{ route('movies.show', $movie['id']) }}" class="block flex-1">
                                    <h2 class="text-sm sm:text-xl font-bold text-white hover:text-purple-500 break-words sm:truncate">
                                        @if(strlen($movie['title']) > 15)
                                            <span class="sm:hidden">{{ wordwrap($movie['title'], 15, "\n", true) }}</span>
                                            <span class="hidden sm:inline">{{ $movie['title'] }}</span>
                                        @else
                                            {{ $movie['title'] }}
                                        @endif
                                    </h2>
                                </a>
                                <div class="ml-2 transform scale-75 sm:scale-100">
                                    <x-favorite-button :id="$movie['id']" type="movie" />
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-purple-500 font-bold">{{ number_format($movie['vote_average'], 1) }}/10</span>
                                <span class="text-gray-400">{{ \Carbon\Carbon::parse($movie['release_date'])->format('Y') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    @if($movies->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $movies->links() }}
        </div>
    @endif
@endif 