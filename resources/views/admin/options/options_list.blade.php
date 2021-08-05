@extends('layouts.app', [
    'title' => __('options List'),
    'parentSection' => 'laravel',
	'elementName' => 'role-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Examples') }}
            @endslot

            <li class="breadcrumb-item"><a href="#">{{ __('options') }}</a></li>
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
                                <h3 class="mb-0">{{ __('Options') }}</h3>
                                <p class="text-sm mb-0">
                                    {{ __('This is an example of Options management. This is a minimal setup in order to get started fast.') }}
                                </p>
                            </div>
                           
                            {{--<div class="col-4 text-right">
                                <a href="{{ route('option.create') }}" class="btn btn-sm btn-primary">{{ __('Add option') }}</a>
                            </div>--}}
                           
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>

                    <div class="table-responsive py-4">
                        <table class="table table-flush"  id="datatable-basic15">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Created') }}</th>
                                
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($options as $option)
                                    <tr>
                                        <td><a href="{{ route('option.edit', $option->id) }}"> {{ $option->name }} </a></td>
                                       
                                        <td>{{ $option->created_at->format('d/m/Y H:i') }}</td>
                                        
					                        <td class="text-right">
                                            <?php //dd($user); ?>
                                               
                                                    <div class="dropdown">
                                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                          
                                                            <a class="dropdown-item" href="{{ route('option.edit', $option->id) }}">{{ __('Edit') }}</a>
                                                        
                                                        </div>
                                                    </div>
                                                
                                            </td>
					                    
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

    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>

    
@endpush
