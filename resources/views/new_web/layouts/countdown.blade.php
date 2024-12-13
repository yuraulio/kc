<?php
    $countdown = null;
    $countdowns = get_countdowns($event);

    if(!empty($countdowns)){
        $countdown = end($countdowns);
    }

    $hasTickers = (bool)count(get_tickers());
?>

<link rel="stylesheet" href="{{ asset('argon') }}/css/countdown.css">

@if($countdown != null)
<div id="countdown_container has_ticker3" class="container tabs-container" @if($hasTickers) style="padding-top: 68px" @endif>
    <div class="row">
        <div style="margin:auto 0;" class="col-sm-12 col-md-auto text-left text-sm-center countdown-text">{!! $countdown['content'] !!}</div>
        <div id="countdown" class="@if($countdown['button_status']) col-sm-12 col-md-5 @else col-sm-12 col-md-9 @endif h-100 justify-content-center align-items-center"></div>
        @if($countdown['button_status'])
        <div style="margin:auto 0;" class="col-sm-12 col-md-auto text-right text-sm-center countdown-btn">
            <a id="countdown_enroll_btn" href="#seats" class="btn btn--lg btn--primary go-to-href">{{ $countdown['button_title'] }}</a>
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
    @if($countdown)
    <?php
        $countdownParsed = Carbon::parse($countdown['countdown_to']);

      $now = Carbon::now();

      $timezoneOffset = $now->format('P'); // Example output: -06:00

      $offsetInHours = intval($now->offsetHours);
    ?>

    if(@json($countdown) != null) {

        countdown = @json($countdown);
        hasCountdown = true;

        $('#countdown').countdown({
            year: '{{ $countdownParsed->format('Y') }}',
            month: '{{ $countdownParsed->format('m') }}',
            day: '{{ $countdownParsed->format('d') }}',
            hour: '{{ $countdownParsed->subHours(2)->format('H') }}',
            minute: '{{ $countdownParsed->format('i') }}',
            second: '59',
            timezone: 0,
        });

    }


  @endif
</script>

@endpush
