@extends('layouts.app', [
    'title' => __('Coupons List'),
    'parentSection' => 'laravel',
	'elementName' => 'coupons-management'
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

            <li class="breadcrumb-item"><a href="#">{{ __('Coupon Management') }}</a></li>
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
                                <h3 class="mb-0">{{ __('Coupon Management') }}</h3>
                            </div>

                            <div class="col-4 text-right">
                                <a href="{{ route('coupon.create') }}" class="btn btn-sm btn-primary">{{ __('Add coupon') }}</a>
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
                                    <div class="col-sm-3 filter_col" id="filter_col7" data-column="7">
                                        <label>Status</label>
                                        <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col7_filter">
                                        <option value="">All</option>
                                        <option value="1"> Published </option>
                                        <option value="0"> Unpublished </option>
                                        </select>
                                    </div>

                                    <!-- <Button type="button" onclick="ClearFields();" class="btn btn-secondary btn-lg "> Clear Filter</Button> -->
                                </div>
                            </div>
                        </div>
                        <table class="table table-flush"  id="datatable-basic3">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Used') }}</th>
                                    <th scope="col">{{ __('Created') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coupons as $coupon)
                                    <tr>
                                        <td><a href="{{ route('coupon.edit', $coupon->id) }}"> {{ $coupon->code_coupon }} </a></td>
                                        <td>{{ $coupon->status }}</td>
                                        <td>{{ $coupon->used }}</td>
                                        <td>{{ $coupon->created_at->format('d/m/Y H:i') }}</td>

					                        <td class="text-right">
                                            <?php //dd($user); ?>

                                                    <div class="dropdown">
                                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                                                            <a class="dropdown-item" href="{{ route('coupon.edit', $coupon->id) }}">{{ __('Edit') }}</a>

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

    <script>
        var table = $('#datatable-basic3').DataTable({
            language: {
                paginate: {
                next: '&#187;', // or '→'
                previous: '&#171;' // or '←'
                }
            }
        });

        $('select.column_filter').on('change', function () {
            filterColumn( $(this).parents('div').attr('data-column') );

        } );

        function filterColumn ( i ) {

            $('#datatable-basic3').DataTable().column( 1 ).search(
                $('#col7_filter').val(), true,false
            ).draw();


        }
    </script>


@endpush
