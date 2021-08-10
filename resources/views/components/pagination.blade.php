@if ($paginator->lastPage() > 1)
<ul class="pagination" style = "justify-content: center;">
    <li class="page-item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
        <button class="page-link" data-method="get" data-url="{{ $paginator->url(1) }}">First Page</button>
     </li>
    <li class="page-item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
        <button class="page-link" data-method="get" data-url="{{ $paginator->url($paginator->currentPage()-1) }}">
            <span aria-hidden="true">«</span>
            {{-- Previous --}}
        </button>
    </li>
    @if ( $paginator->currentPage() <= 6 )
    @for ($i = 1; $i <= min([10,$paginator->lastPage()]); $i++)
        <li class="page-item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
            <button class="page-link" data-method="get" data-url="{{ $paginator->url($i) }}">{{ $i }}</button>
        </li>
    @endfor
    @elseif ($paginator->currentPage() <= $paginator->lastPage()-4)
    @for ($i = $paginator->currentPage()-5; $i <= $paginator->currentPage()+4; $i++)
    <li class="page-item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
        <button class="page-link" data-method="get" data-url="{{ $paginator->url($i) }}">{{ $i }}</button>
    </li>
    @endfor
    @else
    @for ($i = max([1,$paginator->lastPage()-9]); $i <= $paginator->lastPage(); $i++)
    <li class="page-item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
        <button class="page-link" data-method="get" data-url="{{ $paginator->url($i) }}">{{ $i }}</button>
    </li>
    @endfor
    @endif
    <li class="page-item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
        <button class="page-link" data-method="get" data-url="{{ $paginator->url($paginator->currentPage()+1) }}" >
            <span aria-hidden="true">»</span>
            {{-- Next --}}
        </button>
    </li>
    <li class="page-item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
        <button class="page-link" data-method="get" data-url="{{ $paginator->url($paginator->lastPage()) }}">Last Page</button>
    </li>
</ul>
@endif