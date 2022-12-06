<?php
    $countdown = null;
    $countdowns = get_countdowns($event);

    if(!empty($countdowns)){
        $countdown = end($countdowns);
    }

?>

<link rel="stylesheet" href="{{ asset('argon') }}/css/countdown.css">

@if($countdown != null)
<div class="container h-100">
    <div style="padding:10px 0;" class="row">
        <div style="margin:auto;" class="col-sm-12 col-md-4 text-center countdown-text">{!! $countdown['content'] !!}</div>
        <div id="countdown" class="@if($countdown['button_status']) col-sm-12 col-md-5 @else col-sm-12 col-md-8 @endif h-100 justify-content-center align-items-center"></div>
        @if($countdown['button_status'])
        <div style="margin:auto;" class="col-sm-12 col-md-3 text-center countdown-btn">
            <a href="#seats" class="btn btn--lg btn--primary go-to-href">{{ $countdown['button_title'] }}</a>
        </div>
        @endif
    </div>

</div>
@endif



@push('components-scripts')
<script src="{{ asset('argon') }}/js/countdown.jquery.js"></script>
<script>
    let hasCountdown = false;
    let countdown = null;

    if(@json($countdown) != null){

        countdown = @json($countdown);
        hasCountdown = true;

        start_at = new Date(countdown.countdown_to)

        $('#countdown').countdown({
            year: start_at.getFullYear(),
            month: start_at.getMonth() + 1,
            day: start_at.getDate()
        });

    }



</script>

@endpush
