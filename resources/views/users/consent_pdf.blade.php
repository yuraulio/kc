<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  </head>
  <style>
    body{font-family: 'Foco !important';}
  </style>
  <body>
    <table width="table">
    <?php
        $consent = (array) json_decode($user->consent,true);
    ?>

        @if(isset($consent['firstname']))
        <tr>
            <th>{{ __('Firstname')}}</th>
            <td>{{ $consent['firstname'] }}</td>
        </tr>
        @endif

        @if(isset($consent['lastname']))
        <tr>
            <th>{{ __('Lastname')}}</th>
            <td>{{ $consent['lastname'] }}</td>
        </tr>
        @endif

        @if(isset($consent['afm']))
        <tr>
            <th>{{ __('VAT Number')}}</th>
            <td>{{ $consent['afm'] }}</td>
        </tr>
        @endif

        @if(isset($consent['ip']))
        <tr>
            <th>{{ __('IP')}}</th>
            <td>{{ $consent['ip'] }}</td>
        </tr>
        @endif

        @if(isset($consent['date']))
        <tr>
            <th>{{ __('Date')}}</th>
            <td>{{ $consent['date'] }}</td>
        </tr>
        @endif
        {{--<tr>
            @foreach((array) json_decode($user->consent,true) as $key => $consent)
            <th scope="col">
                @if($key == 'ip')
                    {{ strtoupper($key) }}
                @elseif($key == 'afm')
                    {{ 'VAT Number' }}
                @else
                    @if($key != 'billafm')
                        {{ ucfirst($key) }}
                    @endif
                @endif
            </th>
            @endforeach
        </tr>--}}


      {{--<tbody>
        <tr>
            @foreach((array) json_decode($user->consent,true) as $key => $consent)

                @if($key == 'ip' || $key == 'afm' )
                    <td scope="row">{{$consent}}</td>
                @else
                    @if($key != 'billafm')
                        <td>{!!$consent!!}</td>
                    @endif
                @endif

            @endforeach
        </tr>

      </tbody>--}}
    </table>

    <hr>
    <h1>Terms & Conditions</h1>
    {!! $terms !!}
    <hr>
    @if($privacy)
    <h1>Data Privacy Policy</h1>
    {!! $privacy !!}
    @endif
</body>
</html>
