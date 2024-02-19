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
                <a class="btn btn-sm btn-neutral" data-toggle="collapse" href="#collapseFilters" role="button" aria-expanded="false" aria-controls="collapseFilters">{{ __('Filters') }}</a>

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

                    <div class="table-responsive py-4">
                      @include('admin.transaction.components.participants_filters')
                      {{$dataTable->table()}}
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
    <script type="text/javascript" src="{{cdn(mix('/js/panel_app.js'))}}"></script>

{{$dataTable->scripts()}}
<script>
  var _DATATABLE_OBJ = null;
  var elid = "{{$dataTable->getTableId()}}";
  $(document).ready(function() {
    $('#participants_info').removeClass('d-none');

    $(document).on('change', '.datatable-filters-form-custom select', function (e) {
      if (window.LaravelDataTables[elid]) {
        window.LaravelDataTables[elid].ajax.reload();
      }
    });
  });
</script>


<script>
  // It's a Kind of Magic...
  let minDate = null;
  let maxDate = moment().add(1, 'day').endOf('day').format('MM/DD/YYYY');
  let eventsArray = [];

  $(document).ready(function() {
    let table = window.LaravelDataTables[elid];

    $(document).on("click",".js-excel-button",function() {

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


    $(document).on("click",".js-invoice-button",function() {

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
  });

</script>

@endpush
