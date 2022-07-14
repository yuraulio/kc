@extends('layouts.app', [
    'title' => __('Event Management'),
    'parentSection' => 'laravel',
    'elementName' => 'events-management'
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
            <li class="breadcrumb-item"><a href="{{ route('events.index') }}">{{ __('Event Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('List') }}</li>
        @endcomponent
        @include('event.layouts.cards')
    @endcomponent



    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">



                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Events') }}</h3>
                            </div>
                            @can('create', App\Model\User::class)
                                <div class="col-4 text-right">
                                    <a href="{{ route('events.create') }}" class="btn btn-sm btn-primary">{{ __('Add Event') }}</a>
                                </div>
                            @endcan
                        </div>
                    </div>

                    <div class="collapse" id="collapseExample">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-3 filter_col" id="filter_col1" data-column="1">
                                <label>Published/Upublished</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col1_filter">
                                <option selected value> -- All -- </option>
                                </select>
                            </div>
                            <div class="col-sm-3 filter_col" id="filter_col2" data-column="2">
                                <label>Status</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col2_filter" >
                                <option selected value> -- All -- </option>
                                </select>
                            </div>

                            <div class="col-sm-3 filter_col" id="filter_col6" data-column="6">
                                <label>Delivery</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col6_filter" >
                                <option selected value> -- All -- </option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                    <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>



                    <div class="table-responsive py-4">



                        <table class="table align-items-center table-flush"  id="datatable-basic26">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Published') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Assigned to Category') }}</th>
                                    <th scope="col">{{ __('Assigned to Type') }}</th>
                                    <th scope="col">{{ __('Created at') }}</th>
                                    <th hidden scope="col">{{ __('Delivery') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $event)
                                    <tr>
                                        <td><a href="{{ route('events.edit', $event) }}">{{ $event->title }}</a></td>
                                        <td>
                                            @if($event->published == 0)
                                                {{'Unpublished'}}
                                            @elseif($event->published == 1)
                                                {{'Published'}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($event->status == 1)
                                                {{'Close'}}
                                            @elseif($event->status == 0)
                                                {{'Open'}}
                                            @elseif($event->status == 3)
                                                {{'Completed'}}
                                            @elseif($event->status == 2)
                                                {{'Soldout'}}
                                            @elseif($event->status == 4)
                                                {{ __('My Account Only') }}
                                            @elseif($event->status == 5)
                                                {{ __('Waiting') }}
                                            @endif
                                        </td>

                                        <td>
                                        @foreach($event->category as $category)
                                            {{ $category->name }}
                                        @endforeach
                                        </td>
                                        <td>
                                        @foreach($event->type as $type)
                                            {{ $type->name }}
                                        @endforeach
                                        </td>
                                        <td>{{ date_format($event->created_at, 'Y-m-d' ) }}</td>
                                        <td hidden >@if(isset($event['delivery'][0])) {{$event['delivery'][0]['name']}} @else none @endif </td>
                                        <td class="text-right">

                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" href="{{ route('events.edit', $event) }}">{{ __('Edit') }}</a>
                                                    <form action="{{ route('events.destroy', $event) }}" method="post">
                                                        @csrf
                                                        @method('delete')

                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('event.clone', $event) }}" method="post">
                                                        @csrf

                                                        <button type="sumbit" class="dropdown-item">
                                                            {{ __('Clone') }}
                                                        </button>
                                                    </form>

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

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
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
        $( "#assignButton" ).on( "click", function(e) {
            const eventId = $(this).data("event-id");

            $.ajax({
               type:'POST',
               url:'/getmsg',
               data:'_token = <?php echo csrf_token() ?>',
               success:function(data) {
                  $("#msg").html(data.msg);
               }
            });

        });

        // $('#datatable-basic').DataTable({
        //                 "ordering" : false,
        //             });

    var table = $('#datatable-basic26').DataTable({
        "order": [[1, 'asc'],[ 5, "desc" ]],
        language: {
            paginate: {
            next: '&#187;', // or '→'
            previous: '&#171;' // or '←'
            }
        }
    });

      </script>


    <script>

        $( document ).ready(function(){

            let published = table.column(1).data().unique();
            let status = table.column(2).data().unique();
            let delivery = table.column(6).data().unique();

            $.each(published, function(key, value){

                $('#col1_filter').append(`<option value="${value}">${value}</option>`)

            })

            $.each(status, function(key, value){

                $('#col2_filter').append(`<option value="${value}">${value}</option>`)

            })

            $.each(delivery, function(key, value){
                 if(value != 'none'){
                    $('#col6_filter').append(`<option value="${value}">${value}</option>`)
                 }
            })

        });

        $('select.column_filter').on('change', function () {
            filterColumn( $(this).parents('div').attr('data-column') );
        });

        function filterColumn ( i ) {

            if($('#col'+i+'_filter').val() && i != 8){
                $('#datatable-basic26').DataTable().column( i ).search(
                    '^'+$('#col'+i+'_filter').val()+'$', true,true
                ).draw();
            }else{
                $('#datatable-basic26').DataTable().column( i ).search(
                    $('#col'+i+'_filter').val()
                ).draw();
            }

        }

    </script>


@endpush
