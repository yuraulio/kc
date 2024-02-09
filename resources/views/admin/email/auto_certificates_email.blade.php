@component('mail::message')

# Certificates report

@foreach($reports as $report)

@if(count($report['users']) > 0)
**{{ $report['course'] }}**

@component('mail::table')
| Name       | KC-ID  |
| :------------- | --------:|
@foreach($report['users'] as $user)
| {{ $user['name'] }} | {{ $user['kc_id'] }} |
@endforeach
@endcomponent
@endif

@endforeach

Best,<br>
{{ config('app.name') }}
@endcomponent
