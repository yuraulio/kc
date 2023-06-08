@extends('layouts.app', [
    'title' => __('User Management'),
    'parentSection' => 'laravel',
    'elementName' => 'user-management'
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

            <li class="breadcrumb-item"><a href="{{ route('user.index') }}">{{ __('User Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('List') }}</li>
        @endcomponent
        @include('users.layouts.cards')
    @endcomponent

    {{--dd($data['transactions']['transactions'])--}}

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Users') }}</h3>
                            </div>
                            <div class="col-4 text-right">

                                <form hidden id="submit-file" action="{{ route('users.file.import') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input id="file-input" name="file" type="file" class="btn btn-sm btn-primary"  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" value="{{ __('Import Testimonials From file') }}" style="display: none;">
                                </form>
                                <a id="download-sample" href="javascript:void(0)" class="btn btn-sm btn-primary">{{ __('Sample File') }}</a>
                                <a id="import-from-file" href="javascript:void(0)" class="btn btn-sm btn-primary">{{ __('Import Users From file') }}</a>
                                <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary">{{ __('New') }}</a>

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
                                    <div class="col-sm-3 filter_col" id="filter_col1" data-column="9">
                                        <label>Courses</label>
                                        <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col9_filter">
                                        <option selected value> -- All -- </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3 filter_col" id="filter_col4" data-column="10">
                                        <label>Coupon</label>
                                        <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col10_filter" placeholder="Coupon">
                                        <option selected value> -- All -- </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3 filter_col" id="filter_col7" data-column="7">
                                        <label>Status</label>
                                        <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col7_filter">
                                        <option selected value> -- All -- </option>
                                        <option value="Active"> Active </option>
                                        <option value="Inactive"> Inactive </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3 filter_col" id="filter_col6" data-column="6">
                                        <label>Roles</label>
                                        <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col6_filter">
                                            <option selected value> -- All -- </option>
                                        </select>
                                    </div>

                                    <div class="col-sm-3 filter_col" id="filter_col12" data-column="12">
                                        <label>Job Position</label>
                                        <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col12_filter">
                                        <option selected value> -- All -- </option>
                                        </select>
                                    </div>

                                    <div class="col-sm-3 filter_col" id="filter_col13" data-column="13">
                                        <label>Company</label>
                                        <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col13_filter">
                                        <option selected value> -- All -- </option>
                                        </select>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>From:</label>
                                            <input class="select2-css" type="text" id="min" name="min">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>To:</label>
                                            <input class="select2-css" type="text" id="max" name="max">
                                        </div>
                                    </div>
                                    <!-- <Button type="button" onclick="ClearFields();" class="btn btn-secondary btn-lg "> Clear Filter</Button> -->
                                </div>
                            </div>
                        </div>
                        <table class="table align-items-center table-flush"  id="datatable-basic45">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Photo</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Lastname') }}</th>
                                    <th scope="col">{{ __('Mobile') }}</th>
                                    <th scope="col">{{ __('Email') }}</th>
                                    <th scope="col">{{ __('KC Id') }}</th>
                                    <th scope="col">{{ __('Student Id') }}</th>
                                    <th scope="col">{{ __('Role') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Creation Date') }}</th>
                                    <th class="d-none" scope="col">{{ __('Event') }}</th>
                                    <th class="elearning-infos d-none" scope="col">{{ __('Video Seen') }}</th>
                                    <th class="elearning-infos d-none" scope="col">{{ __('Exams') }}</th>
                                    <th class="d-none" scope="col">{{ __('Job Position') }}</th>
                                    <th class="d-none" scope="col">{{ __('Company') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                {{--@foreach ($users as $user)
                                    <tr id="user_{{$user['id']}}">
                                        <td>
                                        <?php //dd(asset('profile_user').'/'.$user['image']['name'] ); ?>
                                            <span class="avatar avatar-sm rounded-circle">

                                            @if($user['image'] != null && $user['image']['name'] != '')
                                                <img src="{{ cdn(get_image($user['image'])) }}" alt="{{ $user['firstname'] }}" onerror="this.src='{{cdn('/theme/assets/images/icons/user-circle.svg')}}'" style="max-width: 100px; border-radiu: 25px">
                                            @else
                                            <img src="{{cdn('/theme/assets/images/icons/user-circle.svg')}}" alt="{{ $user['firstname'] }}" style="max-width: 100px; border-radius: 25px">
                                            @endif
                                            </span>
                                        </td>
                                        <td><a href="{{ route('user.edit', $user['id']) }}">{{ $user['firstname'] }}</a></td>
                                        <td>{{ $user['lastname'] }}</td>
                                        <td>{{ $user['mobile'] }}</td>
                                        <td><a href="mailto:{{ $user['email'] }}">{{ $user['email'] }}</a></td>
                                        <td>{{ $user['kc_id'] }}</td>
                                        <td>{{ $user['id'] }}</td>

                                        <td>
                                        @foreach($user['role'] as $role)
                                            {{$role['name']}}
                                        @endforeach
                                        </td>

                                        <td>

                                        <?php //dd($user); ?>

                                        @if($user['statusAccount'] != null)
                                        @if($user['statusAccount']['completed'] == 1)
                                            {{ __('Active') }}
                                        @else
                                            {{ __('Inactive') }}
                                        @endif
                                        @endif
                                        </td>

                                        <td>{{ date('Y-m-d', strtotime($user['created_at'])) }}</td>

                                        <td class="d-none">
                                            @foreach($user['events_for_user_list1'] as $event){{ $event['title'] }}--@if(isset($data['transactions'][$user['id']]))@if(isset($data['transactions'][$user['id']][$event['id']])){{$data['transactions'][$user['id']][$event['id']][0]['type']}}--{{$data['transactions'][$user['id']][$event['id']][0]['amount']}}--{{$data['transactions'][$user['id']][$event['id']][0]['coupon_code']}}--{{$data['transactions'][$user['id']][$event['id']][0]['date']}}||@else||@endif
                                            @endif
                                            @endforeach
                                        </td>

                                        <td class="elearning-infos d-none videoSeen"></td>
                                        <td class="elearning-infos d-none"></td>

                                        <td class="d-none"> {{ $user['job_title'] }} </td>
                                        <td class="d-none"> {{ $user['company'] }} </td>

                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" href="{{ route('user.edit', $user['id']) }}">{{ __('Edit') }}</a>

                                                    <form action="{{ route('user.destroy', $user['id']) }}" method="post">
                                                        @csrf
                                                        @method('delete')

                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach--}}
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
        $(document).ready(() => {
            $('#import-from-file').on('click', function (event) {
                event.preventDefault();
                $("#file-input").trigger('click');
            });

            $('#download-sample').on('click', function (event) {
                event.preventDefault();
                window.location.href = '/import/Users_sample.xlsx';
            });


        });

        $("#file-input").change(function(){
            $("#submit-file").submit();
        })

    </script>
    <script>



        // Custom filtering function which will search data in column four between two values
        $.fn.dataTable.ext.search.push(


            function( settings, data, dataIndex ) {
                let find = false
                var min = new Date($('#min').val());
                var max = new Date($('#max').val());

                if(min == 'Invalid Date'){
                    min = null
                }
                if(max == 'Invalid Date'){
                    max = null
                }

                //console.log('mindate::'+min)
                //console.log('max'+max)


                //console.log(data[9])
                //row = data[9]
                row = data[10].split('||')
                selected_event = removeSpecial($('#select2-col9_filter-container').attr('title'))
                //console.log('MY EVENT'+selected_event)
                //console.log(row)
                $.each(row, function(key1, value1) {

                    if(selected_event != '--All--'){
                        //console.log(value1)
                        if(value1 != ''){
                            event = value1.split('--')
                            date1 = event[4]
                            if(date1 !== undefined){
                                var date = new Date(date1)

                                if (selected_event == removeSpecial(event[0]) && (( min === null && max === null ) ||
                                ( min === null && date <= max ) ||
                                ( min <= date   && max === null ) ||
                                ( min <= date   && date <= max ))) {
                                    find = true
                                        return true
                                    }else if(selected_event == '--All--' && (( min === null && max === null ) ||
                                ( min === null && date <= max ) ||
                                ( min <= date   && max === null ) ||
                                ( min <= date   && date <= max ))){
                                    find = true
                                        return true
                                }

                            }

                            //var date = new Date( date1 );

                            //console.log('inside row:'+date >= min)


                        }
                    }else{
                        find = true
                    }



                    if(find){
                        return true;
                    }


                    //console.log('false')
                        //return false;
                })
                if(find){
                    return true;
                }
                return false;


                    //console.log(value)
                    // val = value.split('||')
                    // $.each(val, function(key1, value1) {
                    //     console.log(value1)
                    //     ev = value1.split('--')
                    //     // if(ev.length > 2){
                    //     //     //console.log(ev)
                    //     // }
                    // })
                //})


            }

        );









        $(function() {

            data = @json($data);

            minDate = new DateTime($('#min'), {
                format: 'L'
            });
            //console.log('--min: '+minDate.val())
            maxDate = new DateTime($('#max'), {
                format: 'L'
            });




            fillFilters(data);

            var table = $('#datatable-basic45').DataTable({
                language: {
                    paginate: {
                        next: '&#187;', // or '→'
                        previous: '&#171;' // or '←'
                    }
                },
                pageLength: 50,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('users.index') }}",
                    data: function(d) {
                        d.event = $('#col9_filter').val(),
                        d.status = $('#col7_filter').val(),
                        d.role = $('#col6_filter').val(),
                        d.job = $('#col12_filter').val(),
                        d.company = $('#col13_filter').val(),
                        d.from_date = $('#min').val(),
                        d.until_date = $('#max').val()
                        d.coupon = $('#col10_filter').val()
                    },
                },
                columns: [

                    {data: 'image', name: 'image', orderable: false},
                    {data: 'firstname', name: 'firstname'},
                    {data: 'lastname', name: 'lastname'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'email', name: 'email'},
                    {data: 'kc_id', name: 'kc_id'},
                    {data: 'id', name: 'id'},
                    {data: 'role', name: 'role.name',title: 'role',orderable: false},
                    {data: 'status', name: 'status',orderable: false},
                    {data: 'created_at',name: 'created_at'},

                    // {data: 'action', name: 'action', orderable: false, searchable: false},

                ],

            });



            $(document).on('change', '#filter_col1, #filter_col7, #filter_col6, #filter_col12, #filter_col13, #min, #max, #filter_col4', function() {
                table.draw();
            });


        });

        function fillFilters(data){

            $.each(data.events, function(key, value) {
                event = value[0]
                $('#col9_filter').append('<option value="'+event.title+'">'+event.title+'</option>')
            })

            $.each(data.coupons, function(key, value) {
                coupon = value['code_coupon']
                $('#col10_filter').append('<option value="'+coupon+'">'+coupon+'</option>')
            })

            $.each(data.roles, function(key, value) {
                role = value['name']
                $('#col6_filter').append('<option value="'+role+'">'+role+'</option>')
            })

            $.each(data.job_positions, function(key, value) {
                $('#col12_filter').append('<option value="'+value+'">'+value+'</option>')
            })

            $.each(data.companies, function(key, value) {
                $('#col13_filter').append('<option value="'+value+'">'+value+'</option>')
            })

            // let jobs = $('#datatable-basic45').DataTable().column(12,{filter: 'applied'}).data().unique().sort();
            // let companies = $('#datatable-basic45').DataTable().column(13,{filter: 'applied'}).data().unique().sort();

            // $('#col12_filter').empty();
            // $('#col12_filter').append('<option value>-- All --</option>')
            // $.each(jobs, function(key, value){
            //     if(value.length > 3){
            //         $('#col12_filter').append('<option value="'+value+'">'+value+'</option>')
            //     }
            // })


            // $('#col13_filter').empty();
            // $('#col13_filter').append('<option value>-- All --</option>')
            // $.each(companies, function(key, value){
            //     if(value.length > 3){
            //         $('#col13_filter').append('<option value="'+value+'">'+value+'</option>')
            //     }
            // })


        }


    </script>
@endpush
