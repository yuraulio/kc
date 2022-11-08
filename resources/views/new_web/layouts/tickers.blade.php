<?php
    $tickers = get_tickers();
?>

@if(count($tickers) != 0)
<div class="carouselTicker-wrapper">
    <div id="carouselTicker" class="carouselTicker container">
        <ul class="carouselTicker__list">
            <li class="carouselTicker__item">
                <a href="https://placehold.it/290x60"><img src="https://placehold.it/290x60" alt="banner image"></a>
            </li>
            <li class="carouselTicker__item">
                <a href="https://placehold.it/190x65"><img src="https://placehold.it/190x65" alt="banner image"></a>
            </li>
            <li class="carouselTicker__item">
                <a href="#"><img src="https://placehold.it/190x65" alt="banner image"></a>
            </li>
            <li class="carouselTicker__item">
                <a href="#"><img src="https://placehold.it/190x65" alt="banner image"></a>
            </li>
            <li class="carouselTicker__item">
                <a href="#"><img src="https://placehold.it/190x65" alt="banner image"></a>
            </li>
            <li class="carouselTicker__item">
                <a href="https://placehold.it/290x60"><img src="https://placehold.it/290x60" alt="banner image"></a>
            </li>
            <li class="carouselTicker__item">
                <a href="https://placehold.it/190x65"><img src="https://placehold.it/190x65" alt="banner image"></a>
            </li>
            <li class="carouselTicker__item">
                <a href="#"><img src="https://placehold.it/190x65" alt="banner image"></a>
            </li>
            <li class="carouselTicker__item">
                <a href="#"><img src="https://placehold.it/190x65" alt="banner image"></a>
            </li>
            <li class="carouselTicker__item">
                <a href="#"><img src="https://placehold.it/190x65" alt="banner image"></a>
            </li>
            <li class="carouselTicker__item">
                <a href="#"><img src="https://placehold.it/190x65" alt="banner image"></a>
            </li>
            <li class="carouselTicker__item">
                <a href="#"><img src="https://placehold.it/190x65" alt="banner image"></a>
            </li>
            <li class="carouselTicker__item">
                <a href="#"><img src="https://placehold.it/190x65" alt="banner image"></a>
            </li>
            <li class="carouselTicker__item">
                <a href="#"><img src="https://placehold.it/190x65" alt="banner image"></a>
            </li>

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
