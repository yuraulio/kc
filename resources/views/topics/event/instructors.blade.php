
<?php 

    $id = isset($sections['topics'][0]) ? $sections['topics'][0]['id'] : '';
    $tab_title = isset($sections['topics'][0]) ? $sections['topics'][0]['tab_title'] : '' ;
    $title = isset($sections['topics'][0]) ? $sections['topics'][0]['title'] : '' ;
    $visible = isset($sections['topics'][0]) ? $sections['topics'][0]['visible'] : false ;
                                            
?> 

<div class="form-group">

   <input hidden name="sections[topics][id]" value="{{$id}}"> 

   <label class="form-control-label" for="input-title">{{ __('Tab Title') }}</label>
   <input type="text" name="sections[topics][tab_title]" class="form-control" placeholder="{{ __('Tab Title') }}" value="{{ old("sections[topics][tab_title]", $tab_title) }}" autofocus> 
   <label class="form-control-label" for="input-title">{{ __('H2 Title') }}</label>
   <input type="text" name="sections[topics][title]" class="form-control" placeholder="{{ __('H2 Title') }}" value="{{ old("sections[topics][title]", $title) }}" autofocus>


   <label class="form-control-label" for="input-method">{{ __('Visible') }}</label>
   <div style="margin: auto;" class="form-group">

       <label class="custom-toggle enroll-toggle visible">
           <input type="checkbox"  name="sections[topics][visible]" @if($visible) checked @endif>
           <span class="custom-toggle-slider rounded-circle" data-label-off="no visible" data-label-on="visible"></span>
       </label>

   </div>
                                

</div>


<div class="accordion accord_topic" id="accordionExample">
    @foreach($topics as $key => $topic)
        <?php $topic = $topic->first();?>
        <?php $status=""; ?>
        @foreach($event['topic'] as $topic_db)
            @if($topic['id'] == $topic_db->id)
                <?php $status="active"; ?>
            @endif
        @endforeach
    <div class="card">
        <div class="row">
            <div class="card-header @if($isInclassCourse) col-9 @else col-10 @endif " id="{{$key}}" data-toggle="collapse" data-target="#col_{{$key}}" aria-expanded="false" aria-controls="collapseOne">
                <h5 class="mb-0">{{$topic->title}}</h5>
            </div>

            <div class=" @if($isInclassCourse) col-1 @else col-2 @endif  assign-toggle" id="toggle_{{$key}}">
                <label class="custom-toggle custom-published">
                    <input data-event-status="<?= ($status == 'active') ? '1' : '0'; ?>" type="checkbox" data-event-id="{{$event['id']}}" data-topic-id="{{$topic['id']}}" checked >
                    <span class="topic custom-toggle-slider rounded-circle" data-label-off="unassign" data-label-on="assigned" ></span>
                </label>
            </div>
            @if($isInclassCourse)
            <div class="col-2 assign-toggle automate-mail" id="toggle_automate_mail_{{$key}}">
                <label class="custom-toggle custom-published">
                    <input type="checkbox" data-email-template="{{$topic['email_template']}}" data-checked="@if(isset($topic['pivot']['automate_mail']) && $topic['pivot']['automate_mail']) $topic['pivot']['automate_mail'] @else 0 @endif" data-event-id="{{$event['id']}}" data-topic-id="{{$topic['id']}}" @if(isset($topic['pivot']['automate_mail']) && $topic['pivot']['automate_mail']) checked @endif>
                    <span class="automate-mail custom-toggle-slider rounded-circle" data-label-on="no automate mail" data-label-off="automate mail" ></span>
                </label>
            </div>
            @endif
        </div>
        <div id="col_{{$key}}" class="collapse" aria-labelledby="{{$key}}" data-parent="#accordionExample">
            <div class="card-body">
                <div class="table-responsive py-4">
                    <table class="table align-items-center table-flush lessons-table" >
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">{{ __('Lesson') }}</th>
                                <th scope="col">{{ __('Instructor') }}</th>
                                {{--@if(count($event->type) > 0 && $isInclassCourse)--}}
                                @if($isInclassCourse)
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

                                @if(isset($lessons[$key]))
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
                                    {{--@if(count($event['type']) > 0 && $isInclassCourse )--}}
                                    @if($isInclassCourse )
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
                                @endif
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
            <div class="card-header col-10" id="{{$key}}" data-toggle="collapse" data-target="#col_{{$key}}" aria-expanded="false" aria-controls="collapseOne">
                <h5 class="mb-0">{{$topic->title}}</h5>
            </div>

            <div class="col-2 assign-toggle" id="toggle_{{$key}}">
                <label class="custom-toggle custom-published">

                    <input data-event-status="0" type="checkbox" data-event-id="{{$event['id']}}" data-topic-id="{{$topic['id']}}"  >
                    <span class="topic custom-toggle-slider rounded-circle" data-label-off="unassign" data-label-on="assigned" ></span>
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
                                    <td id="start_lesson_edit_{{$lesson['id']}}"><?php if($lesson->pivot->time_starts != null){$start = strtotime($lesson->pivot->time_starts);}else{ $start = "";} ?><?=  date('H:i:s', $start ) ?></td>
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
                if(data.request.status1 == "1"){
                    $(elem).attr("data-event-status", "1")
                }else{
                    $(elem).attr("data-event-status", "0")
                }
            }
        });

    })

</script>


<script>

$(document).on('click', '.automate-mail.custom-toggle-slider', function(){
        //alert($($(this).parent().find('input')).data('topicId'))
        let topic_id = $($(this).parent().find('input')).data('topicId')
        let event_id = $($(this).parent().find('input')).data('eventId')
        let status = $($(this).parent().find('input')).data('checked')
        let email_template = $($(this).parent().find('input')).data('email-template')
        
        if(status === 1){
            status = 0;
            $($(this).parent().find('input')).data('checked',status)
        }else{
            status = 1;
            $($(this).parent().find('input')).data('checked',status)
        }

        let data = {status:status, topic_id : topic_id, event_id : event_id, email_template: email_template}

        $.ajax({
            type: 'POST',
            headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            Accept: 'application/json',
            url: "{{ route ('topics.automate.mails.status') }}",
            data:data,
            success: function(data) {
               
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

{{--<script src="{{ asset('js/sortable/Sortable.js') }}"></script>
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

</script>--}}

@endpush



