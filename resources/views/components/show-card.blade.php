@props(['show', 'loop'])

<div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform hover:scale-105"
     data-aos="fade-up"
     data-aos-duration="600"
     data-aos-delay="{{ $loop->index * 50 }}">
    <a href="{{ route('tv.show', $show['id']) }}">
        @if($show['poster_path'])
            <img src="https://image.tmdb.org/t/p/w500{{ $show['poster_path'] }}" 
                 alt="{{ $show['name'] }}"
                 class="w-full h-[300px] object-cover">
        @else
            <div class="w-full h-[300px] bg-gray-700 flex items-center justify-center">
                <span class="text-gray-400">Image non disponible</span>
            </div>
        @endif
    </a>
    
    <div class="p-4">
        <div class="flex justify-between items-center mb-2">
            <a href="{{ route('tv.show', $show['id']) }}" class="block">
                <h2 class="text-xl font-bold text-white hover:text-purple-500">
                    {{ $show['name'] ?? $show['original_name'] }}
                </h2>
            </a>
            <x-favorite-button :id="$show['id']" type="tv" />
        </div>
        <div class="flex justify-between items-center">
            @if(isset($show['vote_average']))
                <span class="text-purple-500 font-bold">{{ number_format($show['vote_average'], 1) }}/10</span>
            @endif
            @if(isset($show['first_air_date']))
                <span class="text-gray-400">{{ \Carbon\Carbon::parse($show['first_air_date'])->format('Y') }}</span>
            @endif
        </div>
    </div>
</div> 