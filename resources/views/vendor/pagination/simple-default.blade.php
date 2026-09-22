{{-- Pagination sederhana (prev/next) untuk tema .pagination --}}
@if ($paginator->hasPages())
    @if ($paginator->onFirstPage())
        <span aria-disabled="true">← Sebelumnya</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev">← Sebelumnya</a>
    @endif

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya →</a>
    @else
        <span aria-disabled="true">Berikutnya →</span>
    @endif
@endif