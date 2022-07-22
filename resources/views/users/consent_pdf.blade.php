<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  </head>
  <body>
    <table width="table">
    <thead>
        <tr>
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
        </tr>

      </thead>
      <tbody>
        <tr>
            @foreach((array) json_decode($user->consent,true) as $key => $consent)

                @if($key == 'ip' || $key == 'afm' )
                    <td scope="row">{{$consent}}</td>
                @else
                    @if($key != 'billafm')
                        <td>{{$consent}}</td>
                    @endif
                @endif

            @endforeach
        </tr>

      </tbody>
    </table>

    <hr>
    <h1>Terms & Conditions</h1>
    {!! $terms !!}
    <hr>
    <h1>Data Privacy Policy</h1>
    {!! $privacy !!}
</body>
</html>
