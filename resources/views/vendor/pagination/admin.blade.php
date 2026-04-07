@if ($paginator->hasPages())
<nav class="admin-pagination" aria-label="Pagination">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="pg-btn pg-btn-disabled">‹</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="pg-btn" rel="prev">‹</a>
    @endif

    {{-- Page numbers --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="pg-btn pg-dots">…</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="pg-btn pg-btn-active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="pg-btn">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="pg-btn" rel="next">›</a>
    @else
        <span class="pg-btn pg-btn-disabled">›</span>
    @endif
</nav>
@endif
