<div class="row align-items-center">
    <div class="col-8">
        <h3 class="mb-0">{{ __('Instructors') }}</h3>

    </div>
</div>

<div class="accordion" id="accordionExample">
    <?php //dd($allTopicsByCategory1); ?>
    @foreach ($allTopicsByCategory1 as $key => $topic1)
    <?php //dd($topic1); ?>
    <div class="card">
        <div class="card-header" id="{{$key}}" data-toggle="collapse" data-target="#col_{{$key}}" aria-expanded="true" aria-controls="collapseOne">
            <h5 class="mb-0">{{$topic1[0]['title']}}</h5>
        </div>
        <div id="col_{{$key}}" class="collapse show" aria-labelledby="{{$key}}" data-parent="#accordionExample">
            <div class="card-body">
                <div class="table-responsive py-4">
                    <table class="table align-items-center table-flush"  id="datatable-basic">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">{{ __('Lesson') }}</th>
                                <th scope="col">{{ __('Instructor') }}</th>
                                @if(count($event->type) > 0 && $isInclassCourse)
                                    <th scope="col">{{ __('Date') }}</th>
                                    <th scope="col">{{ __('Time starts') }}</th>
                                    <th scope="col">{{ __('Time ends') }}</th>
                                    <th scope="col">{{ __('Duration') }}</th>
                                    <th scope="col">{{ __('Room') }}</th>
                                    <th scope="col">{{ __('Priority') }}</th>
                                @endif

                                @can('manage-users', App\Model\User::class)
                                    <th scope="col"></th>
                                @endcan
                            </tr>

                        </thead>
                        <tbody id="topic_lessons" data-event-id="{{$event['id']}}">
                            <?php $i=0; ?>
                            @foreach($topic1 as $topic)

                            <tr class="topic_{{$topic->id}}">
                                @foreach($topic->lessons as $lesson)
                                @if($topic->getOriginal('pivot_lesson_id') == $lesson['id'])
                                <td>{{ $lesson->title }}</td>
                                <td id="inst_lesson_{{$lesson['id']}}"><?php if($topic->pivot['instructor_id'] != null)
                                {

                                    $inst = $lesson->get_instructor($topic->pivot['instructor_id']);
                                    echo $inst['title'];
                                }else{
                                    echo '-';
                                } ?></td>
                                    @if(count($event->type) > 0 && $isInclassCourse)
                                    <td></td><td></td><td></td><td></td><td></td><td></td>
                                    @endif
                                @can('manage-users', App\Model\User::class)
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                                            @can('update', $user)
                                                <a href="javascript:void(0)" id="open_modal" data-topic-id="topic_{{$topic->id}}"  data-lesson-id="lesson_{{ $lesson->id }}" class="dropdown-item open_modal">{{ __('Edit') }}</a>
                                            @endcan
                                            <a href="javascript:void(0)" id="remove_lesson" data-topic-id="topic_{{$topic->id}}"  data-lesson-id="lesson_{{ $lesson->id }}" class="dropdown-item">{{ __('Delete') }}</a>
                                            </div>
                                        </div>
                                    </td>
                                @endcan
                            </tr>

                                @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>


<!-- <div class="row align-items-center">
    <div class="col-8">
        <h3 class="mb-0">{{ __('Instructors') }}</h3>

    </div>
</div>
<div class="table-responsive py-4">
    <table class="table align-items-center table-flush"  id="datatable-basic">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{ __('Topic') }}</th>
                <th scope="col">{{ __('Lesson') }}</th>
                <th scope="col">{{ __('Instructor') }}</th>
                @if(count($event->type) > 0 && $isInclassCourse)
                    <th scope="col">{{ __('Date') }}</th>
                    <th scope="col">{{ __('Time starts') }}</th>
                    <th scope="col">{{ __('Time ends') }}</th>
                    <th scope="col">{{ __('Duration') }}</th>
                    <th scope="col">{{ __('Room') }}</th>
                    <th scope="col">{{ __('Priority') }}</th>
                @endif

                @can('manage-users', App\Model\User::class)
                    <th scope="col"></th>
                @endcan
            </tr>

        </thead>
        <tbody id="topic_lessons" data-event-id="{{$event['id']}}">


                    @foreach ($allTopicsByCategory1 as $topic1)
                    <?php $i=0; ?>
                    <?php //dd(count($topic1));?>
                    @foreach($topic1 as $topic)

                    <tr class="topic_{{$topic->id}}">


                            @if($i == 0)
                                <th rowspan="{{count($topic1)}}">{{ $topic->title }}</th>
                                <?php $i++;?>
                            @endif
                    @foreach($topic->lessons as $lesson)
                    @if($topic->getOriginal('pivot_lesson_id') == $lesson['id'])


                            <td>{{ $lesson->title }}</td>
                            <td id="inst_lesson_{{$lesson['id']}}"><?php if($topic->pivot['instructor_id'] != null)
                            {

                                $inst = $lesson->get_instructor($topic->pivot['instructor_id']);
                                echo $inst['title'];
                            }else{
                                echo '-';
                            } ?></td>
                            @if(count($event->type) > 0 && $isInclassCourse)
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @endif
                            @can('manage-users', App\Model\User::class)
                                <td class="text-right">
                                    @if (auth()->user()->can('update', $user) || auth()->user()->can('delete', $user))
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                                                    @can('update', $user)
                                                        <a href="javascript:void(0)" id="open_modal" data-topic-id="topic_{{$topic->id}}"  data-lesson-id="lesson_{{ $lesson->id }}" class="dropdown-item open_modal">{{ __('Edit') }}</a>
                                                    @endcan
                                                    <a href="javascript:void(0)" id="remove_lesson" data-topic-id="topic_{{$topic->id}}"  data-lesson-id="lesson_{{ $lesson->id }}" class="dropdown-item">{{ __('Delete') }}</a>
                                                    {{--@can('delete', $user)
                                                        <form action="{{ route('lesson.destroy_from_topic', [
                                                                        'topic_id' => $topic->id,
                                                                        'lesson_id'=> $lesson->id,
                                                                        'event_id'=> $event['id']
                                                                        ]
                                                                        ) }}"
                                                                        method="post">
                                                            @csrf

                                                        @method('post')
                                                            <button type="button" class="dropdown-item btn btn-primary btn-sm" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                                                                {{ __('Delete') }}
                                                            </button>
                                                        </form>
                                                    @endcan--}}

                                            </div>
                                        </div>
                                    @endif
                                </td>
                            @endcan
                        </tr>

            @endif
            @endforeach
            @endforeach
            @endforeach

        </tbody>
    </table>

    </div>
</div> -->

