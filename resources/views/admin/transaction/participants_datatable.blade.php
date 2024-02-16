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


                <div class="collapse" id="collapseFilters">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-3 filter_col" id="filter_col1" data-column="1">
                                <label>Event</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col1_filter">
                                <option selected value> -- All -- </option>
                                </select>
                            </div>
                            <div class="col-sm-3 filter_col" id="filter_col4" data-column="4">
                                <label>Coupon</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col4_filter" placeholder="Coupon">
                                <option selected value> -- All -- </option>
                                </select>
                            </div>

                            <div class="col-sm-3 filter_col" id="filter_col8" data-column="8">
                                <label>Payment Method</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col8_filter" placeholder="Payment Method">
                                <option selected value> -- All -- </option>
                                </select>
                            </div>


                            <div class="col-sm-3 filter_col" id="filter_col11" data-column="11">
                                <label>Delivery</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col11_filter" placeholder="Payment Method">
                                <option selected value> -- All -- </option>
                                </select>
                            </div>

                            <div class="col-sm-3 filter_col d-none" id="filter_col12" data-column="12">
                                <label>City</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col12_filter" placeholder="City">
                                <option selected value> -- All -- </option>
                                </select>
                            </div>

                            <div class="col-sm-3 filter_col" id="filter_col13" data-column="13">
                                <label>Category</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col13_filter" placeholder="Category">
                                <option selected value> -- All -- </option>
                                </select>
                            </div>


                            <div class="col-sm-3 filter_col">
                                <div class="form-group">
                                    <label>From - To</label>
                                    <input class="select2-css" type="text" autocomplete="off" name="daterange">
                                </div>
                            </div>
                            <!-- <div class="col-sm-3 filter_col">
                                <div class="form-group">
                                    <label>From:</label>
                                    <input class="select2-css" type="text" id="min" name="min">
                                </div>
                            </div>
                            <div class="col-sm-3 filter_col">
                                <div class="form-group">
                                    <label>To:</label>
                                    <input class="select2-css" type="text" id="max" name="max">
                                </div>
                            </div> -->
                            {{--<Button type="button" onclick="ClearFields();" class="btn btn-secondary btn-lg "> Clear Filter</Button>--}}
                        </div>
                    </div>
                </div>
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
  });
</script>

@endpush
