@if ($paginator->hasPages())
    <div class="pagination-wrapper" style="margin-top: 1.5rem;">
        <div class="buttons is-centered has-addons">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="button is-small" disabled style="opacity: 0.5; pointer-events: none;">« Trước</span>
            @else
                <a class="button is-small" href="{{ $paginator->previousPageUrl() }}">« Trước</a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="button is-small" disabled>…</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="button is-small is-primary">{{ $page }}</span>
                        @else
                            <a class="button is-small" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a class="button is-small" href="{{ $paginator->nextPageUrl() }}">Sau »</a>
            @else
                <span class="button is-small" disabled style="opacity: 0.5; pointer-events: none;">Sau »</span>
            @endif
        </div>
        
        <p class="has-text-centered has-text-grey is-size-7 mt-2">
            Hiển thị {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }} trong tổng số {{ $paginator->total() }} kết quả
        </p>
    </div>
@endif
