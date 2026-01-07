@if ($paginator->hasPages())
    <div class="pagination-wrapper" style="margin-top: 1.5rem;">
        <nav class="pagination is-centered is-small" role="navigation" aria-label="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="pagination-previous" style="opacity: 0.5; pointer-events: none;">« Trước</span>
            @else
                <a class="pagination-previous" href="{{ $paginator->previousPageUrl() }}">« Trước</a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a class="pagination-next" href="{{ $paginator->nextPageUrl() }}">Sau »</a>
            @else
                <span class="pagination-next" style="opacity: 0.5; pointer-events: none;">Sau »</span>
            @endif
        </nav>
    </div>
@endif
