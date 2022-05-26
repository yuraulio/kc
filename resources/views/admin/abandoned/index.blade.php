@extends('layouts.app', [
    'title' => __('Abandoned Management'),
    'parentSection' => 'laravel',
    'elementName' => 'abandoned-management'
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

            <li class="breadcrumb-item"><a href="{{ route('abandoned.index') }}">{{ __('Abandoned Management') }}</a></li>
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
                                <h3 class="mb-0">{{ __('Abandoned') }}</h3>
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
                                    <div class="col-4" id="filter_col1" data-column="1">
                                        <label>Event</label>
                                        <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="event" class="column_filter" id="col1_filter">
                                            <option selected value> -- All -- </option>
                                        </select>
                                    </div>

                                    <div class="col-4 filter_col">
                                        <div class="form-group">
                                            <label>From:</label>
                                            <input class="select2-css" type="text" id="min" name="min">
                                        </div>
                                    </div>
                                    <div class="col-4 filter_col">
                                        <div class="form-group">
                                            <label>To:</label>
                                            <input class="select2-css" type="text" id="max" name="max">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <a href="javascript:void(0)" style="margin-left:1.2rem;" class="btn btn-primary btn-sm export-excel">Export excel</a>
                        <hr>
                        <table class="table align-items-center table-flush"  id="abandoned_table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('User') }}</th>
                                    <th scope="col">{{ __('Event') }}</th>
                                    <th scope="col">{{ __('Ticket') }}</th>
                                    <th scope="col">{{ __('Qty') }}</th>
                                    <th scope="col">{{ __('Amount') }}</th>
                                    <th scope="col">{{ __('Dates') }}</th>
                                    <th scope="col"></th>
                                    <th hidden scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php //dd($abcart); ?>
                                @if(isset($list))
                                    @foreach($list as $user_id => $ucart)
                                    <?php //dd($abcart); ?>
                                        @if(isset($abcart[$user_id]->user) && isset($tickets[$ucart->id]))
                                        <?php
                                            $evdate = 'No Date';
                                            if(isset($events[$ucart->options['event']]['customFields'])) {
                                                foreach ($events[$ucart->options['event']]['customFields'] as $ckey => $cvalue) {
                                                    if ($cvalue->name == 'simple_text' && $cvalue->priority == 0) {
                                                        $evdate = $cvalue->value;
                                                        break;
                                                    }
                                                }
                                            }
                                        ?>

                                        <tr>
                                            <?php //dd($events[$ucart->options['event']]['title']); ?>
                                            <td>@if($abcart[$user_id]['user']->first() != null)<a href="mailto:{{$abcart[$user_id]['user']->first()['email']}}">{{$abcart[$user_id]['user']->first()['email']}}</a><br />{{$abcart[$user_id]['user']->first()['firstname']}} {{$abcart[$user_id]['user']->first()['lastname']}}<br /><a target="_blank" href="admin/student/{{$user_id}}"><i class="fa fa-external-link"></i></a> @endif</td>
                                            <td class="text-center">{{$events[$ucart->options['event']]['title']}}</td>
                                            <td class="text-center">{{$tickets[$ucart->id]->title}}</td>
                                            <td class="text-center">{{$ucart->qty}}</td>
                                            <td class="text-right">&euro;{{$ucart->qty*$ucart->price}}</td>

                                            <td class="td_categories text-right">@if(isset($abcart[$user_id]->created_at) && $abcart[$user_id]->created_at != '') {{$abcart[$user_id]->created_at->format('Y-m-d')}} @endif </td>
                                            {{--<td class="td_categories text-right">@if(isset($abcart[$user_id]->created_at) && $abcart[$user_id]->created_at != '') C:{{$abcart[$user_id]->created_at->format('d/m/Y H:i')}} @endif <br />U:{{$abcart[$user_id]->updated_at->format('d/m/Y H:i')}}</td>--}}
                                            {{--<td style="text-align: center;"><span class="delete_link"><a href="javascript:void(0);" class="delete_abcart" data-dp-content-id="{{$user_id}}" data-dp-content-title="{{$ucart->name}}" title="Delete"><i class="fa fa-trash"></i></span></td>--}}


                                            <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                                                    <form action="{{ route('abandoned.remove', $abcart[$user_id]->identifier) }}" method="post">
                                                        @csrf
                                                        @method('post')

                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this item?") }}') ? this.parentElement.submit() : ''">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            </td>
                                            <td hidden>
                                                {{$events[$ucart->options['event']]['id']}}
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endif

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('argon') }}/vendor/datatables-datetime/datetime.min.js"></script>

    <script>
        function fillSelectedBox(){
            events = table.column(1).data().unique().sort()
            $.each(events, function(key, value){
                $('#col1_filter').append('<option value="'+value+'">'+value+'</option>')
            })
        }

        function filterColumn ( i ) {
            table.column( i ).search($('#col'+i+'_filter').val()).draw();
        }

        // DataTables initialisation
        var table = $('#abandoned_table').DataTable({
            language: {
                paginate: {
                next: '&#187;', // or '→'
                previous: '&#171;' // or '←'
                }
            }
        });

        $(document).ready(function() {

            minDate = new DateTime($('#min'), {
                format: 'L'
            });
            //console.log('--min: '+minDate.val())
            maxDate = new DateTime($('#max'), {
                format: 'L'
            });

            fillSelectedBox()

            $('select.column_filter').on('change', function () {
                filterColumn( $(this).parents('div').attr('data-column') );

            } );


            var minDate, maxDate;


            // Custom filtering function which will search data in column four between two values
            $.fn.dataTable.ext.search.push(
            
                function( settings, data, dataIndex ) {
                    var min = minDate.val();
                    var max = maxDate.val();
                    var date = new Date( data[5] );
                    if (
                        ( min === null && max === null ) ||
                        ( min === null && date <= max ) ||
                        ( min <= date   && max === null ) ||
                        ( min <= date   && date <= max )
                    ) {
                        return true;
                    }
                    return false;
                }
            
            );

            $('#min, #max').on('change', function () {
              
                table.draw();
                     
                min = new Date($('#min').val());
                max = new Date($('#max').val());            
                minDate = new DateTime($('#min'), {
                    format: 'L'
                });         
                min = moment(min).format('MM/DD/YYYY')
                max = moment(max).format('MM/DD/YYYY')          

            });



            $(".export-excel").click(function(){

                let events = table.column(7,{filter: 'applied'}).data().unique().sort();
                console.log(events);

                //abandoned/exportcsv
            })

        });


    </script>
@endpush
