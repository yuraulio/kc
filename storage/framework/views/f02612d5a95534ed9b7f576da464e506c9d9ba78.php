<div class="accordion accord_topic" id="accordionExample">
    <?php $__currentLoopData = $topics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $topic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $topic = $topic->first(); ?>
        <?php $status=""; ?>
        <?php $__currentLoopData = $event['topic']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topic_db): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($topic['id'] == $topic_db->id): ?>
                <?php $status="active"; ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <div class="card">
        <div class="row">
            <div class="card-header col-10" id="<?php echo e($key); ?>" data-toggle="collapse" data-target="#col_<?php echo e($key); ?>" aria-expanded="false" aria-controls="collapseOne">
                <h5 class="mb-0"><?php echo e($topic->title); ?></h5>
            </div>

            <div class="col-2 assign-toggle" id="toggle_<?php echo e($key); ?>">
                <label class="custom-toggle custom-published">
                    <input data-event-status="<?= ($status == 'active') ? '1' : '0'; ?>" type="checkbox" data-event-id="<?php echo e($event['id']); ?>" data-topic-id="<?php echo e($topic['id']); ?>" checked >
                    <span class="topic custom-toggle-slider rounded-circle" data-label-off="unassign" data-label-on="assigned" ></span>
                </label>
            </div>
        </div>
        <div id="col_<?php echo e($key); ?>" class="collapse" aria-labelledby="<?php echo e($key); ?>" data-parent="#accordionExample">
            <div class="card-body">
                <div class="table-responsive py-4">
                    <table class="table align-items-center table-flush lessons-table" >
                        <thead class="thead-light">
                            <tr>
                                <th scope="col"><?php echo e(__('Lesson')); ?></th>
                                <th scope="col"><?php echo e(__('Instructor')); ?></th>
                                <?php if(count($event->type) > 0 && $isInclassCourse): ?>
                                    <th scope="col"><?php echo e(__('Date')); ?></th>
                                    <th scope="col"><?php echo e(__('Time starts')); ?></th>
                                    <th scope="col"><?php echo e(__('Time ends')); ?></th>
                                    <th scope="col"><?php echo e(__('Room')); ?></th>
                                <?php endif; ?>


                                    <th scope="col"></th>

                            </tr>

                        </thead>
                        <tbody id="topic_lessons"  class="lessons-order" data-event-id="<?php echo e($event['id']); ?>">
                            <?php $i=0; ?>

                                <?php if(isset($lessons[$key])): ?>
                                <?php $__currentLoopData = $lessons[$key]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key1 => $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr id="<?php echo e($lesson['id']); ?>" class="topic_<?php echo e($topic->id); ?> lessons-list">
                                <td><a class="edit_btn_topic1" href="#"><?php echo e($lesson->title); ?></td>
                                <?php //dd($instructors[$lesson->id]->first()); ?>
                                <td id="inst_lesson_edit_<?php echo e($lesson['id']); ?>">

                                    <?php
                                        if(isset($instructors[$lesson->id]) && $instructors[$lesson->id]->first() != null)
                                        {
                                            //dd($instructors[$lesson->id]->first()['mediable'][]);
                                    ?>
                                    <span style="display:inline-block" class="avatar avatar-sm rounded-circle">
                                        <img src="<?= asset(get_image($instructors[$lesson->id]->first()['mediable'], 'instructors-small')); ?>" alt="<?php echo e($user['firstname']); ?>" style="max-width: 100px; max-height: 40px; border-radius: 25px">

                                    </span>

                                    <div style="display:inline-block"><?php echo e($instructors[$lesson->id]->first()['title']); ?> <?php echo e($instructors[$lesson->id]->first()['subtitle']); ?></div>
                                <?php
                                }else{
                                    echo '-';
                                } ?></td>
                                    <?php if(count($event['type']) > 0 && $isInclassCourse ): ?>
                                    <?php if($lesson->pivot->date != null): ?>
                                    <td id="date_lesson_edit_<?php echo e($lesson['id']); ?>"><?php $date = strtotime($lesson->pivot->date);  ?><?php echo e(date('d-m-Y', $date )); ?> </td>
                                    <td id="start_lesson_edit_<?php echo e($lesson['id']); ?>"><?php if($lesson->pivot->time_starts != null){$start = strtotime($lesson->pivot->time_starts);}else{ $start = "";} ?><?php  if($start != ""){ echo date('H:i:s', $start );} ?></td>
                                    <td id="end_lesson_edit_<?php echo e($lesson['id']); ?>"><?php if($lesson->pivot->time_ends != null){ $ends = strtotime($lesson->pivot->time_ends);}else{$ends = "";} ?><?php if($ends != ""){ echo date('H:i:s', $ends );}?></td>
                                    <td id="room_lesson_edit_<?php echo e($lesson['id']); ?>"><?php echo e($lesson->pivot->room); ?></td>
                                    <?php else: ?>
                                    <?php if($lesson->pivot->time_starts == null): ?>
                                    <td id="date_lesson_edit_<?php echo e($lesson['id']); ?>"></td>
                                    <td id="start_lesson_edit_<?php echo e($lesson['id']); ?>"></td>
                                    <td id="end_lesson_edit_<?php echo e($lesson['id']); ?>"></td>
                                    <td id="room_lesson_edit_<?php echo e($lesson['id']); ?>"></td>
                                    <?php else: ?>
                                    <td id="date_lesson_edit_<?php echo e($lesson['id']); ?>"><?php $date = strtotime($lesson->pivot->time_starts);  ?><?php echo e(date('d-m-Y', $date )); ?> </td>
                                    <td id="start_lesson_edit_<?php echo e($lesson['id']); ?>"><?php if($lesson->pivot->time_starts != null){$start = strtotime($lesson->pivot->time_starts);}else{ $start = "";} ?><?php  if($start != ""){ echo date('H:i:s', $start );} ?></td>
                                    <td id="end_lesson_edit_<?php echo e($lesson['id']); ?>"><?php if($lesson->pivot->time_ends != null){ $ends = strtotime($lesson->pivot->time_ends);}else{$ends = "";} ?><?php if($ends != ""){ echo date('H:i:s', $ends );}?></td>
                                    <td id="room_lesson_edit_<?php echo e($lesson['id']); ?>"><?php echo e($lesson->pivot->room); ?></td>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    <?php endif; ?>

                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a href="javascript:void(0)" id="open_modal" data-topic-id="topic_<?php echo e($topic->id); ?>"  data-lesson-id="lesson_<?php echo e($lesson->id); ?>" class="dropdown-item open_modal"><?php echo e(__('Edit')); ?></a>
                                            <a href="javascript:void(0)" id="remove_lesson" data-topic-id="topic_<?php echo e($topic->id); ?>"  data-lesson-id="lesson_<?php echo e($lesson->id); ?>" class="dropdown-item"><?php echo e(__('Delete')); ?></a>
                                            </div>
                                        </div>
                                    </td>
                            </tr>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <!-- //unssigned -->

    <?php $__currentLoopData = $unassigned; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $topic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php //dd($topic);
        //$topic = $topic->first() ?>
        <?php $status=""; ?>
    <div class="card">
        <div class="row">
            <div class="card-header col-10" id="<?php echo e($key); ?>" data-toggle="collapse" data-target="#col_<?php echo e($key); ?>" aria-expanded="false" aria-controls="collapseOne">
                <h5 class="mb-0"><?php echo e($topic->title); ?></h5>
            </div>

            <div class="col-2 assign-toggle" id="toggle_<?php echo e($key); ?>">
                <label class="custom-toggle custom-published">

                    <input data-event-status="0" type="checkbox" data-event-id="<?php echo e($event['id']); ?>" data-topic-id="<?php echo e($topic['id']); ?>"  >
                    <span class="topic custom-toggle-slider rounded-circle" data-label-off="unassign" data-label-on="assigned" ></span>
                </label>
            </div>
        </div>
        <div id="col_<?php echo e($key); ?>" class="collapse" aria-labelledby="<?php echo e($key); ?>" data-parent="#accordionExample">
            <div class="card-body">
                <div class="table-responsive py-4">
                    <table class="table align-items-center table-flush"  id="datatable-basic42">
                        <thead class="thead-light">
                            <?php //dd($isInclassCourse); ?>
                            <tr>
                                <th scope="col"><?php echo e(__('Lesson')); ?></th>
                                <th scope="col"><?php echo e(__('Instructor')); ?></th>
                                <?php if(count($event->type) > 0 && $isInclassCourse): ?>
                                    <th scope="col"><?php echo e(__('Date')); ?></th>
                                    <th scope="col"><?php echo e(__('Time starts')); ?></th>
                                    <th scope="col"><?php echo e(__('Time ends')); ?></th>
                                    <th scope="col"><?php echo e(__('Duration')); ?></th>
                                    <th scope="col"><?php echo e(__('Room')); ?></th>
                                <?php endif; ?>


                                    <th scope="col"></th>

                            </tr>

                        </thead>
                        <tbody id="topic_lessons" data-event-id="<?php echo e($event['id']); ?>">
                            <?php $i=0; ?>
                                <?php $__currentLoopData = $topic->lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key1 => $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php //dd($lesson);?>
                                <tr id="<?php echo e($lesson['id']); ?>" class="topic_<?php echo e($topic->id); ?>">
                                <td><a class="edit_btn_topic1" href="#"><?php echo e($lesson->title); ?></a></td>

                                <td id="inst_lesson_edit_<?php echo e($lesson['id']); ?>"><?php if(isset($instructors[$lesson->id]) && $instructors[$lesson->id]->first() != null)
                                {
                                    echo $instructors[$lesson->id]->first()['title'].' '.$instructors[$lesson->id]->first()['subtitle'];
                                }else{
                                    echo '-';
                                } ?></td>
                                    <?php if(count($event['type']) > 0 && $isInclassCourse ): ?>
                                    <?php if($lesson->pivot->date != null): ?>
                                    <td id="date_lesson_edit_<?php echo e($lesson['id']); ?>"><?php $date = strtotime($lesson->pivot->date);  ?><?php echo e(date('d-m-Y', $date )); ?> </td>
                                    <td id="start_lesson_edit_<?php echo e($lesson['id']); ?>"><?php dd($lesson->pivot->time_starts); if($lesson->pivot->time_starts != null){$start = strtotime($lesson->pivot->time_starts);}else{ $start = "";} ?><?=  date('H:i:s', $start ) ?></td>
                                    <td id="end_lesson_edit_<?php echo e($lesson['id']); ?>"><?php $ends = strtotime($lesson->pivot->time_ends); ?><?php echo e(date('H:i:s', $ends )); ?></td>
                                    <td id="duration_lesson_edit_<?php echo e($lesson['id']); ?>"><?php echo e($lesson->pivot->duration); ?></td>
                                    <td id="room_lesson_edit_<?php echo e($lesson['id']); ?>"><?php echo e($lesson->pivot->room); ?></td>
                                    <?php else: ?>
                                    <td id="date_lesson_edit_<?php echo e($lesson['id']); ?>"></td>
                                    <td id="start_lesson_edit_<?php echo e($lesson['id']); ?>"></td>
                                    <td id="end_lesson_edit_<?php echo e($lesson['id']); ?>"></td>
                                    <td id="duration_lesson_edit_<?php echo e($lesson['id']); ?>"></td>
                                    <td id="room_lesson_edit_<?php echo e($lesson['id']); ?>"></td>
                                    <?php endif; ?>
                                    <?php endif; ?>

                                    <td class="text-right">
                                        <div class="dropdown <?= ($status == 'active') ? '' : 'd-none'; ?>">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a href="javascript:void(0)" id="open_modal" data-topic-id="topic_<?php echo e($topic->id); ?>"  data-lesson-id="lesson_<?php echo e($lesson->id); ?>" class="dropdown-item open_modal"><?php echo e(__('Edit')); ?></a>
                                            <a href="javascript:void(0)" id="remove_lesson" data-topic-id="topic_<?php echo e($topic->id); ?>"  data-lesson-id="lesson_<?php echo e($lesson->id); ?>" class="dropdown-item"><?php echo e(__('Delete')); ?></a>
                                            </div>
                                        </div>
                                    </td>
                            </tr>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php $__env->startPush('js'); ?>
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
            url: "<?php echo e(route ('events.assign_store')); ?>",
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


$(document).ready( function () {
    $('.lessons-table').dataTable( {
        "ordering": false,
        "paging": false
    });
});
</script>

<script src="<?php echo e(asset('js/sortable/Sortable.js')); ?>"></script>
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
            url: "<?php echo e(route ('sort-lessons', $event->id)); ?>",
            data:lessons,
            success: function(data) {


            }
        });
    }

</script>

<?php $__env->stopPush(); ?>



<?php /**PATH C:\laragon\www\kcversion8\resources\views/topics/event/instructors.blade.php ENDPATH**/ ?>