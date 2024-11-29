<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
    @forelse($shows as $show)
        <x-show-card :show="$show" :loop="$loop" />
    @empty
        <div class="col-span-full text-center text-gray-400 py-10">
            Aucune série disponible.
        </div>
    @endforelse
</div>

@if(method_exists($shows, 'links'))
    <div class="mt-8">
        {{ $shows->links('vendor.pagination.tailwind') }}
    </div>
@endif 