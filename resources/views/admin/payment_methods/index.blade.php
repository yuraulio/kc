@extends('layouts.app', [
    'title' => __('Payment Management'),
    'parentSection' => 'laravel',
    'elementName' => 'payment-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">{{ __('Payments Methods Management') }}</a></li>
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
                                <h3 class="mb-0">{{ __('Payments Methods') }}</h3>
                            </div>
                            @can('create', App\Model\User::class)
                                <div class="col-4 text-right">
                                    <a href="{{ route('payments.create') }}" class="btn btn-sm btn-primary">{{ __('Add Payment Method') }}</a>
                                </div>
                            @endcan
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="d17">
                            <thead class="thead-light">
                                <tr>

                                    <th scope="col">{{ __('ID') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Slug') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Options') }}</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paymentMethods as $method)
                                    <tr>
                                        <td>
                                            {{$method->id}}
                                        </td>
                                        <td>
                                            {{$method->method_name}}
                                        </td>
                                        <td>
                                            {{$method->method_slug}}
                                        </td>
                                        <td>
                                            {{$method->status}}
                                        </td>
					                    @can('manage-users', App\Model\User::class)
					                        <td class="">

                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">


                                                        <a class="dropdown-item" href="{{ route('payments.edit', $method->id) }}">{{ __('Edit') }}</a>


                                                    </div>
                                                </div>

                                            </td>
					                    @endcan
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
@endpush
