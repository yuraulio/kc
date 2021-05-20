

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Topics') }}</h3>
                                <p class="text-sm mb-0">
                                        {{ __('This is an example of Topic management.') }}
                                    </p>
                            </div>
                            {{--@can('create', App\User::class)
                                <div class="col-4 text-right">
                                    <a href="{{ route('topics.create_event', ['event_id' => $event['id']]) }}" class="btn btn-sm btn-primary">{{ __('Assign Topic') }}</a>
                                </div>
                            @endcan--}}
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="datatable-basic">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Title') }}</th>

                                    <th scope="col">{{ __('Assigned to event') }}</th>
                                    <th scope="col">{{ __('Created at') }}</th>
                                    {{--@can('manage-users', App\User::class)
                                        <th scope="col"></th>
                                    @endcan--}}
                                </tr>
                            </thead>
                            <tbody>
                            <?php //dd($allTopicsByCategory); ?>
                                @foreach ($allTopicsByCategory->topics as $topic)

                                    <tr>
                                        <td>{{ $topic->status }}</td>
                                        <td>{{ $topic->title }}</td>

                                        <td>
                                            <?php $status=""; ?>
                                            @foreach($event->topic as $topic_db)
                                                @if($topic['id'] == $topic_db->id)
                                                    <?php $status="active"; ?>
                                                    <?php //dd($topic); ?>
                                                @endif
                                            @endforeach

                                            <div id="{{$topic['id']}}" class="btn-group-toggle" data-toggle="buttons">
                                                <label data-event-id="{{$event['id']}}" data-topic-id="{{$topic['id']}}" class="btn btn-secondary <?= $status; ?>">
                                                    <input type="checkbox" <?= ($status == 'active') ? 'checked=""' : ''; ?> autocomplete="off"> <?= ($status == 'active') ? 'Unassign' : 'Assign'; ?>
                                                </label>
                                            </div>


                                        </td>
                                        <td>{{ date_format($topic->created_at, 'Y-m-d' ) }}</td>
					                    {{--@can('manage-users', App\User::class)
					                        <td class="text-right">
                                                @if (auth()->user()->can('update', $user) || auth()->user()->can('delete', $user))
                                                    <div class="dropdown">
                                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                                                                @can('update', $user)
                                                                    <a class="dropdown-item" href="{{ route('topics.edit_event', ['topic' => $topic, 'event_id' => $event['id']]) }}">{{ __('Edit') }}</a>
                                                                @endcan
    							                                @can('delete', $user)
        							                                <form action="{{ route('topics.destroy', $topic) }}" method="post">
                                                                        @csrf
                                                                        @method('delete')

                                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                                                                            {{ __('Delete') }}
                                                                        </button>
                                                                    </form>
    						                                    @endcan

                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
					                    @endcan--}}
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
        $( ".btn-group-toggle" ).change(function() {
            function convertToSlug(Text)
                {
                    return Text
                        .toLowerCase()
                        .replace(/ /g,'-')
                        .replace(/[^\w-]+/g,'')
                        ;
                }
            let status = $(this).find('label').hasClass('active')
            let id = $(this).find('label').data('topic-id')
            let event_id = $(this).find('label').data('event-id')


            let data = {status1:status, topic_id : id, event_id : event_id}

            $.ajax({
                type: 'POST',
                headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },
               Accept: 'application/json',
                url: "{{ route ('events.assign_store') }}",
                data:data,
                success: function(data) {
                    data = JSON.parse(data)
                    console.log(data)
                    let e = $('#'+data.request.topic_id).find('label')


                        if(data.request.status1 == "true")
                        {
                                    $(e).text('Unassign')
                                    $(e).append('<input type="checkbox" checked="" autocomplete="off">')


                        }
                        else if(data.request.status1 == "false")
                        {
                            $(e).text('Assign')
                            $(e).append('<input type="checkbox" autocomplete="off">')


                        }


                                if(data.request.status1 == "true"){
                                    topic_row = `
                                        <div id="inst_${data.lesson.title}" class="card">
                                            <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                <h5 class="mb-0">${data.lesson.title}</h5>
                                            </div>
                                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                                <div  class="card-body">
                                                <ul >
                                                </ul>

                                                </div>
                                            </div>
                                    </div>
                                        `
                                    $('#instructor-wrapper').append(topic_row)

                                    $.each( data.lesson['lessons'], function( key, value ) {
                                    $.each( value, function( key1, value1 ) {
                                        let title = ""
                                        if(key1 == 'title'){
                                            title = value1;
                                            //alert(title)
                                            lesson_row =`
                                        <li>${title}</li>
                                        `
                                        $('#inst_'+data.lesson.title).find('ul').append(lesson_row)
                                        }


                                    });
                                });
                                }else{
                                    alert('from remove')
                                    $('#inst_'+data.lesson.title).remove()
                                }











                }
            });


        });

    </script>
@endpush
