<nav class="border-top mt-4 pt-3" aria-label="Catalog pagination">
    <ul class="pagination pagination-lg pt-2 pt-md-3">
        @if ($paginator->onFirstPage())
            <li class="page-item disabled me-auto">
                <span class="page-link d-flex align-items-center h-100 fs-lg px-2"><i class="ci-chevron-left mx-1"></i></span>
            </li>
        @else
            <li class="page-item me-auto">
                <a class="page-link d-flex align-items-center h-100 fs-lg px-2" href="{{ $paginator->previousPageUrl() }}"><i class="ci-chevron-left mx-1"></i></a>
            </li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item"><span class="page-link pe-none">{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li class="page-item ms-auto">
                <a class="page-link d-flex align-items-center h-100 fs-lg px-2" href="{{ $paginator->nextPageUrl() }}"><i class="ci-chevron-right mx-1"></i></a>
            </li>
        @else
            <li class="page-item disabled ms-auto">
                <span class="page-link d-flex align-items-center h-100 fs-lg px-2"><i class="ci-chevron-right mx-1"></i></span>
            </li>
        @endif
    </ul>
</nav>
