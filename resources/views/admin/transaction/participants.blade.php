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

            <li class="breadcrumb-item"><a href="{{ route('transaction.participants') }}">{{ __('Registrations List') }}</a></li>
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
                                <h3 class="mb-0">{{ __('Registration') }}</h3>
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


                            <div class="col-sm-3 filter_col" id="filter_col11" data-column="11">
                                <label>Delivery</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col11_filter" placeholder="Payment Method">
                                <option selected value> -- All -- </option>
                                </select>
                            </div>

                            <div class="col-sm-3 filter_col d-none" id="filter_col12" data-column="12">
                                <label>City</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col12_filter" placeholder="City">
                                <option selected value> -- All -- </option>
                                </select>
                            </div>

                            <div class="col-sm-3 filter_col" id="filter_col13" data-column="13">
                                <label>Category</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col13_filter" placeholder="Category">
                                <option selected value> -- All -- </option>
                                </select>
                            </div>


                            <div class="col-sm-3 filter_col">
                                <div class="form-group">
                                    <label>From - To</label>
                                    <input class="select2-css" type="text" name="daterange">
                                </div>
                            </div>
                            <!-- <div class="col-sm-3 filter_col">
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
                            </div> -->
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
                            <th hidden>{{ __('Transaction ID') }}</th>
                            <th hidden>{{ __('Delivery') }}</th>
                            <th>{{__('City')}}</th>
                            <th>{{ __('Category') }}</th>
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
                            <th hidden>{{ __('Transaction ID') }}</th>
                            <th hidden>{{ __('Delivery') }}</th>
                            <th>{{ __('City') }}</th>
                            <th>{{ __('Category') }}</th>
                            {{--<th class="participant_elearning none">{{ __('New Expiration Date') }}</th>--}}
                        </tr>
                    </tfoot>
                    <tbody>
                    <?php //dd($transactions); ?>
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
                                <td hidden>{{$transaction['id']}}</td>
                                <td hidden>{{$transaction['is_elearning'] ? 'e-learning' : 'in-class'}}</td>
                                <td>{{ $transaction['city'] }}</td>
                                <td>{{ $transaction['category'] }}</td>
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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
       var eventsArray = {};
        // DataTables initialisation
        var table = $('#participants_table').DataTable({
            order: [[6, 'desc']],
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
        let countRegularNew = {};
        let countValueRegularNew = {};
        let newTickets = {};

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
            countRegularNew = {};
            countValueRegularNew = {};
            newTickets = {};
        }






let minDate = null, maxDate = null;


// Custom filtering function which will search data in column four between two values
$.fn.dataTable.ext.search.push(

    function( settings, data, dataIndex ) {
        var min = new Date(minDate).setHours(0, 0, 0, 0);
        var max = new Date(maxDate).setHours(0, 0, 0, 0);
        var date = new Date( data[6] ).setHours(0, 0, 0, 0);
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

    $('input[name="daterange"]').daterangepicker();

    $('input[name="daterange"]').val('')

    let sort_events = []

    $('#participants_info').removeClass('d-none')

    // Create date inputs
    //console.log($('#min'))
    minDate = null;
    // //console.log('--min: '+minDate.val())
    maxDate = moment().add(1, 'day').endOf('day').format('MM/DD/YYYY')

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

    let eventIDS = table.column(9,{filter: 'applied'}).data().unique()
    let events = table.column(1,{filter: 'applied'}).data().unique();

    $.each(events, function(key, value) {
        eventsArray[removeSpecial(value)] = eventIDS[key]
    })


    events = table.column(1).data().unique().sort()
    coupons = table.column(4).data().unique().sort()
    paymentMethods = table.column(8).data().unique().sort()
    delivery = table.column(11).data().unique().sort()
    city = table.column(12).data().unique().sort()
    category = table.column(13).data().unique().sort()

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

    $.each(delivery, function(key, value){
        $('#col11_filter').append('<option value="'+value+'">'+value+'</option>')
    })

    $.each(city, function(key, value){
        $('#col12_filter').append('<option value="'+value+'">'+value+'</option>')
    })

    $.each(category, function(key, value){
        $('#col13_filter').append('<option>'+value+'</option>')
    })


    $('#col11_filter').on('select2:select', function (e) {


        if($(this).val() == 'in-class'){
            $('#filter_col12').removeClass('d-none');
        }else{
            $('#col12_filter').val("")
            $('#col12_filter').select2().trigger('change');

            $('#filter_col12').addClass('d-none');

        }
    });

    //Refilter the table
    $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {

        price = $('#participants_table').DataTable().column( 3 ).data();
        initCounters()

        min = picker.startDate.format('MM/DD/YYYY')
        max = picker.endDate.format('MM/DD/YYYY')

        minDate = min;
        maxDate = max;
        table.draw();

        coupons = table.column(4,{filter: 'applied'}).data().unique().sort();
        $('#col4_filter').empty();
        $('#col4_filter').append('<option value>-- All --</option>')
        $.each(coupons, function(key, value){
            $('#col4_filter').append('<option value="'+value+'">'+value+'</option>')
        })

        paymentMethods = table.column(8,{filter: 'applied'}).data().unique().sort();
        delivery = table.column(11,{filter: 'applied'}).data().unique().sort();

        $('#col8_filter').empty();
        $('#col8_filter').append('<option value>-- All --</option>')
        $.each(paymentMethods, function(key, value){
            $('#col8_filter').append('<option value="'+value+'">'+value+'</option>')
        })

        /*$('#col11_filter').empty();
        $('#col11_filter').append('<option value>-- All --</option>')
        $.each(delivery, function(key, value){
            $('#col11_filter').append('<option value="'+value+'">'+value+'</option>')
        })*/

        let eventIDS = table.column(9,{filter: 'applied'}).data().unique()
        let events = table.column(1,{filter: 'applied'}).data().unique();
        eventsArray ={};
         $.each(events, function(key, value) {
            eventsArray[removeSpecial(value)] = eventIDS[key]
        })
        stats_non_elearning()

    });

    $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
        $('input[name="daterange"]').val('');

        initCounters();
        minDate = null;
        maxDate = moment().endOf('day').format('MM/DD/YYYY');

        table.draw()
        stats_non_elearning();

        let eventIDS = table.column(9,{filter: 'applied'}).data().unique()
        let events = table.column(1,{filter: 'applied'}).data().unique();
        eventsArray ={};
         $.each(events, function(key, value) {
            eventsArray[removeSpecial(value)] = eventIDS[key]
        })

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


        $(`.ticket-choices`).empty();


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

                if(!newTickets[coupon]){
                    newTickets[coupon] = {};
                    newTickets[coupon]['all'] = {};
                    newTickets[coupon]['all']['countValue'] = 0;
                    newTickets[coupon]['all']['count'] = 0;

                }
                if(!newTickets[coupon][amount]){
                    newTickets[coupon][amount] = {};
                    newTickets[coupon][amount]['countValue'] = 0;
                    newTickets[coupon][amount]['count'] = 0;

                }

                newTickets[coupon]['all']['countValue'] += parseInt(amount);
                newTickets[coupon]['all']['count']++;
                newTickets[coupon][amount]['countValue'] += parseInt(amount);
                newTickets[coupon][amount]['count']++;

            }else if(coupon == 'Regular'){
                regular = regular + amount
                count_regular++

                if(!newTickets[coupon]){
                    newTickets[coupon] = {};
                    newTickets[coupon]['all'] = {};
                    newTickets[coupon]['all']['countValue'] = 0;
                    newTickets[coupon]['all']['count'] = 0;

                }
                if(!newTickets[coupon][amount]){
                    newTickets[coupon][amount] = {};
                    newTickets[coupon][amount]['countValue'] = 0;
                    newTickets[coupon][amount]['count'] = 0;

                }

                newTickets[coupon]['all']['countValue'] += parseInt(amount);
                newTickets[coupon]['all']['count']++;
                newTickets[coupon][amount]['countValue'] += parseInt(amount);
                newTickets[coupon][amount]['count']++;

            }else if(coupon == 'Special'){
                special = special + amount
                count_special++

                if(!newTickets[coupon]){
                    newTickets[coupon] = {};
                    newTickets[coupon]['all'] = {};
                    newTickets[coupon]['all']['countValue'] = 0;
                    newTickets[coupon]['all']['count'] = 0;

                }
                if(!newTickets[coupon][amount]){
                    newTickets[coupon][amount] = {};
                    newTickets[coupon][amount]['countValue'] = 0;
                    newTickets[coupon][amount]['count'] = 0;

                }

                newTickets[coupon]['all']['countValue'] += parseInt(amount);
                newTickets[coupon]['all']['count']++;
                newTickets[coupon][amount]['countValue'] += parseInt(amount);
                newTickets[coupon][amount]['count']++;

            }else if(coupon == 'Sponsored'){
                sponsored = sponsored + amount
                count_sponsored++

                if(!newTickets[coupon]){
                    newTickets[coupon] = {};
                    newTickets[coupon]['all'] = {};
                    newTickets[coupon]['all']['countValue'] = 0;
                    newTickets[coupon]['all']['count'] = 0;

                }
                if(!newTickets[coupon][amount]){
                    newTickets[coupon][amount] = {};
                    newTickets[coupon][amount]['countValue'] = 0;
                    newTickets[coupon][amount]['count'] = 0;

                }

                newTickets[coupon]['all']['countValue'] += parseInt(amount);
                newTickets[coupon]['all']['count']++;
                newTickets[coupon][amount]['countValue'] += parseInt(amount);
                newTickets[coupon][amount]['count']++;

            }else if(coupon == 'Early birds' || coupon == 'Early Bird'){
                //console.log('has early bird')
                early = early + amount
                count_early++

                if(!newTickets[coupon]){
                    newTickets[coupon] = {};
                    newTickets[coupon]['all'] = {};
                    newTickets[coupon]['all']['countValue'] = 0;
                    newTickets[coupon]['all']['count'] = 0;

                }
                if(!newTickets[coupon][amount]){
                    newTickets[coupon][amount] = {};
                    newTickets[coupon][amount]['countValue'] = 0;
                    newTickets[coupon][amount]['count'] = 0;

                }

                newTickets[coupon]['all']['countValue'] += parseInt(amount);
                newTickets[coupon]['all']['count']++;
                newTickets[coupon][amount]['countValue'] += parseInt(amount);
                newTickets[coupon][amount]['count']++;

            }

        });


        $('#total').text('€'+sum.toLocaleString())
        $('#special').text('€'+special.toLocaleString())
        $('#regular').text('€'+regular.toLocaleString())
        $('#alumni').text('€'+alumni.toLocaleString())
        $('#early-bird').text('€'+early.toLocaleString())
        $('#sponsored').text('€'+sponsored.toLocaleString())

        $('#count_special').text('Special(all): '+count_special)
        $('#count_regular').text('Regular(all): '+count_regular)
        $('#count_alumni').text('Alumni(all): '+count_alumni)
        $('#count_early-bird').text('Early Bird(all): '+count_early)
        $('#count_sponsored').text(+count_sponsored)

        $.each( newTickets, function( key, value ) {

          actionValue = key.toLowerCase()
          html = ``;
          $.each( value, function( key1, value1 ) {

            if(value1['countValue'] <= 0){
                return;
            }

            html += `<a class="dropdown-item ticket-action" data-type='${key}' data-ticket=${key1} href="javascript:void(0)">${key1}</a>`
          });
          actionValue = actionValue.replace(/ /g, '-');
          $(`.${actionValue}-action`).append(html)

      });




    }


    function filterColumn ( i ) {

        if($('#col'+i+'_filter').val() && i != 8){
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

        let eventIDS = table.column(9,{filter: 'applied'}).data().unique()
        let events = table.column(1,{filter: 'applied'}).data().unique();

        eventsArray ={};
         $.each(events, function(key, value) {
            eventsArray[removeSpecial(value)] = eventIDS[key]
        })


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
        let countRegularNew = {};
        let countValueRegularNew = {};
        let count_sponsored = 0;
        let count_early = 0;

        newTickets = {};

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

                    if(!newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]){
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]] = {};
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all'] = {};
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all']['countValue'] = 0;
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all']['count'] = 0;

                    }
                    if(!newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]){
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)] = {};
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]['countValue'] = 0;
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]['count'] = 0;

                    }

                    newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all']['countValue'] += parseInt(value);
                    newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all']['count']++;
                    newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]['countValue'] += parseInt(value);
                    newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]['count']++;

                }else if($('#participants_table').DataTable().column( 2 ).data()[key] == 'Regular'){

                    regular = regular + parseInt(value)
                    count_regular++

                    if(!newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]){
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]] = {};
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all'] = {};
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all']['countValue'] = 0;
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all']['count'] = 0;

                    }
                    if(!newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]){
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)] = {};
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]['countValue'] = 0;
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]['count'] = 0;

                    }

                    newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all']['countValue'] += parseInt(value);
                    newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all']['count']++;
                    newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]['countValue'] += parseInt(value);
                    newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]['count']++;

                    /*if(!countValueRegularNew[value]){
                        countValueRegularNew[value] = 0;
                        countRegularNew[value] = 0;
                    }
                    countValueRegularNew[value] += parseInt(value);
                    countRegularNew[value]++;*/

                }else if($('#participants_table').DataTable().column( 2 ).data()[key] == 'Special'){
                    special = special + parseInt(value)
                    count_special++


                    if(!newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]){
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]] = {};
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all'] = {};
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all']['countValue'] = 0;
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all']['count'] = 0;

                    }
                    if(!newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]){
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)] = {};
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]['countValue'] = 0;
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]['count'] = 0;

                    }

                    newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all']['countValue'] += parseInt(value);
                    newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all']['count']++;
                    newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]['countValue'] += parseInt(value);
                    newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]['count']++;


                }else if($('#participants_table').DataTable().column( 2 ).data()[key] == 'Sponsored'){
                    sponsored = sponsored + parseInt(value)
                    count_sponsored++


                    if(!newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]){
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]] = {};
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all'] = {};
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all']['countValue'] = 0;
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all']['count'] = 0;

                    }
                    if(!newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]){
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)] = {};
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]['countValue'] = 0;
                        newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]['count'] = 0;

                    }

                    newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all']['countValue'] += parseInt(value);
                    newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]]['all']['count']++;
                    newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]['countValue'] += parseInt(value);
                    newTickets[$('#participants_table').DataTable().column( 2 ).data()[key]][parseInt(value)]['count']++;


                }else if($('#participants_table').DataTable().column( 2 ).data()[key] == 'Early Bird' ||
                                $('#participants_table').DataTable().column( 2 ).data()[key] == 'Early birds'){
                    early = early + parseInt(value)
                    count_early++

                    if(!newTickets['early-bird']){
                        newTickets['early-bird'] = {};
                        newTickets['early-bird']['all'] = {};
                        newTickets['early-bird']['all']['countValue'] = 0;
                        newTickets['early-bird']['all']['count'] = 0;

                    }
                    if(!newTickets['early-bird'][parseInt(value)]){
                        newTickets['early-bird'][parseInt(value)] = {};
                        newTickets['early-bird'][parseInt(value)]['countValue'] = 0;
                        newTickets['early-bird'][parseInt(value)]['count'] = 0;

                    }

                    newTickets['early-bird']['all']['countValue'] += parseInt(value);
                    newTickets['early-bird']['all']['count']++;
                    newTickets['early-bird'][parseInt(value)]['countValue'] += parseInt(value);
                    newTickets['early-bird'][parseInt(value)]['count']++;

                }



        })

        console.log('sum = ', sum)
        console.log('special = ', special)
        console.log('regular = ', regular)
        console.log('alumni = ', alumni)


        console.log('count special = ', count_special)
        console.log('count regular = ', count_regular)
        console.log('count alumni = ', count_alumni)

        $('#total').text('€'+sum.toLocaleString())
        $('#special').text('€'+special.toLocaleString())
        $('#regular').text('€'+regular.toLocaleString())
        $('#alumni').text('€'+alumni.toLocaleString())
        $('#early-bird').text('€'+early.toLocaleString())
        $('#sponsored').text('€'+sponsored.toLocaleString())
        $('#count_special').text('Special(all): '+count_special.toLocaleString())
        $('#count_regular').text('Regular(all): '+count_regular.toLocaleString())
        $('#count_alumni').text('Alumni(all): '+count_alumni.toLocaleString())
        $('#count_early-bird').text('Early Bird(all): '+count_early.toLocaleString())
        $('#count_sponsored').text(+count_sponsored.toLocaleString())


        $.each( newTickets, function( key, value ) {

            actionValue = key.toLowerCase()
            html = ``;


            $.each( value, function( key1, value1 ) {

                if(value1['countValue'] <= 0){
                    return;
                }

                html += `<a class="dropdown-item ticket-action" data-type=${key} data-ticket=${key1} href="javascript:void(0)">${key1}</a>`



            });

            //sortKeys = Object.keys(value);
            //sortKeys.sort();
            /*console.log(sortKeys.sort())

            $.each( sortKeys, function( key1, value1 ) {

                if(value1 <= 0){
                    return;
                }

                html += `<a class="dropdown-item ticket-action" data-type=${key} data-ticket=${value1} href="javascript:void(0)">${value1}</a>`



            });*/

            actionValue = actionValue.replace(/ /g, '-');
            $(`.${actionValue}-action`).append(html)

        });


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
                        <button title="export transactions to csv" class="btn btn-icon btn-primary" type="button">
                        	<span class="btn-inner--icon"><i class="ni ni-cloud-download-95"></i></span>
                        </button>
                    </div>
                    <div class='invoice-button'>
                        <button title="download invoices" class="btn btn-icon btn-primary" type="button">
                        	<span class="btn-inner--icon"><i class="ni ni-folder-17"></i></span>
                        </button>
                    </div>
                    `
            )

        })


        $(document).on("click",".excel-button",function() {

            let min = minDate;
            let max = maxDate;
            let city = $('#col12_filter').val()
            let category = $('#col13_filter').val()
            let delivery = $('#col11_filter').val()

            //let event = eventsArray[removeSpecial($("#col1_filter").val())];

            let event = [];
            $.each(eventsArray, function(key, value) {
                event.push(value);
            })

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('transaction.export-excel')}}",
                type: "POST",
                data:{event:event,fromDate:min,toDate:max, city: city, category: category, delivery: delivery} ,
                success: function(data) {

                    window.location.href = '/tmp/exports/TransactionsExport.xlsx'

                }
            });

        });


        $(document).on("click",".invoice-button",function() {

            let city = $('#col12_filter').val()
            let category = $('#col13_filter').val()
            let delivery = $('#col11_filter').val()

            let transactionsData = table.column(10,{filter: 'applied'}).data().unique().sort();
            let transactions = [];
            $.each(transactionsData, function(key, value){
                transactions.push(value)
            })

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('transaction.export-invoice')}}",
                type: "POST",
                data:{transactions:transactions, city: city, delivery: delivery, category: category} ,
                success: function(data) {
                    window.location.href = data.zip
                }
            });

        });

    </script>


@endpush
