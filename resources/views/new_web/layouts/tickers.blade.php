<?php
    $tickers = get_tickers();
?>

@if(!empty($tickers))
<div class="ticker-wrap">
  <div class="ticker">

    @foreach($tickers as $ticker)
        <div class="ticker__item">
            {!! $ticker->content !!}
        </div>
    @endforeach


  </div>
</div>
@endif




