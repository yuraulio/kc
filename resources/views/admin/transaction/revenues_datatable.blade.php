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
  PanelApp.setRoutes(@json(['participants_statistics' => route('api.v1.transactions.participants_statistics')]));

  function updateParticipantsStatistics(all = true) {
    ['.js-statistics-registrations-total', '.js-statistics-registrations-income', '.js-statistics-tickets-income'].forEach((x) => {
      var rootEl = $(x).first();
      rootEl.find('.js-statistics-body').hide();
      rootEl.find('.loader').show();
    })
    PanelApp.api.post(
      'participants_statistics',
      $.extend( {type: 'revenues'}, getFormData($('#'+elid+'-filters')) ),
      function(data) {
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
          rootEl.find('#total_income_by_type').text(PanelApp.formater.price(data.total));
          rootEl.find('#incomeInclassAll').text(PanelApp.formater.price(data.in_class));
          rootEl.find('#incomeElearningAll').text(PanelApp.formater.price(data.elearning));
          rootEl.find('.loader').hide();
          rootEl.find('.js-statistics-body').show();
        })(data.income);
        ((data) => {
          var rootEl = $('.js-statistics-tickets-income').first();
          rootEl.find('#total_income').text(PanelApp.formater.price(data.total));
          rootEl.find('#special').text(PanelApp.formater.price(data.special ?? 0));
          rootEl.find('#regular').text(PanelApp.formater.price(data.regular ?? 0));
          rootEl.find('#alumni').text(PanelApp.formater.price(data.alumni ?? 0));
          rootEl.find('#early-bird').text(PanelApp.formater.price(data.early_bird ?? 0));

          rootEl.find('.loader').hide();
          rootEl.find('.js-statistics-body').show();
        })(data.tickets);
        ((data) => {
          var rootEl = $('.js-statistics-revenues').first();
          rootEl.find('#incomeTotal').text(PanelApp.formater.price(data.total ?? 0));
          rootEl.find('#incomeInclass').text(PanelApp.formater.price(data.in_class ?? 0));
          rootEl.find('#incomeElearning').text(PanelApp.formater.price(data.elearning ?? 0));
          rootEl.find('.loader').hide();
          rootEl.find('.js-statistics-body').show();
        })(data.income_accurate);
      }
    )
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
