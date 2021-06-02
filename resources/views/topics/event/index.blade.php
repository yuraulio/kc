


                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Topics') }}</h3>
                                <p class="text-sm mb-0">
                                        {{ __('This is an example of Topic management.') }}
                                    </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>
                    <?php ?>
                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="datatable-basic">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Title') }}</th>

                                    <th scope="col">{{ __('Assigned to event') }}</th>
                                </tr>
                            </thead>
                            <tbody id="topic_lessons" data-event-id="{{$event['id']}}">
                            <?php //dd($allTopicsByCategory->topics); ?>
                                @foreach ($allTopicsByCategory['topics'] as $topic)
                                <?php //dd($topic); ?>

                                    <tr>
                                        <td>{{ $topic->status }}</td>
                                        <td>{{ $topic->title }}</td>

                                        <td>
                                            <?php $status=""; ?>
                                            @foreach($event['topic'] as $topic_db)
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

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                    let event_type = data.isInclassCourse
                    let e = $('#'+data.request.topic_id).find('label')

                    let topic = data.lesson;
                    let lessons = data.lesson.lessons


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

                                let row = ``
                                if(data.request.status1 == "true"){

                                    if(event_type)
                                    {
                                        row = `
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        `
                                    }

                                        // new_topic_row = `
                                        //     <tr class="topic_${data.lesson.id}">
                                        //         <th rowspan="${data.lesson.lessons.length}">${data.lesson.title}</td>



                                        //     </tr>
                                        // `
                                        ///////////////////////////////////
                                        row_accor = `
                                        <div class="card" id="topic_card_${topic.id}">
                                            <div class="card-header" id="${topic.id}" data-toggle="collapse" data-target="#col_`+topic.id+`" aria-expanded="true" aria-controls="collapseOne">
                                                <h5 class="mb-0">${topic.title}</h5>
                                            </div>
                                            <div id="col_${topic.id}" class="collapse" aria-labelledby="${topic.id}" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="table-responsive py-4">
                                                    <table class="table align-items-center table-flush"  id="datatable-basic">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th scope="col">{{ __('Lesson') }}</th>
                                                                <th scope="col">{{ __('Instructor') }}</th>
                                                                ${row}
                                                                <th scope="col"></th>
                                                            </tr>

                                                        </thead>
                                                        <tbody id="topic_lessons_${topic.id}">


                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        `;
                                        $('.accord_topic').append(row_accor)

                                        $.each( lessons , function(key, value) {
                                            let row_lessons = `
                                                <tr id="inst_lesson_${value.id}" class="topic_`+value.id+`">
                                                <td>${value.title}</td>
                                                <td id="inst_lesson_edit_`+value.id+`">-</td>
                                                    ${row}
                                                    <td class="text-right">
                                                        <div class="dropdown">
                                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                <a href="javascript:void(0)" id="open_modal" data-topic-id="topic_${topic.id}"  data-lesson-id="lesson_${value.id}" class="dropdown-item open_modal">{{ __('Edit') }}</a>
                                                            <a href="javascript:void(0)" id="remove_lesson" data-topic-id="topic_${topic.id}"  data-lesson-id="lesson_${value.id}" class="dropdown-item">{{ __('Delete') }}</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            `
                                            $('#topic_lessons_'+topic.id).append(row_lessons)
                                        })

                                    let b = $('#inst_'+data.lesson.id).find('ul')
                                    let i = 0
                                    $.each( data.lesson['lessons'], function( key, value ) {
                                        let id = 0

                                        $.each( value, function( key1, value1 ) {

                                            let title = ""

                                            if(key1 == 'id')
                                            {
                                                id = value1;
                                            }

                                            if(key1 == 'title'){
                                                title = value1;
                                                //alert(title)
                                                lesson_row =`
                                                    <li>${title}</li>
                                                    `
                                            //$(b).append(lesson_row)

                                            action_row = `
                                            <td class="text-right">
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" style="">
                                                        <a href="javascript:void(0)" id="open_modal" data-topic-id="topic_${data.lesson.id}" data-lesson-id="lesson_${id}" class="dropdown-item open_modal">Edit</a>
                                                        <a href="javascript:void(0)" id="remove_lesson" data-topic-id="topic_${data.lesson.id}" data-lesson-id="lesson_${id}" class="dropdown-item">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                            `
                                            }


                                        });
                                    });
                                }else{

                                    $('#topic_card_'+data.lesson.id).remove()

                                }

                }
            });


        });

    </script>
@endpush
