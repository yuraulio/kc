<div class="row align-items-center">
    <div class="col-8">
        <h3 class="mb-0">{{ __('Instructors') }}</h3>

    </div>
</div>

<div class="accordion accord_topic" id="accordionExample">
    <?php //dd($allTopicsByCategory1); ?>
    @foreach($topics as $key => $topic)
        <?php $topic = $topic->first() ?>
    <div class="card">
        <div class="card-header" id="{{$key}}" data-toggle="collapse" data-target="#col_{{$key}}" aria-expanded="true" aria-controls="collapseOne">
            <h5 class="mb-0">{{$topic->title}}</h5>
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



                                @foreach($lessons[$key] as $key1 => $lesson)
                                <?php //dd($lesson);?>
                                <tr id="{{$lesson['id']}}" class="topic_{{$topic->id}}">
                                <td>{{ $lesson->title }}</td>

                                <td id="inst_lesson_edit_{{$lesson['id']}}"><?php if(isset($instructors[$lesson->id]) && $instructors[$lesson->id]->first() != null)
                                {
                                    echo $instructors[$lesson->id]->first()['title'];
                                }else{
                                    echo '-';
                                } ?></td>
                                    @if(count($event['type']) > 0 && $isInclassCourse )
                                    @if($lesson->pivot->date != null)
                                    <?php //dd('asd');?>
                                    <td id="date_lesson_edit_{{$lesson['id']}}"><?php $date = strtotime($lesson->pivot->date);  ?>{{ date('d-m-Y', $date ) }} </td>
                                    <td id="start_lesson_edit_{{$lesson['id']}}"><?php $start = strtotime($lesson->pivot->time_starts); ?><?=  date('H:i:sa', $start ) ?></td>
                                    <td id="end_lesson_edit_{{$lesson['id']}}"><?php $ends = strtotime($lesson->pivot->time_ends); ?>{{ date('H:i:sa', $ends ) }}</td>
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



