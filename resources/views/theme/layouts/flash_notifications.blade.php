<script>
@if (Session::has('opstatus'))
    @if (Session::get('opstatus'))
    notify("{!! Session::get('opmessage') !!}", 'success', 8000);
    @else
    notify("{!! Session::get('opmessage') !!}", 'error', 8000);
    @endif
@endif

@if (Session::has('message'))

    notify("{!! Session::get('message') !!}", 'success', 8000);

@endif

@if (Session::has('dperror'))

    notify("{!! Session::get('dperror') !!}", 'error', 8000);

@endif
</script>
