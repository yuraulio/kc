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
                      @include('admin.transaction.components.participants_filters', ['formId' => $dataTable->getTableId() . '-filters', 'type' => 'participants'])
                      {{$dataTable->table()}}
                    </div>
              </div>
          </div>
      </div>
      @include('layouts.footers.auth')
    </div>
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{cdn(mix('/css/argon_vendors.css'))}}" />
    <link rel="stylesheet" type="text/css" href="{{cdn(mix('/css/panel_app.css'))}}" />
@endpush

@push('js')
    <script type="text/javascript" src="{{cdn(mix('/js/panel_app.js'))}}"></script>
<script type="text/javascript" src="{{cdn(mix('/js/argon_vendors.js'))}}"></script>

{{$dataTable->scripts()}}
<script>
  var _DATATABLE_OBJ = null;
  var elid = "{{$dataTable->getTableId()}}";
  const routes = {
    'participants_statistics': @json(route('api.v1.transactions.participants_statistics')),
  };

  function manageColumns(visible) {
    let datatable = window.LaravelDataTables[elid];
    if (!datatable) {
      return;
    }
    /**
     * old version of library, names not supported
     * datatable.columns().names()
     */
    const fields = {
      videos_seen: 5,
      expiration: 7,
    }
    Object.keys(fields).map(x => {
      let index = fields[x];
      datatable.column(index).visible(visible);
    })
  }

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
      data: JSON.stringify($.extend( {}, getFormData($('#'+elid+'-filters')) )) ,
      success: function(data) {
        ((data) => {
          var rootEl = $('.js-statistics-registrations-total').first();
          if (all) {
            rootEl.find('.js-total-users').text(data.total);
            rootEl.find('.js-total-users-in-class').text(data.in_class);
            rootEl.find('.js-total-users-elearning').text(data.elearning);
          }
          rootEl.find('.loader').hide();
          rootEl.find('.js-statistics-body').show();
        })(data.users);
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
    manageColumns(false);

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


<script>
  // It's a Kind of Magic...
  $(document).ready(function() {
    let table = window.LaravelDataTables[elid];

    $(document).on("click",".js-excel-button",function() {

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        url: "{{route('transaction.export-excel')}}",
        type: "POST",
        data: getOldFilters(),
        success: function(data) {

          window.location.href = '/tmp/exports/TransactionsExport.xlsx'

        }
      });

    });


    $(document).on("click",".js-invoice-button",function() {

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        url: "{{route('transaction.export-invoice')}}",
        type: "POST",
        data: getFormData($('#'+ elid + '-filters')),
        success: function(data) {
          window.location.href = data.zip
        },
        error: function(jqXHR, exception) {
          if (jqXHR.status === 401) {
            alert('You don\'t have access to this functionality');
          }
        }
      });

    });
  });

</script>

@endpush
