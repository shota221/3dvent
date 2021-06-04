@if ($paginator->lastPage() > 1)
<ul class="pagination" style = "justify-content: center;">
    <li class="page-item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
        <a class="page-link" href="{{ $paginator->url(1) }}">First Page</a>
     </li>
    <li class="page-item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
        <a class="page-link" href="{{ $paginator->url($paginator->currentPage()-1) }}">
            <span aria-hidden="true">«</span>
            {{-- Previous --}}
        </a>
    </li>
    @if ( $paginator->currentPage() <= 6 )
    @for ($i = 1; $i <= min([10,$paginator->lastPage()]); $i++)
        <li class="page-item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
            <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
        </li>
    @endfor
    @elseif ($paginator->currentPage() <= $paginator->lastPage()-4)
    @for ($i = $paginator->currentPage()-5; $i <= $paginator->currentPage()+4; $i++)
    <li class="page-item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
        <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
    </li>
    @endfor
    @else
    @for ($i = max([1,$paginator->lastPage()-9]); $i <= $paginator->lastPage(); $i++)
    <li class="page-item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
        <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
    </li>
    @endfor
    @endif
    <li class="page-item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
        <a class="page-link" href="{{ $paginator->url($paginator->currentPage()+1) }}" >
            <span aria-hidden="true">»</span>
            {{-- Next --}}
        </a>
    </li>
    <li class="page-item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
        <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">Last Page</a>
    </li>
</ul>
@endif