<?php
    $tickers = get_tickers();

    $string = '';
    foreach($tickers as $ticker){
        $string = $string . '  <div style="display:inline; margin-right:2rem;">' . preg_replace( "/\r|\n/", " ", $ticker->content ) . '</div>';
    }
?>

@if(count($tickers) != 0)

<div class="marquee3k carouselTicker-wrap sticky-tickers" data-speed="0.25"
     data-reverse="false"
     data-pausable="true">

     <div>{!! $string !!}</div>

</div>

@endif



@push('components-scripts')
<style>
.sticky-tickers {
  position: fixed;
  z-index: 2000;
  width: 100%;
}

@if(count($tickers) != 0)
#header.scroll-down {
  background-color: #245DAE;
  padding: 70px 0 22px;
}

#checkout-header {
  padding: 40px 0 0;
}

.has_ticker2 {
  padding-top: 14px;
}
@endif
</style>
<script>
let hasTicker = false;
let tickers = @json($tickers);

if(tickers.length) {
    hasTicker = true;
}
</script>

@endpush
