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
              { extend: 'copy', className: 'btn btn-secondary btn-sm', text: '<i class="fa fa-copy"></i> Copy' },
              { extend: 'csv', className: 'btn btn-primary btn-sm', text: '<i class="fa fa-file-csv"></i> CSV' }
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
    </script>
@endpush
