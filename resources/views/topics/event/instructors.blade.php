<div class="accordion accord_topic" id="accordionExample">
    @foreach($topics as $key => $topic)
        <?php $topic = $topic->first(); ?>
        <?php $status=""; ?>
        @foreach($event['topic'] as $topic_db)
            @if($topic['id'] == $topic_db->id)
                <?php $status="active"; ?>
            @endif
        @endforeach
    <div class="card">
        <div class="row">
            <div class="card-header col-10" id="{{$key}}" data-toggle="collapse" data-target="#col_{{$key}}" aria-expanded="true" aria-controls="collapseOne">
                <h5 class="mb-0">{{$topic->title}}</h5>
            </div>

            <div class="col-2 assign-toggle" id="toggle_{{$key}}">
                <label class="custom-toggle">
                    <input data-event-status="<?= ($status == 'active') ? '1' : '0'; ?>" type="checkbox" data-event-id="{{$event['id']}}" data-topic-id="{{$topic['id']}}" checked >
                    <span class="topic custom-toggle-slider rounded-circle" ></span>
                </label>
            </div>
        </div>
        <div id="col_{{$key}}" class="collapse" aria-labelledby="{{$key}}" data-parent="#accordionExample">
            <div class="card-body">
                <div class="table-responsive py-4">
                    <table class="table align-items-center table-flush lessons-table" >
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">{{ __('Lesson') }}</th>
                                <th scope="col">{{ __('Instructor') }}</th>
                                @if(count($event->type) > 0 && $isInclassCourse)
                                    <th scope="col">{{ __('Date') }}</th>
                                    <th scope="col">{{ __('Time starts') }}</th>
                                    <th scope="col">{{ __('Time ends') }}</th>
                                    <th scope="col">{{ __('Room') }}</th>
                                @endif


                                    <th scope="col"></th>

                            </tr>

                        </thead>
                        <tbody id="topic_lessons"  class="lessons-order" data-event-id="{{$event['id']}}">
                            <?php $i=0; ?>
                                @foreach($lessons[$key] as $key1 => $lesson)
                                <tr id="{{$lesson['id']}}" class="topic_{{$topic->id}} lessons-list">
                                <td><a class="edit_btn_topic1" href="#">{{ $lesson->title }}</td>
                                <?php //dd($instructors[$lesson->id]->first()); ?>
                                <td id="inst_lesson_edit_{{$lesson['id']}}">

                                    <?php
                                        if(isset($instructors[$lesson->id]) && $instructors[$lesson->id]->first() != null)
                                        {
                                            //dd($instructors[$lesson->id]->first()['mediable'][]);
                                    ?>
                                    <span style="display:inline-block" class="avatar avatar-sm rounded-circle">
                                        <img src="<?= asset(get_image($instructors[$lesson->id]->first()['mediable'], 'instructors-small')); ?>" alt="{{ $user['firstname'] }}" style="max-width: 100px; max-height: 40px; border-radius: 25px">

                                    </span>

                                    <div style="display:inline-block">{{$instructors[$lesson->id]->first()['title']}} {{$instructors[$lesson->id]->first()['subtitle']}}</div>
<?php
                                }else{
                                    echo '-';
                                } ?></td>
                                    @if(count($event['type']) > 0 && $isInclassCourse )
                                    @if($lesson->pivot->date != null)
                                    <td id="date_lesson_edit_{{$lesson['id']}}"><?php $date = strtotime($lesson->pivot->date);  ?>{{ date('d-m-Y', $date ) }} </td>
                                    <td id="start_lesson_edit_{{$lesson['id']}}"><?php if($lesson->pivot->time_starts != null){$start = strtotime($lesson->pivot->time_starts);}else{ $start = "";} ?><?php  if($start != ""){ echo date('H:i:s', $start );} ?></td>
                                    <td id="end_lesson_edit_{{$lesson['id']}}"><?php if($lesson->pivot->time_ends != null){ $ends = strtotime($lesson->pivot->time_ends);}else{$ends = "";} ?><?php if($ends != ""){ echo date('H:i:s', $ends );}?></td>
                                    <td id="room_lesson_edit_{{$lesson['id']}}">{{$lesson->pivot->room}}</td>
                                    @else
                                    @if($lesson->pivot->time_starts == null)
                                    <td id="date_lesson_edit_{{$lesson['id']}}"></td>
                                    <td id="start_lesson_edit_{{$lesson['id']}}"></td>
                                    <td id="end_lesson_edit_{{$lesson['id']}}"></td>
                                    <td id="room_lesson_edit_{{$lesson['id']}}"></td>
                                    @else
                                    <td id="date_lesson_edit_{{$lesson['id']}}"><?php $date = strtotime($lesson->pivot->time_starts);  ?>{{ date('d-m-Y', $date ) }} </td>
                                    <td id="start_lesson_edit_{{$lesson['id']}}"><?php if($lesson->pivot->time_starts != null){$start = strtotime($lesson->pivot->time_starts);}else{ $start = "";} ?><?php  if($start != ""){ echo date('H:i:s', $start );} ?></td>
                                    <td id="end_lesson_edit_{{$lesson['id']}}"><?php if($lesson->pivot->time_ends != null){ $ends = strtotime($lesson->pivot->time_ends);}else{$ends = "";} ?><?php if($ends != ""){ echo date('H:i:s', $ends );}?></td>
                                    <td id="room_lesson_edit_{{$lesson['id']}}">{{$lesson->pivot->room}}</td>
                                    @endif
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
                    <input data-event-status="0" type="checkbox" data-event-id="{{$event['id']}}" data-topic-id="{{$topic['id']}}" <?= ($status == 'active') ? 'checked' : ''; ?> >
                    <span class="topic custom-toggle-slider rounded-circle" ></span>
                </label>
            </div>
        </div>
        <div id="col_{{$key}}" class="collapse" aria-labelledby="{{$key}}" data-parent="#accordionExample">
            <div class="card-body">
                <div class="table-responsive py-4">
                    <table class="table align-items-center table-flush"  id="datatable-basic42">
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
                                <td><a class="edit_btn_topic1" href="#">{{ $lesson->title }}</a></td>

                                <td id="inst_lesson_edit_{{$lesson['id']}}"><?php if(isset($instructors[$lesson->id]) && $instructors[$lesson->id]->first() != null)
                                {
                                    echo $instructors[$lesson->id]->first()['title'].' '.$instructors[$lesson->id]->first()['subtitle'];
                                }else{
                                    echo '-';
                                } ?></td>
                                    @if(count($event['type']) > 0 && $isInclassCourse )
                                    @if($lesson->pivot->date != null)
                                    <td id="date_lesson_edit_{{$lesson['id']}}"><?php $date = strtotime($lesson->pivot->date);  ?>{{ date('d-m-Y', $date ) }} </td>
                                    <td id="start_lesson_edit_{{$lesson['id']}}"><?php dd($lesson->pivot->time_starts); if($lesson->pivot->time_starts != null){$start = strtotime($lesson->pivot->time_starts);}else{ $start = "";} ?><?=  date('H:i:s', $start ) ?></td>
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
                                        <div class="dropdown <?= ($status == 'active') ? '' : 'd-none'; ?>">
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

    $(document).on('click',".edit_btn_topic1",function(){
        let a = $(this).parent().parent().find('.open_modal').click()
    })




    $(document).on('click', '.topic.custom-toggle-slider', function(){
        //alert($($(this).parent().find('input')).data('topicId'))
        let id = $($(this).parent().find('input')).data('topicId')
        let event_id = $($(this).parent().find('input')).data('eventId')



        let status = $('#toggle_'+id).find('input').attr('data-event-status')

        let elements = $('#col_'+id).find('tr')

        console.log(status)

        if(status == '1'){


            $.each(elements, function(key, value) {
                $(value).find('.dropdown').addClass('d-none')
                $(value).find('.open_modal').addClass('d-none')
            })
        }else{
            $.each(elements, function(key, value) {
                $(value).find('.dropdown').removeClass('d-none')
                $(value).find('.open_modal').addClass('d-none')
            })
        }
        //console.log(status)


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
                // console.log(data.request.status1)
                // console.log(data.request.topic_id)
                let elem = $('#toggle_'+data.request.topic_id).find('input')[0]
                console.log(elem)
                if(data.request.status1 == "1"){
                    console.log('from flase')
                    $(elem).attr("data-event-status", "1")
                }else{
                    console.log($(elem))
                    console.log('from truw')
                    $(elem).attr("data-event-status", "0")
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


                    orderLessons()



                },
            });

        });

        el = document.getElementById('accordionExample');

        new Sortable(el, {
           group: "words",
           handle: ".my-handle",
           draggable: ".item",
           ghostClass: "sortable-ghost",

        });

        new Sortable(el, {

            // Element dragging ended
            onEnd: function ( /**Event*/ evt) {

                orderLessons()


            },
        });


    })( jQuery );


    function orderLessons(){
        let lessons = {};

        $( ".lessons-list" ).each(function( index ) {
            lessons[$(this).attr('id')] = index
        });

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
    }

</script>

@endpush



