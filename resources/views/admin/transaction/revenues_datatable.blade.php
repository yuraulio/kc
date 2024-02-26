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
                <a class="btn btn-sm btn-neutral" data-toggle="collapse" href="#collapseFilters" role="button"
                   aria-expanded="false" aria-controls="collapseFilters">{{ __('Filters') }}</a>
            @endslot
            <li class="breadcrumb-item"><a href="{{ route('transaction.participants') }}">{{ __('Revenue List') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('List') }}</li>
        @endcomponent
        @include('admin.transaction.layouts.cards', ['type' => 'revenue'])
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
                  <div class="table-responsive py-4">
                    @include('admin.transaction.components.participants_filters', ['formId' => $dataTable->getTableId() . '-filters', 'type' => 'revenues'])
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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@push('js')

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>--}}

    <script src="{{ asset('argon') }}/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- <script src="{{ asset('argon') }}/vendor/datatables-datetime/datetime.min.js"></script> -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="{{cdn(mix('/js/panel_app.js'))}}"></script>

{{$dataTable->scripts()}}
<script>
  var _DATATABLE_OBJ = null;
  var elid = "{{$dataTable->getTableId()}}";
  const routes = {
    'participants_statistics': @json(route('api.v1.transactions.participants_statistics')),
  };

  function priceFormater(n, c = '€') {
    return c + ' ' + Number(n).toLocaleString();
  }

  function updateParticipantsStatistics(all = true) {
    ['.js-statistics-registrations-total', '.js-statistics-registrations-income', '.js-statistics-tickets-income'].forEach((x) => {
      var rootEl = $(x).first();
      rootEl.find('.js-statistics-body').hide();
      rootEl.find('.loader').show();
    })

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content'),
        'Authorization':  'Bearer ' + jQuery('meta[name="api-token"]').attr('content'),
      },
      url: routes.participants_statistics,
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify($.extend( {type: 'revenues'}, getFormData($('#'+elid+'-filters')) )) ,
      success: function(data) {
        if (all) {
          ((data) => {
            var rootEl = $('.js-statistics-registrations-total').first();
            rootEl.find('.js-total-users').text(data.total);
            rootEl.find('.js-total-users-in-class').text(data.in_class);
            rootEl.find('.js-total-users-elearning').text(data.elearning);
            rootEl.find('.loader').hide();
            rootEl.find('.js-statistics-body').show();
          })(data.users);
        }
        ((data) => {
          var rootEl = $('.js-statistics-registrations-income').first();
          rootEl.find('#total_income_by_type').text(priceFormater(data.total));
          rootEl.find('#incomeInclassAll').text(priceFormater(data.in_class));
          rootEl.find('#incomeElearningAll').text(priceFormater(data.elearning));
          rootEl.find('.loader').hide();
          rootEl.find('.js-statistics-body').show();
        })(data.income);
        ((data) => {
          var rootEl = $('.js-statistics-tickets-income').first();
          rootEl.find('#total_income').text(priceFormater(data.total));


          rootEl.find('#special').text(priceFormater(data.special ?? 0));
          rootEl.find('#regular').text(priceFormater(data.regular ?? 0));
          rootEl.find('#alumni').text(priceFormater(data.alumni ?? 0));
          rootEl.find('#early-bird').text(priceFormater(data.early_bird ?? 0));

          rootEl.find('.loader').hide();
          rootEl.find('.js-statistics-body').show();
        })(data.tickets);

      }
    });
  }

  $(document).ready(function() {
    $('#participants_info').removeClass('d-none');

    var dr = $('#filter_daterange');
    dr.daterangepicker();
    dr.val('')

    function updateDataTable() {
      if (window.LaravelDataTables[elid]) {
        window.LaravelDataTables[elid].ajax.reload();
      }
      updateParticipantsStatistics(false);
    }
    dr.on('change', updateDataTable);

    $(document).on('change', '#' + elid + '-filters select', updateDataTable);
    $(document).on('change', '#' + elid + '-filters #filter_event', function() {
      let v = $(this).val();
      let visible = false;
      if(v.search('E-Learning') !== -1) {
        visible = true;
      }
      manageColumns(visible);
    });
    updateParticipantsStatistics();
  });

  function getOldFilters() {
    const filters = getFormData($('#'+ elid + '-filters')).filter,
      data = {
        ...filters
      };
    var min = 0,
      max = 0;
    if (data.daterange) {
      [min, max] = data.daterange.split(' - ');
    }
    data.fromDate = min;
    data.toDate = max;
    return data;
  }
</script>

@endpush
