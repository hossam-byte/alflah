@if ($paginator->hasPages())
    <nav class="d-flex justify-content-center align-items-center flex-wrap gap-2 py-3">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="btn btn-sm btn-outline-secondary disabled rounded-pill px-3" aria-disabled="true">
                <i class="fas fa-chevron-right small me-1"></i> السابق
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-sm btn-green rounded-pill px-3 shadow-sm transition-all" rel="prev">
                <i class="fas fa-chevron-right small me-1"></i> السابق
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="d-flex gap-1">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="btn btn-sm disabled px-2">...</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="btn btn-sm btn-green rounded-circle active fw-bold shadow" style="width:36px; height:36px; display:flex; align-items:center; justify-content:center;">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="btn btn-sm btn-light rounded-circle hover-green shadow-sm fw-medium" style="width:36px; height:36px; display:flex; align-items:center; justify-content:center; color: #555;">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-sm btn-green rounded-pill px-3 shadow-sm transition-all" rel="next">
                التالي <i class="fas fa-chevron-left small ms-1"></i>
            </a>
        @else
            <span class="btn btn-sm btn-outline-secondary disabled rounded-pill px-3" aria-disabled="true">
                التالي <i class="fas fa-chevron-left small ms-1"></i>
            </span>
        @endif
        
        <div class="w-100 text-center mt-2 small text-muted">
            عرض من {{ $paginator->firstItem() }} إلى {{ $paginator->lastItem() }} من أصل {{ $paginator->total() }} نتيجة
        </div>
    </nav>
@endif

<style>
    .hover-green:hover {
        background-color: var(--green-pale) !important;
        color: var(--green-main) !important;
        border-color: var(--green-main) !important;
    }
    .transition-all {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-green.active {
        background: var(--green-main);
        border: none;
    }
</style>
