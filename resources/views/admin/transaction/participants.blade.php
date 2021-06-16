@extends('layouts.app', [
    'title' => __('User Management'),
    'parentSection' => 'laravel',
    'elementName' => 'user-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Examples') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('city.index') }}">{{ __('Cities Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('List') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Participants') }}</h3>
                                <p class="text-sm mb-0">
                                        {{ __('This is an example of Booking Participants.') }}
                                    </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>

                    <?php //dd($transactions[0]); ?>

                    <div class="table-responsive py-4">

                    <div class="text-center">
                <a class="btn btn-success btn-lg " href="#"><i class="fa fa-filter "></i> Filter</a>
                <a class="btn btn-secondary btn-lg " onclick="clear()" href="#"><i class="fa fa-eraser "></i> Clear Filter</a>
                </div>
                    <table id="range-date" cellspacing="5" cellpadding="5" border="0">
                        <tbody>
                            <tr>
                                <div class="" id="filter_col1" data-column="1">
                                    <label>Event</label>
                                    <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col1_filter">
                                    </select>

                                </div>
                            </tr>

                            <tr>
                            <div class="" id="filter_col4" data-column="4">
                            <label>Coupon</label>
                            <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col4_filter" placeholder="Coupon">
                        </div>
                            </tr>
                            <tr>
                                <td>From:</td>
                                <td><input type="text" id="min" name="min"></td>
                            </tr>
                            <tr>
                                <td>To:</td>
                                <td><input type="text" id="max" name="max"></td>
                            </tr>
                            <tr>
                                <td>Total Amount</td>
                                <td id="total"></td>
                            </tr>
                        </tbody>
                    </table>
                        <table class="table align-items-center table-flush"  id="participants_table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Student Data') }}</th>
                                    <th scope="col">{{ __('Event') }}</th>
                                    <th scope="col">{{ __('Ticket Type') }}</th>
                                    <th scope="col">{{ __('Ticket Price') }}</th>
                                    <th scope="col">{{ __('Coupon') }}</th>
                                    <th scope="col">{{ __('Registration Date') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>{{ __('Student Data') }}</th>
                                    <th>{{ __('Event') }}</th>
                                    <th>{{ __('Ticket Type') }}</th>
                                    <th>{{ __('Ticket Price') }}</th>
                                    <th>{{ __('Coupon') }}</th>
                                    <th>{{ __('Registration Date') }}</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                <?php //dd($transaction); ?>
                                    <tr>
                                        <td>
                                            <?php if(count($transaction->user) > 0){
                                                        echo $transaction->user->first()['firstname'].$transaction->user->first()['lasttname'];
                                                    }else{
                                                        echo "";
                                                    }?>
                                        </td>
                                        <td>
                                            <?php if(count($transaction->event) > 0){
                                                        echo $transaction->event->first()['title'];
                                                    }else{
                                                        echo "";
                                                    }?>
                                        </td>
                                        <td>
                                            @if($transaction['type'] != null )
                                                {{ $transaction['type'] }}
                                            @endif
                                        </td>
                                        <td>{{ $transaction->amount }}</td>
                                        <td>{{ $transaction->coupon_code }}</td>
                                        <td>{{ date_format($transaction->created_at, 'Y/m/d' )}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables-datetime/datetime.min.css">
@endpush

@push('js')
    <script src="{{ asset('argon') }}/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('argon') }}/vendor/datatables-datetime/datetime.min.js"></script>
    <script>

var minDate, maxDate;

// Custom filtering function which will search data in column four between two values
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var min = minDate.val();
        var max = maxDate.val();
        var date = new Date( data[5] );



        if (
            ( min === null && max === null ) ||
            ( min === null && date <= max ) ||
            ( min <= date   && max === null ) ||
            ( min <= date   && date <= max )
        ) {
            return true;
        }
        return false;
    }
);

$(document).ready(function() {
    // Create date inputs
    minDate = new DateTime($('#min'), {
        format: ('MMM Do YY')
    });
    maxDate = new DateTime($('#max'), {
        format: 'MMM Do YY'
    });

    // DataTables initialisation
    var table = $('#participants_table').DataTable();

    events = table.column(1).data().unique().sort()
    coupons = table.column(4).data().unique().sort()
    prices = table.column(3).data()
    //console.log(prices)

    var sum = prices.reduce(function(a, b){
        return parseInt(a) + parseInt(b);
    }, 0);

    //let sum = 0;

    $.each(events, function(key, value){

        $('#col1_filter').append('<option value="'+value+'">'+value+'</option>')
    })
    $.each(coupons, function(key, value){
        $('#col4_filter').append('<option value="'+value+'">'+value+'</option>')
    })

    // $.each(prices, function(key, value){
    //     //console.log(value)
    //     //sum = sum + parseInt(value)
    // })
    $('#total').text(sum)



    $("#participants_table tfoot th").each( function ( i ) {


        if(i != 5){
            var select = $('<select><option value=""></option></select>')
            .appendTo( $(this).empty() )
            .on( 'change', function () {
                table.column( i )
                    .search( $(this).val() )
                    .draw();

                    prices = table.column(3).data()

                    var sum = prices.reduce(function(a, b){
                        return parseInt(a) + parseInt(b);
                    }, 0);
                    $('#total').text(sum)
            } );


            //
        table.column( i ).data().unique().sort().each( function ( d, j ) {

            select.append( '<option value="'+d+'">'+d+'</option>' )

        } );
        }

    } );

    // Refilter the table
    $('#min, #max').on('change', function () {
        table.draw();
    });
});









function filterGlobal () {
    $('#participants_table').DataTable().search(
        $('#global_filter').val(),


    ).draw();

}

    function filterColumn ( i ) {
        $('#participants_table').DataTable().column( i ).search(
            $('#col'+i+'_filter').val()
        ).draw();
    }

    $(document).ready(function() {
        $('#participants_table').DataTable();



        $('input.global_filter').on( 'keyup click', function () {
            filterGlobal();
        } );

        $('input.column_filter').on( 'keyup click', function () {
            filterColumn( $(this).parents('div').attr('data-column') );
        } );
    } );

    $('select.column_filter').on('change', function () {
            filterColumn( $(this).parents('div').attr('data-column') );
            var table = $('#participants_table').DataTable();
            alert(table.column( 3 ).data().sum());
        } );




    </script>
@endpush
