<?php
    $tickers = get_tickers();
?>

@if(count($tickers) != 0)



<div class="carouselTicker__wrap">
    <div id="carouselTicker" class="carouselTicker text-center">
        <ul class="carouselTicker__list">
            @foreach($tickers as $ticker)
            <li class="carouselTicker__item">
                {!! $ticker->content !!}
            </li>
            @endforeach

        </ul>
    </div>
</div>
@endif

@push('components-scripts')
<script>
    let hasTicker = false;
    let tickers = @json($tickers);

    if(tickers.length != 0){
        hasTicker = true;
    }



</script>

@endpush
