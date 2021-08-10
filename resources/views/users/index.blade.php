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
        @include('admin.transaction.layouts.cards')
    @endcomponent

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
                                <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary">{{ __('Add user') }}</a>
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
                            <div class="col-sm-4" id="filter_col1" data-column="9">
                                <label>Event</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="col9_filter">
                                <option selected value> -- All -- </option>
                                </select>
                            </div>
                            <div class="col-sm-4" id="filter_col4" data-column="4">
                                <label>Coupon</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col4_filter" placeholder="Coupon">
                                <option selected value> -- All -- </option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Min</label>
                                    <input type="text" id="min" name="min">
                                </div>

                                <div class="form-group">
                                    <label>Max</label>
                                    <input type="text" id="max" name="max">
                                </div>
                            </div>
                            <Button type="button" onclick="ClearFields();" class="btn btn-secondary btn-lg "> Clear Filter</Button>
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
                                    <th scope="col">{{ __('Student Id') }}</th>
                                    <th scope="col">{{ __('Role') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Creation Date') }}</th>
                                    <th scope="col">{{ __('Events') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                        <?php //dd(asset('profile_user').'/'.$user['image']['name'] ); ?>
                                            <span class="avatar avatar-sm rounded-circle">

                                            {{--@if($user['image'] != null && $user['image']['name'] != '')
                                                <img src="{{ cdn(get_image($user['image'])) }}" alt="{{ $user['firstname'] }}" style="max-width: 100px; border-radiu: 25px">
                                            @else
                                            <img src="" alt="{{ $user['firstname'] }}" style="max-width: 100px; border-radius: 25px">
                                            @endif--}}
                                            </span>
                                        </td>
                                        <td>{{ $user['firstname'] }}</td>
                                        <td>{{ $user['lastname'] }}</td>
                                        <td>{{ $user['mobile'] }}</td>
                                        <td><a href="mailto:{{ $user['email'] }}">{{ $user['email'] }}</a></td>
                                        <td>{{ $user['id'] }}</td>

                                        <td>
                                        @foreach($user['role'] as $role)
                                            {{$role['name']}}
                                        @endforeach
                                        </td>

                                        <td>

                                        @if($user['status_account'] != null)
                                        @if($user['status_account']['completed'] == 1)
                                            {{ __('Active') }}
                                        @else
                                            {{ __('Inactive') }}
                                        @endif
                                        @endif
                                        </td>

                                        <td>{{ $user['created_at'] }}</td>
                                        <td>
                                            <?php //dd($data['transactions'][1350]); ?>
                                            @foreach($user['events_for_user_list'] as $event){{ $event['title'] }}--@if(isset($data['transactions'][$user['id']]))
                                            @if(isset($data['transactions'][$user['id']][$event['id']])){{$data['transactions'][$user['id']][$event['id']][0]['type']}}--{{$data['transactions'][$user['id']][$event['id']][0]['amount']}}||@else||@endif
                                            @endif
                                            @endforeach
                                        </td>

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
        $('#datatable-basic45').DataTable({
            'order':false,
            language: {
                paginate: {
                next: '&#187;', // or '→'
                previous: '&#171;' // or '←'
                }
            }
        });

        function initCounters(){
            sum = 0
            alumni = 0;
            special = 0;
            regular = 0;
            sponsored = 0;
            early = 0;
            count_alumni = 0;
            count_special = 0;
            count_regular = 0;
            count_sponsored = 0;
            count_early = 0;
        }

        function removeSpecial(s){
            s = s.replace(/ /g,'');
            s = s.replace(/&/g,'');
            s = s.replace(/amp;/g,'');
            return s
        }

        function stats(selected_event){
            let sum = 0


            initCounters()

            selected_event = removeSpecial($('#select2-col9_filter-container').attr('title'))
            events = $('#datatable-basic45').DataTable().column( 9 ).data()

            $.each(events, function(key, value) {
                let ticket_type = ''
                let ticket_amount = 0;

                value_without_spec = removeSpecial(value)
                let find = value_without_spec.indexOf(selected_event)
                //console.log(find)
                if(find != -1){

                    found_eve = value_without_spec.split('||')
                    //console.log(found_eve)
                    $.each(found_eve, function(key1, value1) {
                        a = value1.split('--')
                        console.log('////////')
                        console.log(value1)
                        //console.log(a)
                        if(a[key1] == selected_event){
                            console.log('_________')
                            ticket_type = a[key1 + 1]
                            ticket_amount = a[key1 + 2]
                            console.log(ticket_type)
                            console.log(ticket_amount)
                        }
                        //console.log(value_without_spec)
                        // if(selected_event == value1){
                        //     alert('has found')
                        //     ticket_type = value1[key1 + 1]
                        //     ticket_amount = value1[key1 + 2]
                        //     console.log(ticket_amount)
                        // }

                    })
                }



                sum = sum + parseInt(ticket_amount)

                if(ticket_type == 'Alumni'){
                    alumni = alumni + parseInt(ticket_amount)
                    count_alumni++
                }else if(ticket_type == 'Regular'){
                    regular = regular + parseInt(ticket_amount)
                    count_regular++
                }else if(ticket_type == 'Special'){
                    special = special + parseInt(ticket_amount)
                    count_special++
                }else if(ticket_type == 'Sponsored'){
                    sponsored = sponsored + parseInt(ticket_amount)
                    count_sponsored++
                }else if(ticket_type == 'Earlybirds' || ticket_type == 'EarlyBird'){
                    //console.log('has early bird')
                    early = early + parseInt(ticket_amount)
                    count_early++
                }

            })

            $('#total').text('€'+sum)
            $('#special').text('€'+special)
            $('#regular').text('€'+regular)
            $('#alumni').text('€'+alumni)
            $('#early').text('€'+early)
            $('#sponsored').text('€'+sponsored)
            $('#count_special').text(count_special)
            $('#count_regular').text(count_regular)
            $('#count_alumni').text(count_alumni)
            $('#count_early').text(count_early)
            $('#count_sponsored').text(count_sponsored)



        }

        function filterColumn ( i ) {
            $('#datatable-basic45').DataTable().column( i ).search(
                $('#col'+i+'_filter').val()
            ).draw();

            if(i == 9) {
                stats()
                if($('#participants_info').hasClass('d-none')){
                    $('#participants_info').removeClass('d-none')
                }
            }






            //select event filter
            // if(i == 9){
            //     selected_event = $('#select2-col9_filter-container').attr('title')

                //$('.participants_info').empty()
                //event = removeSpecial($('#col'+i+'_filter').val())
                //console.log(event)

                // if(event.search('E-Learning') != -1){

                //     if($('.participant_elearning').hasClass('none')){
                //         $('.participant_elearning').removeClass('none')
                //     }


                // }else{
                //     $('.participant_elearning').addClass('none')
                // }

            //     stats(selected_event)

            // }else{
            // //select coupon filter
            // }


        }


        $(function() {
            data = @json($data);

            $.each(data.events, function(key, value) {
                event = value[0]
                //console.log(event)
                // coupons = value[0].coupons[0]
                // console.log(coupons)
                $('#col9_filter').append('<option value="'+event.title+'">'+event.title+'</option>')
            })

            $('select.column_filter').on('change', function () {
                filterColumn( $(this).parents('div').attr('data-column') );

            } );

            // $.each(events, function(key, value){

            //     $('#col9_filter').append('<option value="'+value+'">'+value+'</option>')
            //     })
            //     $.each(coupons, function(key, value){
            //     $('#col4_filter').append('<option value="'+value+'">'+value+'</option>')
            //     })

        });



    </script>
@endpush
