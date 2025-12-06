@if ($paginator->hasPages())
    <nav role="navigation" class="flex justify-center">
        <ul class="inline-flex items-center space-x-1">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg shadow">
                        ‹
                    </span>
                </li>
            @else
                <li>
                    <button wire:click="previousPage('{{ $paginator->getPageName() }}')"
                        class="px-3 py-2 text-sm bg-white rounded-lg shadow hover:bg-blue-50 transition">
                        ‹
                    </button>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- Dots --}}
                @if (is_string($element))
                    <li><span class="px-3 py-2 text-sm text-gray-500">...</span></li>
                @endif

                {{-- Page Numbers --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="px-3 py-2 text-sm bg-blue-600 text-grey rounded-lg shadow">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                    class="px-3 py-2 text-sm font-semibold bg-white rounded-lg shadow hover:bg-blue-50 transition">
                                    {{ $page }}
                                </button>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <button wire:click="nextPage('{{ $paginator->getPageName() }}')"
                        class="px-3 py-2 text-sm bg-white rounded-lg shadow hover:bg-blue-50 transition">
                        ›
                    </button>
                </li>
            @else
                <li>
                    <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg shadow">
                        ›
                    </span>
                </li>
            @endif

        </ul>
    </nav>
@endif
