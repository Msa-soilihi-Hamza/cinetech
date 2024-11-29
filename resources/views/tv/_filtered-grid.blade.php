@if($shows->isEmpty())
    <div class="flex flex-col items-center justify-center py-12">
        <div class="text-gray-400 text-xl mb-4">
            Aucune série trouvée pour ce genre
        </div>
        <div class="text-gray-500">
            Essayez un autre genre ou revenez plus tard
        </div>
    </div>
@else
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        @foreach($shows as $show)
            <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform hover:scale-105"
                 data-aos="fade-up"
                 data-aos-duration="600"
                 data-aos-delay="{{ $loop->index * 50 }}">
                <a href="{{ route('tv.show', $show['id']) }}">
                    @if($show['poster_path'])
                        <img src="https://image.tmdb.org/t/p/w500{{ $show['poster_path'] }}" 
                             alt="{{ $show['name'] }}"
                             class="w-full h-[300px] object-cover">
                    @endif
                </a>
                
                <div class="p-4">
                    <div class="flex justify-between items-center mb-2">
                        <a href="{{ route('tv.show', $show['id']) }}" class="block">
                            <h2 class="text-xl font-bold text-white hover:text-purple-500">{{ $show['name'] }}</h2>
                        </a>
                        <x-favorite-button :id="$show['id']" type="tv" />
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-purple-500 font-bold">{{ number_format($show['vote_average'], 1) }}/10</span>
                        <span class="text-gray-400">{{ \Carbon\Carbon::parse($show['first_air_date'])->format('Y') }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($shows->hasPages())
        <div class="mt-8">
            {{ $shows->links() }}
        </div>
    @endif
@endif