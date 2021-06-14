<div class="accordion accord_topic" id="accordionExample">

    @foreach($topics as $key => $topic)
        <?php $topic = $topic->first() ?>
        <?php $status=""; ?>
        @foreach($event['topic'] as $topic_db)
            @if($topic['id'] == $topic_db->id)
                <?php $status="active"; ?>
                <?php //dd($topic); ?>
            @endif
        @endforeach
    <div class="card">
        <div class="row">
            <div class="card-header col-10" id="{{$key}}" data-toggle="collapse" data-target="#col_{{$key}}" aria-expanded="true" aria-controls="collapseOne">
                <h5 class="mb-0">{{$topic->title}}</h5>
            </div>

            <div class="col-2 assign-toggle" id="toggle_{{$key}}">
                <label class="custom-toggle">
                    <input class="<?= ($status == 'active') ? 'active' : ''; ?>" id="assign-toogle" type="checkbox" data-event-id="{{$event['id']}}" data-topic-id="{{$topic['id']}}" <?= ($status == 'active') ? 'checked' : ''; ?> >
                    <span class="custom-toggle-slider rounded-circle" ></span>
                </label>
            </div>
        </div>
        <div id="col_{{$key}}" class="collapse" aria-labelledby="{{$key}}" data-parent="#accordionExample">
            <div class="card-body">
                <div class="table-responsive py-4">
                    <table class="table align-items-center table-flush lessons-table" >
                        <thead class="thead-light">
                            <?php //dd($isInclassCourse); ?>
                            <tr>
                                <th scope="col">{{ __('Lesson') }}</th>
                                <th scope="col">{{ __('Instructor') }}</th>
                                @if(count($event->type) > 0 && $isInclassCourse)
                                    <th scope="col">{{ __('Date') }}</th>
                                    <th scope="col">{{ __('Time starts') }}</th>
                                    <th scope="col">{{ __('Time ends') }}</th>
                                    <th scope="col">{{ __('Duration') }}</th>
                                    <th scope="col">{{ __('Room') }}</th>
                                @endif


                                    <th scope="col"></th>

                            </tr>

                        </thead>
                        <tbody id="topic_lessons"  class="lessons-order" data-event-id="{{$event['id']}}">
                            <?php $i=0; ?>
                                @foreach($lessons[$key] as $key1 => $lesson)
                                <?php //dd($lesson);?>
                                <tr id="{{$lesson['id']}}" class="topic_{{$topic->id}} lessons-list">
                                <td>{{ $lesson->title }}</td>

                                <td id="inst_lesson_edit_{{$lesson['id']}}"><?php if(isset($instructors[$lesson->id]) && $instructors[$lesson->id]->first() != null)
                                {
                                    echo $instructors[$lesson->id]->first()['title'].' '.$instructors[$lesson->id]->first()['subtitle'];
                                }else{
                                    echo '-';
                                } ?></td>
                                    @if(count($event['type']) > 0 && $isInclassCourse )
                                    @if($lesson->pivot->date != null)
                                    <td id="date_lesson_edit_{{$lesson['id']}}"><?php $date = strtotime($lesson->pivot->date);  ?>{{ date('d-m-Y', $date ) }} </td>
                                    <td id="start_lesson_edit_{{$lesson['id']}}"><?php $start = strtotime($lesson->pivot->time_starts); ?><?=  date('H:i:s', $start ) ?></td>
                                    <td id="end_lesson_edit_{{$lesson['id']}}"><?php $ends = strtotime($lesson->pivot->time_ends); ?>{{ date('H:i:s', $ends ) }}</td>
                                    <td id="duration_lesson_edit_{{$lesson['id']}}">{{$lesson->pivot->duration}}</td>
                                    <td id="room_lesson_edit_{{$lesson['id']}}">{{$lesson->pivot->room}}</td>
                                    @else
                                    <td id="date_lesson_edit_{{$lesson['id']}}"></td>
                                    <td id="start_lesson_edit_{{$lesson['id']}}"></td>
                                    <td id="end_lesson_edit_{{$lesson['id']}}"></td>
                                    <td id="duration_lesson_edit_{{$lesson['id']}}"></td>
                                    <td id="room_lesson_edit_{{$lesson['id']}}"></td>
                                    @endif
                                    @endif

                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a href="javascript:void(0)" id="open_modal" data-topic-id="topic_{{$topic->id}}"  data-lesson-id="lesson_{{ $lesson->id }}" class="dropdown-item open_modal">{{ __('Edit') }}</a>
                                            <a href="javascript:void(0)" id="remove_lesson" data-topic-id="topic_{{$topic->id}}"  data-lesson-id="lesson_{{ $lesson->id }}" class="dropdown-item">{{ __('Delete') }}</a>
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
    @endforeach

    <!-- //unssigned -->

    @foreach($unassigned as $key => $topic)
        <?php //dd($topic);
        //$topic = $topic->first() ?>
        <?php $status=""; ?>
    <div class="card">
        <div class="row">
            <div class="card-header col-10" id="{{$key}}" data-toggle="collapse" data-target="#col_{{$key}}" aria-expanded="true" aria-controls="collapseOne">
                <h5 class="mb-0">{{$topic->title}}</h5>
            </div>

            <div class="col-2 assign-toggle" id="toggle_{{$key}}">
                <label class="custom-toggle">
                    <input class="" id="assign-toogle" type="checkbox" data-event-id="{{$event['id']}}" data-topic-id="{{$topic['id']}}" <?= ($status == 'active') ? 'checked' : ''; ?> >
                    <span class="custom-toggle-slider rounded-circle" ></span>
                </label>
            </div>
        </div>
        <div id="col_{{$key}}" class="collapse" aria-labelledby="{{$key}}" data-parent="#accordionExample">
            <div class="card-body">
                <div class="table-responsive py-4">
                    <table class="table align-items-center table-flush"  id="datatable-basic">
                        <thead class="thead-light">
                            <?php //dd($isInclassCourse); ?>
                            <tr>
                                <th scope="col">{{ __('Lesson') }}</th>
                                <th scope="col">{{ __('Instructor') }}</th>
                                @if(count($event->type) > 0 && $isInclassCourse)
                                    <th scope="col">{{ __('Date') }}</th>
                                    <th scope="col">{{ __('Time starts') }}</th>
                                    <th scope="col">{{ __('Time ends') }}</th>
                                    <th scope="col">{{ __('Duration') }}</th>
                                    <th scope="col">{{ __('Room') }}</th>
                                @endif


                                    <th scope="col"></th>

                            </tr>

                        </thead>
                        <tbody id="topic_lessons" data-event-id="{{$event['id']}}">
                            <?php $i=0; ?>
                                @foreach($topic->lessons as $key1 => $lesson)
                                <?php //dd($lesson);?>
                                <tr id="{{$lesson['id']}}" class="topic_{{$topic->id}}">
                                <td>{{ $lesson->title }}</td>

                                <td id="inst_lesson_edit_{{$lesson['id']}}"><?php if(isset($instructors[$lesson->id]) && $instructors[$lesson->id]->first() != null)
                                {
                                    echo $instructors[$lesson->id]->first()['title'].' '.$instructors[$lesson->id]->first()['subtitle'];
                                }else{
                                    echo '-';
                                } ?></td>
                                    @if(count($event['type']) > 0 && $isInclassCourse )
                                    @if($lesson->pivot->date != null)
                                    <td id="date_lesson_edit_{{$lesson['id']}}"><?php $date = strtotime($lesson->pivot->date);  ?>{{ date('d-m-Y', $date ) }} </td>
                                    <td id="start_lesson_edit_{{$lesson['id']}}"><?php $start = strtotime($lesson->pivot->time_starts); ?><?=  date('H:i:s', $start ) ?></td>
                                    <td id="end_lesson_edit_{{$lesson['id']}}"><?php $ends = strtotime($lesson->pivot->time_ends); ?>{{ date('H:i:s', $ends ) }}</td>
                                    <td id="duration_lesson_edit_{{$lesson['id']}}">{{$lesson->pivot->duration}}</td>
                                    <td id="room_lesson_edit_{{$lesson['id']}}">{{$lesson->pivot->room}}</td>
                                    @else
                                    <td id="date_lesson_edit_{{$lesson['id']}}"></td>
                                    <td id="start_lesson_edit_{{$lesson['id']}}"></td>
                                    <td id="end_lesson_edit_{{$lesson['id']}}"></td>
                                    <td id="duration_lesson_edit_{{$lesson['id']}}"></td>
                                    <td id="room_lesson_edit_{{$lesson['id']}}"></td>
                                    @endif
                                    @endif

                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a href="javascript:void(0)" id="open_modal" data-topic-id="topic_{{$topic->id}}"  data-lesson-id="lesson_{{ $lesson->id }}" class="dropdown-item open_modal">{{ __('Edit') }}</a>
                                            <a href="javascript:void(0)" id="remove_lesson" data-topic-id="topic_{{$topic->id}}"  data-lesson-id="lesson_{{ $lesson->id }}" class="dropdown-item">{{ __('Delete') }}</a>
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
    @endforeach
</div>

@push('js')
<script>
    $(document).on('change', '#assign-toogle', function(){
        let current_status = $(this).hasClass('active')
        let id = $(this).data('topic-id')
        let event_id = $(this).data('event-id')

        if(current_status == true){
            status = false
        }else{
            status = true
        }

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
    console.log(event_type)
    let e = $('#'+data.request.topic_id).find('label')

    let topic = data.lesson;
    let lessons = data.lesson.lessons


        if(data.request.status1 == "true")
        {
            $('#toggle_'+topic.id).find('input').addClass('active')
        }
        else if(data.request.status1 == "false")
        {
            $('#toggle_'+topic.id).find('input').removeClass('active')
        }

                }
            });

    })

</script>
<script>


$(document).ready( function () {
    $('.lessons-table').dataTable( {
        "ordering": false,
        "paging": false
    });
});
</script>

<script src="{{ asset('js/sortable/Sortable.js') }}"></script>
<script>
    (function( $ ){
        var el

        $( ".lessons-order" ).each(function( index ) {



            el = document.getElementsByClassName('lessons-order')[index];
            //var el = document.getElementsByClassName('lessons-order')[0];
            //var el = document.getElementsByClassName('lessons-table')[0];
            //var el = document.getElementById('lessons-order');



            new Sortable(el, {
               group: "words",
               handle: ".my-handle",
               draggable: ".item",
               ghostClass: "sortable-ghost",

            });

            new Sortable(el, {

                // Element dragging ended
                onEnd: function ( /**Event*/ evt) {

                    let lessons = {};

                    $( ".lessons-list" ).each(function( index ) {
                        lessons[$(this).attr('id')] = index
                    });
                    console.log(lessons);


                    $.ajax({
                        type: 'POST',
                        headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        Accept: 'application/json',
                        url: "{{ route ('sort-lessons', $event->id) }}",
                        data:lessons,
                        success: function(data) {


                        }
                    });




                },
            });

        });


    })( jQuery );

</script>

@endpush



