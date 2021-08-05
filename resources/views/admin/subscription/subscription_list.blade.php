@extends('layouts.app', [
    'title' => __('Subscription Management'),
    'parentSection' => 'laravel',
    'elementName' => 'subscriptions-management'
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

            <li class="breadcrumb-item"><a href="{{ route('subscriptions.index') }}">{{ __('Subscriptions List') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('List') }}</li>
        @endcomponent
        @include('admin.subscription.layouts.cards')
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Subscriptions') }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>

                    <?php //dd($transactions[0]); ?>

                    <div class="table-responsive py-4">

                    <div class="collapse" id="collapseExample">
                        <div class="container">
                            <div class="row">
                                <div class="col" id="filter_col1" data-column="3">
                                    <label>Event</label>
                                    <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="event" class="column_filter" id="col3_filter">
                                        <option selected value> -- All -- </option>
                                    </select>
                                </div>
                                <div class="col" id="filter_col4" data-column="4">
                                    <label>Status</label>
                                    <select data-toggle="select" data-live-search="true" class="column_filter" id="col4_filter" placeholder="Status">
                                        <option selected value> -- All -- </option>
                                        <option value="1"> ACTIVE </option>
                                        <option value="0"> INACTIVE </option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{--<div class="card bg-gradient-default">
                    <div class="card-body">
                        <!-- <h3 class="card-title text-white">Testimonial</h3>
                        <blockquote class="blockquote text-white mb-0">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            <footer class="blockquote-footer text-danger">Someone famous in <cite title="Source Title">Source Title</cite></footer>
                        </blockquote> -->
                        <div class="container">
                            <div class="row">
                                <div class="col-sm">
                                    <h4 class="card-title text-white">Total Amount:</h4>
                                    <div class="text-white" id="total"></div>
                                </div>

                                <hr id="participantHr">

                            </div>
                        </div>
                        <div style="margin-top:10px" class="container">
                        <div class="row" id="participants_info">
                        </div>
                        </div>
                    </div>
                </div>--}}

                        <table class="table align-items-center table-flush"  id="subscriptions_table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Id') }}</th>
                                    <th scope="col">{{ __('Student') }}</th>
                                    <th scope="col">{{ __('Plan') }}</th>
                                    <th scope="col">{{ __('Event Name') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Trials Sub end at') }}</th>
                                    <th scope="col">{{ __('Sub end at') }}</th>
                                    <th scope="col">{{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>{{ __('Id') }}</th>
                                    <th>{{ __('Student') }}</th>
                                    <th>{{ __('Plan') }}</th>
                                    <th>{{ __('Event Name') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Trials Sub end at') }}</th>
                                    <th>{{ __('Sub end at') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($new_sub as $item)
                                    <tr>
                                        <td>
                                            {{ $item['id'] }}
                                        </td>
                                        <td>
                                            <?php
                                                $user = $item['user'][0];
                                            ?>
                                            {{ $user['firstname']}} {{ $user['lastname']}}
                                        </td>
                                        <td>{{$item['subscription'][0]['plan_name']}}</td>
                                        <td>{{ $item['subscription'][0]['event'][0]['title'] }}</td>

                                        <td>{{ $item['status'] }}</td>
                                        <td>{{ $item['trial'] }}</td>


                                        <td>{{ $item['ends_at'] }}</td>
                                        <td><?= number_format(intval($item['total_amount']), 2, '.', ''); ?></td>


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
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables-datetime/datetime.min.css">
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


        function initStats(){
            amount = $('#subscriptions_table').DataTable().column( 7 ).data();
            let sum = 0;

            $.each(amount, function(key, value) {
                sum = sum + parseInt(value)
                //console.log(value)
            })

            $('#total').text(sum)

        }

        function fillSelectedBox(){
            events = table.column(3).data().unique().sort()
            $.each(events, function(key, value){
                console.log(value)
                $('#col3_filter').append('<option value="'+value+'">'+value+'</option>')
            })
        }

        function filterColumn ( i ) {
            if(i == 4){ //Status filter
                if($('#col'+i+'_filter').val() != ''){
                    status = $('#col'+i+'_filter').val()
                }else{
                    status = ''
                }
                value = status

            }else if(i == 3){ // Event filter
                if($('#col'+i+'_filter').val() != ''){
                    value = $('#col'+i+'_filter').val()
                }else{
                    value = ''
                }

            }
            table.column( i ).search(value).draw();
        }


        // DataTables initialisation
        var table = $('#subscriptions_table').DataTable();
        initStats()

        let status = '';

        $(document).ready(function() {
            fillSelectedBox()

            $('select.column_filter').on('change', function () {
                filterColumn( $(this).parents('div').attr('data-column') );

            } );

        });
    </script>
@endpush
