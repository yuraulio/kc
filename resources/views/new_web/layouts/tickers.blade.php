<?php
    $tickers = get_tickers();

    $string = '';
    foreach($tickers as $ticker){
        $string = $string . '  <div style="display:inline; margin-right:2rem;">' . $ticker->content . '</div>';
    }
    $string = $string ;
?>

@if(count($tickers) != 0)

<div class="marquee3k carouselTicker-wrap" data-speed="0.25"
     data-reverse="false"
     data-pausable="true">

     <div>{!! $string !!}</div>

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
