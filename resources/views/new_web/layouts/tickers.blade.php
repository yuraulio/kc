<?php
    $tickers = get_tickers();
    $allTickersString = '';
?>

@if(count($tickers) != 0)
<div class="ticker-wrapper">
    <div class="ticker-wrap">
        <div class="ticker">
            <?php
            foreach($tickers as $ticker){
                $allTickersString = $allTickersString . ' '. $ticker->content;
            }
            ?>
            <div class="ticker__item">
                {!! $allTickersString !!}
            </div>


        </div>
    </div>
</div>
@endif
