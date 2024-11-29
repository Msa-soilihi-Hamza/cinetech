<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
    @forelse($movies as $movie)
        <x-movie-card :movie="$movie" :loop="$loop" />
    @empty
        <div class="col-span-full text-center text-gray-400 py-10">
            Aucun film disponible.
        </div>
    @endforelse
</div>

@if(method_exists($movies, 'links'))
    <div class="mt-8">
        {{ $movies->links('vendor.pagination.tailwind') }}
    </div>
@endif 