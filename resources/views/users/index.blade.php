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
                            <div class="col-sm-4" id="filter_col4" data-column="10">
                                <label>Coupon</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="Name" class="column_filter" id="col10_filter" placeholder="Coupon">
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
                                    <th class="" scope="col">{{ __('Events') }}</th>
                                    <th class="elearning-infos d-none" scope="col">{{ __('Video Seen') }}</th>
                                    <th class="elearning-infos d-none" scope="col">{{ __('Exams') }}</th>
                                    <th class="elearning-infos" scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr id="user_{{$user['id']}}">
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
                                        <td><a href="{{ route('user.edit', $user['id']) }}">{{ $user['firstname'] }}</a></td>
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
                                        <td class="">
                                            <?php //dd($user['events_for_user_list']); ?>
                                            @foreach($user['events_for_user_list'] as $event){{ $event['title'] }}--@if(isset($data['transactions'][$user['id']]))@if(isset($data['transactions'][$user['id']][$event['id']])){{$data['transactions'][$user['id']][$event['id']][0]['type']}}--{{$data['transactions'][$user['id']][$event['id']][0]['amount']}}--{{$data['transactions'][$user['id']][$event['id']][0]['coupon_code']}}--{{$data['transactions'][$user['id']][$event['id']][0]['date']}}||@else||@endif
                                            @endif
                                            @endforeach
                                        </td>

                                        <td class="elearning-infos d-none videoSeen"></td>
                                        <td class="elearning-infos d-none"></td>

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
        var table = $('#datatable-basic45').DataTable({
            'order':false,
            language: {
                paginate: {
                next: '&#187;', // or '→'
                previous: '&#171;' // or '←'
                }
            }
        });

        $('#datatable-basic45').on( 'page.dt', function () {
            selected_event = $('#select2-col9_filter-container').attr('title')

            if(selected_event.search('E-Learning') != -1){
                var info = table.page.info();
                updateElearningInfos(user_ids, $('#select2-col9_filter-container').attr('title'), info.page)

                table.$('.elearning-infos').removeClass('d-none');
            }else{
                table.$('.elearning-infos').addClass('d-none');
            }

        } );

        var minDate, maxDate;
        let min, max;
        min = new Date($('#min').val());
        max = new Date($('#max').val());

        // minDate = new DateTime($('#min'), {
        // format: 'L'
        // });
        // //console.log('--min: '+minDate.val())
        // maxDate = new DateTime($('#max'), {
        //     format: 'L'
        // });

        //min = moment($('#min').val()).format('MM/DD/YYYY')
        max = null





        // Custom filtering function which will search data in column four between two values
        $.fn.dataTable.ext.search.push(
            

            function( settings, data, dataIndex ) {
                let find = false
                var min = new Date($('#min').val());

                //console.log('mindate::'+min)
        //console.log('max'+max)

                
                //console.log(data[9])
                //row = data[9]
                row = data[9].split('||')
                //console.log(row)
                $.each(row, function(key1, value1) {
                    max = null
                    //console.log(value1)
                    if(value1 != ''){
                        event = value1.split('--')
                        date1 = event[4]
                        if(date1 !== undefined){
                            var date = new Date(date1)
                            console.log('my date:'+date)
                            console.log('min:'+ min)
                            console.log('max:'+ max)
                            console.log('has:'+(min <= date   && max === null))
                            
                            if (( min === null && max === null ) ||
                            ( min === null && date <= max ) ||
                            ( min <= date   && max === null ) ||
                            ( min <= date   && date <= max )) {
                                console.log('true')
                                find = true
                                    return true
                                }
                                
                        }
                        
                        //var date = new Date( date1 );
                        
                        //console.log('inside row:'+date >= min)
                         
                                   
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
            coupons = []
            user_ids = []
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
                    let count = 0;
                    $.each(found_eve, function(key1, value1) {
                        a = value1.split('--')
                        //console.log('////////')
                        if(removeSpecial(a[0]) == selected_event){
                            user_ids.push($('#datatable-basic45').DataTable().column( 5 ).data()[key])
                            // console.log('_________')
                            // console.log(value1)
                            ticket_type = a[1]
                            ticket_amount = parseInt(a[2])

                            if(typeof a[3] !== "undefined"){
                                coupon = a[3]
                                //console.log('////'+a[3])
                                if(selected_event.search('E-Learning') != -1){
                                //console.log(ticket_type)
                                    coupons.push({
                                                'price': ticket_amount,
                                                'type' : ticket_type,
                                                'name' : coupon
                                            })


                                }
                            }



                            //console.log(ticket_type)
                            //console.log(ticket_amount)
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


                //console.log(ticket_amount)
                if(typeof ticket_amount !== "undefined" && !isNaN(ticket_amount)){
                    sum = sum + ticket_amount
                    //console.log(sum)
                }


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

            updateElearningInfos(user_ids, $('#select2-col9_filter-container').attr('title'), 0)

            if(selected_event.search('E-Learning') != -1){

                if($('.elearning-infos').hasClass('d-none')){
                    $('.elearning-infos').removeClass('d-none')
                    $('#participants_info').removeClass('d-none')
                }

                //$('#participants_info').removeClass('d-none')

                $('.elearning-coupons').remove()
                // Accepts the array and key
                const groupBy = (array, key) => {
                // Return the end result
                return array.reduce((result, currentValue) => {
                    // If an array already present for key, push it to the array. Else create an array and push the object
                    (result[currentValue[key]] = result[currentValue[key]] || []).push(
                    currentValue
                    );
                    // Return the current iteration `result` value, this will be taken as next iteration `result` value and accumulate
                    return result;
                }, {}); // empty object is the initial value for result object
                };

                // Group by color as key to the person array
                const couponsGroupedByName = groupBy(coupons, 'name');

                //console.log(couponsGroupedByName)

                sumCoupon = []
                //console.log(couponsGroupedByName)
                $.each(couponsGroupedByName, function(key, value){
                    //console.log('from coupons')
                    var sum1 = 0
                    var count1 = 0
                    $.each(value, function(key1, value1){
                        count1++

                        sum1 = sum1 + parseInt(value1.price)
                    })
                    //console.log(key+'::'+sum)




                    elem =`
                            <div class="elearning-coupons col-xl-3 col-md-6">
                                <div class="card card-stats">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="card-title text-uppercase text-muted mb-0"><div id="count_sponsored">${count1}x ${key}:</div></h5>
                                                <span id="total" class="h2 font-weight-bold mb-0">${'€'+sum1}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `

                            $('#participants_info').append(elem)

                })

            }else{
                $('.elearning-infos').addClass('d-none')
                $('#participants_info').addClass('d-none')

            }

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




        function getStatsByDate(min, max, details){

            if(details.length > 2){

                // console.log(details)
                // console.log('min:'+min)
                // console.log('max:'+max)
                title = details[0]
                type = details[1]
                amount = parseInt(details[2])
                datatable_date = details[4]
                datatable_date = new Date(datatable_date);
                datatable_date = moment(datatable_date).format('MM/DD/YYYY')
               
                if(type != ''){
                if(min != 'Invalid date' && max == 'Invalid date'){
                    if(moment(datatable_date).isAfter(min)){
                        sum = sum + amount


                        if(type == 'Alumni'){
                            alumni = alumni + amount
                            count_alumni++
                        }else if(type == 'Regular'){
                            regular = regular + amount
                            count_regular++
                        }else if(type == 'Special'){
                            special = special + amount
                            count_special++
                        }else if(type == 'Sponsored'){
                            sponsored = sponsored + amount
                            count_sponsored++
                        }else if(type == 'Early birds'){
                            early = early + amount
                            count_early++
                        }
                    }
                }else if(min !='Invalid date' && max != 'Invalid date'){
                    if(moment(datatable_date).isAfter(min) && moment(datatable_date).isBefore(max)){
                        sum = sum + amount

                        if(type == 'Alumni'){
                            alumni = alumni + amount
                            count_alumni++
                        }else if(type == 'Regular'){
                            regular = regular + amount
                            count_regular++
                        }else if(type == 'Special'){
                            special = special + amount
                            count_special++
                        }else if(type == 'Sponsored'){
                            sponsored = sponsored + amount
                            count_sponsored++
                        }else if(type == 'Early birds'){
                            early = early + amount
                            count_early++
                        }
                    }
                }else if(min == 'Invalid date' && max != 'Invalid date'){
                    if(moment(datatable_date).isBefore(max)){
                        sum = sum + amount

                        if(type == 'Alumni'){
                            alumni = alumni + amount
                            count_alumni++
                        }else if(type == 'Regular'){
                            regular = regular + amount
                            count_regular++
                        }else if(type == 'Special'){
                            special = special + amount
                            count_special++
                        }else if(type == 'Sponsored'){
                            sponsored = sponsored + amount
                            count_sponsored++
                        }else if(type == 'Early birds'){
                            early = early + amount
                            count_early++
                        }
                    }
                }
            }

            }
            
            
        

    }

    //Refilter the table
    $('#min, #max').on('change', function () {
        $('#participants_info').removeClass('d-none')
        alert('change date')
        //console.log('from change min!!')
        table.draw();
        //console.log(table.column(1).data())
        events = table.column( 9 ).data()
        //console.log(price)

        initCounters()

        min = new Date($('#min').val());
        max = new Date($('#max').val());
        console.log('+min:'+min)
        console.log('+max:'+max)

        minDate = new DateTime($('#min'), {
            format: 'L'
        });

        

        //console.log(minDate.getTime())
       // moment()

        min = moment(min).format('MM/DD/YYYY')
        max = moment(max).format('MM/DD/YYYY')
        

        //console.log(moment(min).isBefore(max))
        //EDW
        //initCounters()
        $.each(events, function(key, value){
            //console.log('KEY:'+key)
            //console.log('row:')
            //console.log(value)
            if(value != ''){
                value = value.split('||')
                    $.each(value, function(key1, value1) {
                        //console.log('events inside row:')
                        //console.log(value1)
                        if(value1 != ''){
                            details = value1.split('--')
                            //console.log('pre')
                            //console.log(details)
                            //console.log('STOP')
                            getStatsByDate(min, max, details)
                        }
                        //a = value1.split('--')
                        
                        ///console.log('----------')
                        //console.log(a)
                        //datatable_date = new Date(a[4]);
                        //datatable_date = moment(datatable_date).format('MM/DD/YYYY')
                        //console.log(datatable_date)
                        //console.log('PRICE:')
                        //console.log(a[3])
                        //

                    })
            }
            
            //datatable_date = table.column( 9 ).data()[key]
            //datatable_date = new Date(datatable_date);
            //datatable_date = moment(datatable_date).format('MM/DD/YYYY')
            //console.log(datatable_date)

        })


        //alert(sum)
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
    });





        function updateElearningInfos(ids, event, page){
            // user_ids = $('#datatable-basic45').DataTable().column( 5 ).data()
            // console.log(user_ids)

            $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/events/fetchElearningInfos',
            data: {'ids': ids, 'event': event, 'page': page},
            success: function (data) {
                data = JSON.parse(data)
                if(data){
                    $.each(data, function(key, value) {
                        //console.log(value)
                        $('#user_'+value.id).find('.videoSeen').text(value.video_seen)[0]
                    })
                   //data = data.data
                    //$('.exp_'+data.id).text(data.date)
                }

            }
        });
        }

        function filterColumn ( i ) {

            //console.log('event:'+$('#col9_filter').val())
            //console.log('coupon:'+$('#col10_filter').val())
            if(i == 9) {
                table = $('#datatable-basic45').DataTable().column( 9 ).search(
                    $('#col9_filter').val(), true,false
                ).draw();

                stats()

                //updateElearningInfos($('#col9_filter').val())


            }else{
                $('#datatable-basic45').DataTable().column( 9 ).search(
                    $('#col10_filter').val(), true,false
                ).draw();
            }


        }


        $(function() {

            minDate = new DateTime($('#min'), {
                format: 'L'
            });
            //console.log('--min: '+minDate.val())
            maxDate = new DateTime($('#max'), {
                format: 'L'
            });
            data = @json($data);

            $.each(data.coupons, function(key, value) {
                //console.log(value)
                $('#col10_filter').append('<option value="'+value.code_coupon+'">'+value.code_coupon+'</option>')
            })

            $.each(data.events, function(key, value) {
                event = value[0]
                $('#col9_filter').append('<option value="'+event.title+'">'+event.title+'</option>')
            })

            $('select.column_filter').on('change', function () {
                filterColumn( $(this).parents('div').attr('data-column') );

            } );


        });



    </script>
@endpush
