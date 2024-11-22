@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-center sm:justify-end mt-12 space-y-4 sm:space-y-0">
        <div class="flex flex-wrap items-center justify-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-400 bg-gray-800 border border-gray-700 cursor-default rounded-md sm:rounded-l-md">
                    <span class="hidden sm:inline">Précédent</span>
                    <span class="sm:hidden">←</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-gray-800 border border-gray-700 rounded-md sm:rounded-l-md hover:bg-purple-600">
                    <span class="hidden sm:inline">Précédent</span>
                    <span class="sm:hidden">←</span>
                </a>
            @endif

            {{-- Pagination Elements --}}
            <div class="flex flex-wrap items-center justify-center gap-1">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span aria-disabled="true">
                            <span class="relative inline-flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-400 bg-gray-800 border border-gray-700 cursor-default">{{ $element }}</span>
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page">
                                    <span class="relative inline-flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-purple-600 border border-gray-700 cursor-default">{{ $page }}</span>
                                </span>
                            @else
                                <a href="{{ $url }}" class="relative inline-flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-gray-800 border border-gray-700 hover:bg-purple-600" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-gray-800 border border-gray-700 rounded-md sm:rounded-r-md hover:bg-purple-600">
                    <span class="hidden sm:inline">Suivant</span>
                    <span class="sm:hidden">→</span>
                </a>
            @else
                <span class="relative inline-flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-400 bg-gray-800 border border-gray-700 cursor-default rounded-md sm:rounded-r-md">
                    <span class="hidden sm:inline">Suivant</span>
                    <span class="sm:hidden">→</span>
                </span>
            @endif
        </div>
    </nav>
@endif 