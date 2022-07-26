@extends('layouts.app', [
'title' => __('Subscription Management'),
'parentSection' => 'laravel',
'elementName' => 'subscriptions-management'
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
<li class="breadcrumb-item"><a href="{{ route('subscriptions.index') }}">{{ __('Subscriptions List') }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('List') }}</li>
@endcomponent
@include('admin.subscription.layouts.cards')
@endcomponent
<div class="container-fluid mt--6">
   <div class="row">
      <div class="col">
         <div class="card">
            <div class="card-header">
               <div class="row align-items-center">
                  <div class="col-8">
                     <h3 class="mb-0">{{ __('Subscriptions') }}</h3>
                  </div>
               </div>
            </div>
            <div class="col-12 mt-2">
               @include('alerts.success')
               @include('alerts.errors')
            </div>
            <div class="table-responsive py-4">
               <div class="collapse" id="collapseExample">
                  <div class="container">
                     <div class="row">
                        <div class="col" id="filter_col1" data-column="2">
                           <label>Event</label>
                           <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="event" class="column_filter" id="col2_filter">
                              <option selected value> -- All -- </option>
                           </select>
                        </div>
                        <div class="col" id="filter_col3" data-column="3">
                           <label>Status</label>
                           <select data-toggle="select" data-live-search="true" class="column_filter" id="col3_filter" placeholder="Status">

                                <option selected value> -- All -- </option>
                                <option value="trialing"> Trialing </option>
                                <option value="active"> Paid and Active </option>
                                <option value="paid_and_cancelled"> Paid and Cancelled </option>
                                <option value="cancelled"> Cancelled </option>

                           </select>
                        </div>
                        <div id="sub_datePicker">
                            <div class="col">
                                <label>From - To</label>
                                <div class="form-group">
                                 <div class="input-group">
                                    <div class="input-group-prepend">
                                       <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                    </div>

                                    <input class="form-control select2-css" type="text" name="daterange" value="" />
                                 </div>
                              </div>
                            </div>

                        </div>
                        <div class="col-sm-3 filter_col" id="filter_col4" data-column="4">
                            <label>Delivery</label>
                            <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col4_filter" placeholder="Delivery">
                            <option selected value> -- All -- </option>
                            </select>
                        </div>

                        <!-- <div id="sub_datePicker" class="input-daterange datepicker">
                           <div class="col">
                              <label>From</label>
                              <div class="form-group">
                                 <div class="input-group">
                                    <div class="input-group-prepend">
                                       <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                    </div>
                                    <input class="form-control select2-css" id="min" placeholder="Start date" type="text" value="">
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <label>To</label>
                              <div class="form-group">
                                 <div class="input-group">
                                    <div class="input-group-prepend">
                                       <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                    </div>
                                    <input class="form-control select2-css" placeholder="End date" id="max" type="text" value="">
                                 </div>
                              </div>
                           </div>
                        </div> -->
                     </div>
                  </div>
               </div>

               <table class="table align-items-center table-flush"  id="subscriptions_table">
                  <thead class="thead-light">
                     <tr>
                        <th scope="col">{{ __('Id') }}</th>
                        <th scope="col">{{ __('Student') }}</th>
                        {{--<th scope="col">{{ __('Plan') }}</th>--}}
                        <th scope="col">{{ __('Event Name') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        {{--
                        <th scope="col">{{ __('Trials Sub end at') }}</th>
                        --}}
                        <th scope="col">{{ __('Delivery') }}</th>
                        <th scope="col">{{ __('Sub end at') }}</th>
                        <th scope="col">{{ __('Amount') }}</th>
                        <th class="" scope="col">{{ __('Create Date') }}</th>
                     </tr>
                  </thead>
                  <tfoot>
                     <tr>
                     <th scope="col">{{ __('Id') }}</th>
                        <th scope="col">{{ __('Student') }}</th>
                        {{--<th scope="col">{{ __('Plan') }}</th>--}}
                        <th scope="col">{{ __('Event Name') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        {{--
                        <th scope="col">{{ __('Trials Sub end at') }}</th>
                        --}}
                        <th scope="col">{{ __('Delivery') }}</th>
                        <th scope="col">{{ __('Sub end at') }}</th>
                        <th scope="col">{{ __('Amount') }}</th>
                        <th class="" scope="col">{{ __('Create Date') }}</th>

                     </tr>
                  </tfoot>
                  <tbody>
                     @foreach ($subscriptions as $item)

                     <tr>
                        <td>
                           {{ $item['id'] }}
                        </td>
                        <td>
                           <a href="{{ route('user.edit', $item['user_id']) }}">{{ $item['user'] }}</a>
                        </td>
                        {{--<td>{{$item['plan_name']}}</td>--}}
                        <td>{{ $item['event_title'] }}</td>
                        <td>{{ $item['status'] }}</td>
                        <td>{{ (isset($item['delivery'])) ? $item['delivery']['name'] : ''}}</td>
                        <td>{{ $item['ends_at'] }}</td>
                        <td>{{ $item['amount'] }}</td>
                        <td class="">{{ $item['created_at'] }}</td>
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
<!-- <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables-datetime/datetime.min.css"> -->
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
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('argon') }}/vendor/datatables-datetime/datetime.min.js"></script> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
   let minDate = null, maxDate = null;
   let transactionArray = [];
   let delivery = [];

   $.fn.dataTable.ext.search.push(
       function( settings, data, dataIndex ) {
           var min = minDate;
           var max = maxDate;

            if(min != null){
                min = moment(min).format('YYYY/MM/DD')
            }

            if(max != null){
                max = moment(max).format('YYYY/MM/DD')
            }

           var date = new Date(data[7]);
           date = moment(date).format('YYYY/MM/DD')



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



    function initStats(){
        transactionArray = [];
        let sum = 0;
        let trial = 0;
        let cancelled = 0 ;
        let inclass = 0;
        let elearning = 0;
        //returns 'filtered' or visible rows
        table.rows({filter: 'applied'}).every( function ( rowIdx, tableLoop, rowLoop ) {
            var statuss = this.data()[3];
            var amount = this.data()[6];
            var delivery = this.data()[4]
            amount = parseInt(amount.replace("€",""))

            if( $("#col3_filter").val() !== 'cancelled' &&  $("#filter_col3").val() !== 'trialing' ){

                sum = sum + amount
            }



            if(statuss == 'trialing'){
                trial += 1
            }else if(statuss == 'cancelled'){
                cancelled += 1;
            }

            if(delivery == ("Video e-learning courses" || "Live webinar courses")){
                elearning += 1;
            }else if( delivery == "In-class courses"){
                inclass += 1;
            }



            transactionArray.push(this.data()[0]);
        } );


        $('#total').text('€'+sum)
        $('#cancel').text(cancelled)
        $('#trial').text(trial)
        $('#inclass').text(inclass)
        $('#e-learning').text(elearning)

    }

    function fillSelectedBox(){
        events = table.column(2).data().unique().sort()
        $.each(events, function(key, value){
            $('#col2_filter').append('<option value="'+value+'">'+value+'</option>')
        })

        delivery = table.column(4).data().unique().sort()
        $.each(delivery, function(key, value){
            $('#col4_filter').append('<option value="'+value+'">'+value+'</option>')
        })
    }

    function filterColumn ( i ) {
        if(i == 3){ //Status filter
            if($('#col'+i+'_filter').val() != ''){
                status = $('#col'+i+'_filter').val()

            }else{
                status = ''
            }
            value = status.trim()

        }else if(i == 2){ // Event filter
            if($('#col'+i+'_filter').val() != ''){
                value = $('#col'+i+'_filter').val()
            }else{
                value = ''
            }

        }else if(i == 4){
            if($('#col'+i+'_filter').val() != ''){
                value = $('#col'+i+'_filter').val()
            }else{
                value = ''
            }
        }

        if(value.trim()){
         table.column( i ).search("^" + value.trim() + "$", true, false, true).draw()
        }else{
         table.column( i ).search(value.trim()).draw();
        }


        initStats();

    }


    // DataTables initialisation
    var table = $('#subscriptions_table').DataTable({
        "order": [[ 7, "desc" ]],
        language: {
            paginate: {
            next: '&#187;', // or '→'
            previous: '&#171;' // or '←'
            }
        }
    });
    initStats()

    let status = '';

    $(document).ready(function() {

        $('input[name="daterange"]').daterangepicker();
        $('input[name="daterange"]').val('')

        minDate = null;
        maxDate = moment().endOf('day').format('MM/DD/YYYY');



        $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
            $('input[name="daterange"]').val('');

            minDate = null;
            maxDate = moment().endOf('day').format('MM/DD/YYYY');

            table.draw();
            initStats();
        });

        $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
            minDate = picker.startDate.format('MM/DD/YYYY')
            maxDate = picker.endDate.format('MM/DD/YYYY')

            table.draw();
            initStats();
        });

        fillSelectedBox()

        $('select.column_filter').on('change', function () {
            filterColumn( $(this).parents('div').attr('data-column') );
        } );


    });



</script>
<script>
    $(document).ready(function(){


        $('#subscriptions_table_filter').append(
            `<div class='excel-button'>
                    <button title="export transactions to csv" class="btn btn-icon btn-primary" type="button">
                        <span class="btn-inner--icon"><i class="ni ni-cloud-download-95"></i></span>
                    </button>
                </div>
                `
        )



    })

    $(document).on("click",".excel-button",function() {

        let min = minDate;
        let max = maxDate;

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{route('subscription.export-excel')}}",
            type: "POST",
            data:{transaction:transactionArray,fromDate:min,toDate:max} ,
            success: function(data) {

                window.location.href = '/tmp/exports/SubscriptionsExport.xlsx'

            }
        });

    });
</script>
@endpush

