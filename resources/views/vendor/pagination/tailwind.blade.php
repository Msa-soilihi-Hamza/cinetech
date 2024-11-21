@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-end mt-12">
        <div class="flex items-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-800 border border-gray-700 cursor-default rounded-l-md">
                    Précédent
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-800 border border-gray-700 rounded-l-md hover:bg-purple-600">
                    Précédent
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true">
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-800 border-t border-b border-gray-700 cursor-default">{{ $element }}</span>
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page">
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-purple-600 border-t border-b border-gray-700 cursor-default">{{ $page }}</span>
                            </span>
                        @else
                            <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-800 border-t border-b border-gray-700 hover:bg-purple-600" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-800 border border-gray-700 rounded-r-md hover:bg-purple-600">
                    Suivant
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-800 border border-gray-700 cursor-default rounded-r-md">
                    Suivant
                </span>
            @endif
        </div>
    </nav>
@endif 