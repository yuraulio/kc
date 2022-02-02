@extends('layouts.app', [
    'title' => __('Participant List'),
    'parentSection' => 'laravel',
    'elementName' => 'participants-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot
            @slot('filter')
                <!-- <a href="#" class="btn btn-sm btn-neutral">{{ __('Filters') }}</a> -->
                <a class="btn btn-sm btn-neutral" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">{{ __('Filters') }}</a>

            @endslot

            <li class="breadcrumb-item"><a href="{{ route('transaction.participants') }}">{{ __('Revenue List') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('List') }}</li>
        @endcomponent
        @include('admin.transaction.layouts.cards')
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Revenue') }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>

                    <?php //dd($transactions[0]); ?>

                    <div class="table-responsive py-4">


                <div class="collapse" id="collapseExample">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-3 filter_col" id="filter_col1" data-column="1">
                                <label>Event</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col1_filter">
                                <option selected value> -- All -- </option>
                                </select>
                            </div>
                            <div class="col-sm-3 filter_col" id="filter_col4" data-column="4">
                                <label>Coupon</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col4_filter" placeholder="Coupon">
                                <option selected value> -- All -- </option>
                                </select>
                            </div>

                            <div class="col-sm-3 filter_col" id="filter_col8" data-column="8">
                                <label>Payment Method</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col8_filter" placeholder="Payment Method">
                                <option selected value> -- All -- </option>
                                </select>
                            </div>

                            <div class="col-sm-3 filter_col">
                                <div class="form-group">
                                    <label>From:</label>
                                    <input class="select2-css" type="text" id="min" name="min">
                                </div>
                            </div>
                            <div class="col-sm-3 filter_col">
                                <div class="form-group">
                                    <label>To:</label>
                                    <input class="select2-css" type="text" id="max" name="max">
                                </div>
                            </div>
                            {{--<Button type="button" onclick="ClearFields();" class="btn btn-secondary btn-lg "> Clear Filter</Button>--}}
                        </div>
                    </div>
                </div>

                <table class="table align-items-center table-flush"  id="participants_table">

                    <thead class="thead-light">
                        <tr>
                            <th scope="col">{{ __('Student Data') }}</th>
                            <th scope="col">{{ __('Event') }}</th>

                            <th scope="col">{{ __('Ticket Type') }}</th>
                            <th scope="col">{{ __('Ticket Price') }}</th>
                            <th scope="col">{{ __('Coupon') }}</th>
                            <th class="participant_elearning none">{{ __('Video Seen') }}</th>
                            <th scope="col">{{ __('Registration Date') }}</th>
                            <th class="participant_elearning none">{{ __('Expiration Date') }}</th>
                            <th class="col">{{ __('Payment Method') }}</th>
                            <th hidden>{{ __('Event ID') }}</th>
                            {{--<th class="participant_elearning none">{{ __('New Expiration Date') }}</th>--}}
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>{{ __('Student Data') }}</th>
                            <th>{{ __('Event') }}</th>

                            <th>{{ __('Ticket Type') }}</th>
                            <th>{{ __('Ticket Price') }}</th>
                            <th>{{ __('Coupon') }}</th>
                            <th class="participant_elearning none">{{ __('Video Seen') }}</th>
                            <th>{{ __('Registration Date') }}</th>
                            <th class="participant_elearning none">{{ __('Expiration Date') }}</th>
                            <th> {{ __('Payment Method') }} </th>
                            <th hidden>{{ __('Event ID') }}</th>
                            {{--<th class="participant_elearning none">{{ __('New Expiration Date') }}</th>--}}
                        </tr>
                    </tfoot>
                    <tbody>
                    <?php //dd($transactions[100]); ?>
                        @foreach ($transactions as $transaction)

                            <tr>
                                <td><a href="{{ route('user.edit', $transaction['user_id']) }}">{{$transaction['name']}}</a></td>
                                <td>{{$transaction['event_title']}}</td>

                                <td>{{ $transaction['type'] }}</td>
                                <td><?= '€'.number_format($transaction['amount'], 2, '.', ''); ?></td>
                                <td>{{ $transaction['coupon_code'] }}</td>
                                <td class="participant_elearning none">{{$transaction['videos_seen']}}</td>

                                <td>{{ $transaction['date']}}</td>

                                <td class="exp_{{$transaction['id']}} participant_elearning none" >
                                    <?= date('m/d/Y',strtotime($transaction['expiration']))  ?>


                                </td>
                                <td> {{$transaction['paymentMethod']}} </td>
                                <td hidden>{{$transaction['event_id']}}</td>
                                {{--<td class="participant_elearning none">
                                    <input id="{{$transaction['id']}}" class="form-control datepicker" placeholder="Select date" type="text" value="<?= ($transaction['expiration'] != null) ? $transaction['expiration'] : ''; ?>">
                                    <button class="update_exp btn btn-info btn-sm" style="margin-top:10px;" type="button" data-id="{{$transaction['id']}}" >Update</button>
                                </td>--}}
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
       var eventsArray = {};
        // DataTables initialisation
        var table = $('#participants_table').DataTable({
            order: [[5, 'asc']],
            language: {
                paginate: {
                next: '&#187;', // or '→'
                previous: '&#171;' // or '←'
                }
            }
        });

        let alumni = 0;
        let special = 0;
        let regular = 0;
        let sponsored = 0;
        let early = 0;
        let count_alumni = 0;
        let count_special = 0;
        let count_regular = 0;
        let count_sponsored = 0;
        let count_early = 0;
        let min = null;
        let max = null;

        function initCounters(){
            sum = 0
            alumni = 0;
            special = 0;
            regular = 0;
            sponsored = 0;
            early = 0;
            count_alumni = 0;
            count_special = 0;
            count_regular = 0;
            count_sponsored = 0;
            count_early = 0;
        }






var minDate, maxDate;


// Custom filtering function which will search data in column four between two values
$.fn.dataTable.ext.search.push(

    function( settings, data, dataIndex ) {
        var min = minDate.val();
        var max = maxDate.val();
        var date = new Date( data[6] );
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

    let sort_events = []

    $('#participants_info').removeClass('d-none')

    // Create date inputs
    //console.log($('#min'))
    minDate = new DateTime($('#min'), {
        format: 'L'
    });
    //console.log('--min: '+minDate.val())
    maxDate = new DateTime($('#max'), {
        format: 'L'
    });


    $('#participants_table').on( 'search.dt', function () {
        selected_event = $('#select2-col1_filter-container').attr('title')
        if(selected_event.search('E-Learning') != -1){
            table.$('.participant_elearning').removeClass('none');
        }else{
            table.$('.participant_elearning').addClass('none');
        }
    } );


    //otan allazei selida kai einai E-learnign na energopoiounte oi antistoixes sthles
    $('#participants_table').on( 'page.dt', function () {
        selected_event = $('#select2-col1_filter-container').attr('title')

        if(selected_event.search('E-Learning') != -1){
            //$('.participant_elearning').removeClass('none')
            //$('#participants_table').DataTable().page(4).draw('page');

            table.$('.participant_elearning').removeClass('none');
        }else{
            table.$('.participant_elearning').addClass('none');
        }

    } );

    let eventIDS = table.column(9).data().unique()
    let events = table.column(1).data().unique();

    $.each(events, function(key, value) {
        eventsArray[removeSpecial(value)] = eventIDS[key]
    })


    events = table.column(1).data().unique().sort()
    coupons = table.column(4).data().unique().sort()
    paymentMethods = table.column(8).data().unique().sort()
    prices = table.column(3).data()

    let sum = 0
    $.each(prices, function(key, value) {
        value = value.replace("€", "")
        sum = sum + parseInt(value)
    })

    // var sum = prices.reduce(function(a, b){
    //     return parseInt(a) + parseInt(b);
    // }, 0);
    $('#total').text('€'+sum)

    //let sum = 0;

    $.each(events, function(key, value){
        let d = value.split(' / ')
        let DateParts = d[1].split("-")
        let t= new Date(+DateParts[2], DateParts[1] - 1, +DateParts[0]).setUTCHours(
      0,
      0,
      0,
      0
    )
    let date = new Date(t)

    let arr = []
    arr['name'] = value
    arr['date'] = date

    sort_events.push(arr)

    })

    sort_events.sort(function compare(a, b) {
        var dateA = new Date(a.date);
        var dateB = new Date(b.date);
        return dateA - dateB;
    });

    let length = parseInt(sort_events.length) -1

    $.each(sort_events, function(key, value){
        let data = sort_events[length];
        $('#col1_filter').append(`<option value="${sort_events[length].name}">${sort_events[length].name}</option>`)

        length = length - 1;
    })



    $.each(coupons, function(key, value){
        $('#col4_filter').append('<option value="'+value+'">'+value+'</option>')
    })

    $.each(paymentMethods, function(key, value){
        $('#col8_filter').append('<option value="'+value+'">'+value+'</option>')
    })

    //Refilter the table
    $('#min, #max').on('change', function () {
        //console.log('from change min!!')
        table.draw();
        //console.log(table.column(1).data())
        price = $('#participants_table').DataTable().column( 3 ).data();

        initCounters()

        min = new Date($('#min').val());
        max = new Date($('#max').val());

        minDate = new DateTime($('#min'), {
            format: 'L'
        });

        min = moment(min).format('MM/DD/YYYY')
        max = moment(max).format('MM/DD/YYYY')

        coupons = table.column(4,{filter: 'applied'}).data().unique().sort();
        $('#col4_filter').empty();
        $('#col4_filter').append('<option value>-- All --</option>')
        $.each(coupons, function(key, value){
            $('#col4_filter').append('<option value="'+value+'">'+value+'</option>')
        })

        paymentMethods = table.column(8,{filter: 'applied'}).data().unique().sort();
        $('#col8_filter').empty();
        $('#col8_filter').append('<option value>-- All --</option>')
        $.each(paymentMethods, function(key, value){
            $('#col8_filter').append('<option value="'+value+'">'+value+'</option>')
        })

        stats_non_elearning()


    });
});

    function filterGlobal () {
        $('#participants_table').DataTable().search(
            $('#global_filter').val()
        ).draw();

    }

    function removeSpecial(s){
        s = s.replace(/ /g,'');
        s = s.replace(/&/g,'');
        s = s.replace(/amp;/g,'');
        return s
    }

    function stats_non_elearning(){
        initCounters()

        let sum = 0
        //returns 'filtered' or visible rows
        table.rows({filter: 'applied'}).every( function ( rowIdx, tableLoop, rowLoop ) {
            var coupon = this.data()[2];
            var amount = this.data()[3];
            amount = parseInt(amount.replace("€",""))
            sum = sum + amount

            if(coupon == 'Alumni'){
                alumni = alumni + amount
                count_alumni++
            }else if(coupon == 'Regular'){
                regular = regular + amount
                count_regular++
            }else if(coupon == 'Special'){
                special = special + amount
                count_special++
            }else if(coupon == 'Sponsored'){
                sponsored = sponsored + amount
                count_sponsored++
            }else if(coupon == 'Early birds' || coupon == 'Early Bird'){
                //console.log('has early bird')
                early = early + amount
                count_early++
            }

        } );


        

        $('#total').text('€'+sum)
        $('#special').text('€'+special)
        $('#regular').text('€'+regular)
        $('#alumni').text('€'+alumni)
        $('#early').text('€'+early)
        $('#sponsored').text('€'+sponsored)
        $('#count_special').text(count_special)
        $('#count_regular').text(count_regular)
        $('#count_alumni').text(count_alumni)
        $('#count_early').text(count_early)
        $('#count_sponsored').text(count_sponsored)


    }


    function filterColumn ( i ) {
        
        if($('#col'+i+'_filter').val()){
            $('#participants_table').DataTable().column( i ).search(
                '^'+$('#col'+i+'_filter').val()+'$', true,true
            ).draw();
        }else{
            $('#participants_table').DataTable().column( i ).search(
                $('#col'+i+'_filter').val()
            ).draw();
        }
        

        $('.participants_info').remove()
        event = removeSpecial($('#col'+i+'_filter').val())

        if(event.search('E-Learning') != -1){

            if($('.participant_elearning').hasClass('none')){
                $('.participant_elearning').removeClass('none')
            }
        }else{
            $('.participant_elearning').addClass('none')
        }

       
        //console.log(removeSpecial($('#col'+i+'_filter').val()))
        stats_non_elearning()

        if(i!=4){
            coupons = table.column(4,{filter: 'applied'}).data().unique().sort();
            $('#col4_filter').empty();
            $('#col4_filter').append('<option value>-- All --</option>')
            $.each(coupons, function(key, value){
                $('#col4_filter').append('<option value="'+value+'">'+value+'</option>')
            })
        }

        

      
    }

    $(document).ready(function() {
        // $('#participants_table').DataTable({
        //     "destroy": true,
        // });


        price = $('#participants_table').DataTable().column( 3 ).data();
        let sum = 0;
        let alumni = 0;
        let special = 0;
        let regular = 0;
        let sponsored = 0;
        let early = 0;
        let count_alumni = 0;
        let count_special = 0;
        let count_regular = 0;
        let count_sponsored = 0;
        let count_early = 0;

            $.each(price, function(key, value){
                //console.log(value)
                value = value.replace("€", "")
            //console.log($('#participants_table').DataTable().column( i ).data()[key] == $('#col'+i+'_filter').val())
                //console.log('asd')
                //console.log($('#participants_table').DataTable().column( 3 ).data()[key])
                sum = sum + parseInt($('#participants_table').DataTable().column( 3 ).data()[key].replace("€", ""))

                if($('#participants_table').DataTable().column( 2 ).data()[key] == 'Alumni'){
                    alumni = alumni + parseInt(value)
                    count_alumni++
                }else if($('#participants_table').DataTable().column( 2 ).data()[key] == 'Regular'){
                    regular = regular + parseInt(value)
                    count_regular++
                }else if($('#participants_table').DataTable().column( 2 ).data()[key] == 'Special'){
                    special = special + parseInt(value)
                    count_special++
                }else if($('#participants_table').DataTable().column( 2 ).data()[key] == 'Sponsored'){
                    sponsored = sponsored + parseInt(value)
                    count_sponsored++
                }else if($('#participants_table').DataTable().column( 2 ).data()[key] == 'Early Bird' || 
                                $('#participants_table').DataTable().column( 2 ).data()[key] == 'Early birds'){
                    early = early + parseInt(value)
                    count_early++
                }



        })
        $('#total').text('€'+sum)
        $('#special').text('€'+special)
        $('#regular').text('€'+regular)
        $('#alumni').text('€'+alumni)
        $('#early').text('€'+early)
        $('#sponsored').text('€'+sponsored)
        $('#count_special').text(count_special)
        $('#count_regular').text(count_regular)
        $('#count_alumni').text(count_alumni)
        $('#count_early').text(count_early)
        $('#count_sponsored').text(count_sponsored)






        $('input.global_filter').on( 'keyup click', function () {
            filterGlobal();
        } );

        $('input.column_filter').on( 'keyup click', function () {
            filterColumn( $(this).parents('div').attr('data-column') );
        } );
    } );

    $('select.column_filter').on('change', function () {
        filterColumn( $(this).parents('div').attr('data-column') );
    });

    function ClearFields(){

        $('#min').val('')
        $('#max').val('')
        //$('.column_filter').val(1).trigger('change.select2');

        $('#col1_filter').val('');
        $('#col4_filter').val('');

        $('#col1_filter').select2().trigger('change');
        $('#col4_filter').select2().trigger('change');


        $('#participants_table').DataTable().column( 1 ).search('').draw();
        $('#participants_table').DataTable().column( 2 ).search('').draw();
        $('#participants_table').DataTable().column( 6 ).search('').draw();
        $('#participants_table').DataTable().column( 5 ).search('').draw();


    }


    </script>

    <script>

        $(document).ready(function(){

            $('#participants_table_filter').append(
                `<div class='excel-button'>
                        <button class="btn btn-icon btn-primary" type="button">
                        	<span class="btn-inner--icon"><i class="ni ni-cloud-download-95"></i></span>
                        </button>
                    </div>`
            )

        })


        $(document).on("click",".excel-button",function() {

            let min = $("#min").val();
            let max = $("#max").val();
            let event = eventsArray[removeSpecial($("#col1_filter").val())];


            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('transaction.export-excel')}}",
                type: "POST",
                data:{event:event,fromDate:min,toDate:max} ,
                success: function(data) {

                    window.location.href = '/tmp/exports/TransactionsExport.xlsx'

                }
            });

        });

    </script>


@endpush
