    @if ($paginator->hasPages())
    <ul class="pagination">

        @if ($paginator->onFirstPage())
            <li class="paginate_button page-item previous disabled"><a href="#" aria-controls="datatable-basic34" data-dt-idx="0" tabindex="0" class="page-link">«</a></li>
        @else
            <li class="paginate_button page-item previous"><a href="{{ $paginator->previousPageUrl() }}" aria-controls="datatable-basic34" data-dt-idx="0" tabindex="0" class="page-link">«</a></li>
        @endif



        @foreach ($elements as $element)

            @if (is_string($element))
            <li class="paginate_button page-item disabled"><a href="#" aria-controls="datatable-basic34" data-dt-idx="2" tabindex="0" class="page-link">{{ $element }}</a></li>
            @endif


            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                    <li class="paginate_button page-item active"><a href="#" aria-controls="datatable-basic34" data-dt-idx="1" tabindex="0" class="page-link">{{ $page }}</a></li>
                    @else
                    <li class="paginate_button page-item "><a href="{{ $url }}" aria-controls="datatable-basic34" data-dt-idx="2" tabindex="0" class="page-link">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach



        @if ($paginator->hasMorePages())
            <li class="paginate_button page-item next" id="datatable-basic34_next"><a href="{{ $paginator->nextPageUrl() }}" aria-controls="datatable-basic34" data-dt-idx="3" tabindex="0" class="page-link">»</a></li>
        @else
            <li class="paginate_button page-item next disabled" id="datatable-basic34_next"><a href="#" aria-controls="datatable-basic34" data-dt-idx="3" tabindex="0" class="page-link">»</a></li>
        @endif
    </ul>
@endif
