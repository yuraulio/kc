<p>
@foreach($mail_data as $key => $value)
    @if($key != '_token' && $key != 'accept')
        @if($value == '0')
            <span style="font-weight: 700; text-transform: capitalize;">{{ $key }}</span> : No <br />
        @elseif($value == '1')
            <span style="font-weight: 700; text-transform: capitalize;">{{ $key }}</span> : Yes <br />
        @else
            <span style="font-weight: 700; text-transform: capitalize;">{{ $key }}</span> : {{ $value }}<br />
        @endif

    @endif
@endforeach
</p>
