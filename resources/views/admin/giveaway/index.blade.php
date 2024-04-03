@extends('layouts.app', [
    'title' => __('Giveaway list'),
    'parentSection' => 'laravel',
    'elementName' => 'giveaways-management'
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

            <li class="breadcrumb-item"><a href="{{ route('giveaway.index') }}">{{ __('Giveaways Management') }}</a></li>
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
                                <h3 class="mb-0">{{ __('Giveaways') }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>




                    <div class="table-responsive py-4">
                        <div class="collapse " id="collapseFilters">
                          <div class="container">
                            <div id="sub_datePicker">
                              <div class="col">
                                <label>From-To</label>
                                <div class="form-group">
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                    </div>

                                    <input class="form-control select2-css" type="text" name="daterange" autocomplete="off" value="" />
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <table class="table align-items-center table-flush"  id="datatable-basic2">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('First name') }}</th>
                                    <th scope="col">{{ __('Last name') }}</th>
                                    <th scope="col">{{ __('Email') }}</th>
                                    <th scope="col">{{ __('Phone') }}</th>
                                    <th scope="col">{{ __('Company') }}</th>
                                    <th scope="col">{{ __('Position') }}</th>
                                    <th scope="col">{{ __('Created at') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($giveaways as $giveaway)
                                    <tr>
                                      <td>{{ $giveaway->firstname }}</td>
                                      <td>{{ $giveaway->lastname }}</td>
                                      <td>{{ $giveaway->email }}</td>
                                      <td>{{ $giveaway->phone }}</td>
                                      <td>{{ $giveaway->company }}</td>
                                      <td>{{ $giveaway->position }}</td>
                                      <td>{{ date_format($giveaway->created_at, 'Y-m-d' ) }}</td>
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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
      .btn-primary {
        color: #fff !important;
        background-color: #5e72e4 !important;
        border-color: #5e72e4 !important;
        box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08) !important;
      }
    </style>
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
    {{-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script> --}}
    {{-- <script type="text/javascript" src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script> --}}
    {{-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script> --}}
    {{-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.dataTables.js"></script> --}}
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    {{-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script> --}}
    {{-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script> --}}

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        var table = $('#datatable-basic2').DataTable(
          {
            language: {
              paginate: {
                next: '&#187;', // or '→'
                previous: '&#171;' // or '←'
              }
            },
            dom: 'Bfltip',
            buttons: [
              { extend: 'copy', className: 'btn btn-icon btn-primary btn-sm js-excel-button btn', text: '<i class="ni ni-folder-17"></i> Copy' },
              { extend: 'excel', className: 'btn btn-icon btn-primary btn-sm js-excel-button btn', text: '<i class="ni ni-cloud-download-95"></i> Excel', filename: 'Giveaway participants' }
            ],
            order: [[6, 'desc']],
            columnDefs: [
              {
                targets: 6, // La columna de fecha (indexada desde 0)
                render: function(data, type, row) {
                  if (type === 'display' || type === 'filter') {
                    var dateSplit = data.split('-'); // Divide la fecha 'Y-m-d'
                    return dateSplit[2] + '/' + dateSplit[1] + '/' + dateSplit[0]; // Reordena a 'd/m/Y'
                  }
                  return data; // Para otros tipos como ordenación, devuelve la fecha sin modificar
                }
              }
            ]
          }
        );

        $(document).ready(function() {

          $('input[name="daterange"]').daterangepicker();
          $('input[name="daterange"]').val('')

          var minDate = null;
          var maxDate = moment().endOf('day').format('MM/DD/YYYY');

          $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
            minDate = new Date(picker.startDate.format('MM/DD/YYYY'));
            maxDate = new Date(picker.endDate.format('MM/DD/YYYY'));

            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {

                    var rawDate = data[6];
                    var dateSplit = rawDate.split('/');
                    var date = new Date(Number(dateSplit[2]), Number(dateSplit[1]) - 1, Number(dateSplit[0]));


                    if (date >= minDate && date <= maxDate) {
                        return true;
                    }
                    return false;
                }
            );
            table.draw();
          });

          $('.buttons-copy').attr('data-toggle', 'tooltip').attr('data-placement', 'top').attr('title', 'Copy to your clipboard the selected information');
          $('.buttons-excel').attr('data-toggle', 'tooltip').attr('data-placement', 'top').attr('title', 'Download the selected information in an XLS file');
          $('[data-toggle="tooltip"]').tooltip();

        });
    </script>
@endpush
