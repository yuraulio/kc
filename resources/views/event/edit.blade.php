@extends('layouts.app', [
    'title' => __('Role Management'),
    'parentSection' => 'laravel',
    'elementName' => 'role-management'
])

@section('content')
    @component('layouts.headers.auth')
    @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Events') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('events.index') }}">{{ __('Events Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Event') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="nav-wrapper">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Settings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Students</a>
                        </li>
                    </ul>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                                <p class="description">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth.</p>
                                <p class="description">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse.</p>
                            </div>
                            <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                <div class="nav-wrapper">
                                    <ul id="tab_inside_tab" class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab_inside" data-toggle="tab" href="#tabs-icons-text-1_inside" role="tab" aria-controls="tabs-icons-text-1_inside" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Overview</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab_inside" data-toggle="tab" href="#tabs-icons-text-2_inside" role="tab" aria-controls="tabs-icons-text-2_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Summary </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab_inside" data-toggle="tab" href="#tabs-icons-text-3_inside" role="tab" aria-controls="tabs-icons-text-3_inside" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Benefit</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-4-tab_inside" data-toggle="tab" href="#tabs-icons-text-4_inside" role="tab" aria-controls="tabs-icons-text-4_inside" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Topics</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-5-tab_inside" data-toggle="tab" href="#tabs-icons-text-5_inside" role="tab" aria-controls="tabs-icons-text-5_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Tickets</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-6-tab_inside" data-toggle="tab" href="#tabs-icons-text-6_inside" role="tab" aria-controls="tabs-icons-text-6_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>City</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-7-tab_inside" data-toggle="tab" href="#tabs-icons-text-7_inside" role="tab" aria-controls="tabs-icons-text-7_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Venue</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-8-tab_inside" data-toggle="tab" href="#tabs-icons-text-8_inside" role="tab" aria-controls="tabs-icons-text-8_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Partners</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-9-tab_inside" data-toggle="tab" href="#tabs-icons-text-9_inside" role="tab" aria-controls="tabs-icons-text-9_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Sections</a>
                                        </li>

                                    </ul>
                                </div>
                                <div class="card shadow">
                                    <div class="card-body">
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="tabs-icons-text-1_inside" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab_inside">

                                                <form method="post" action="{{ route('events.update', $event) }}" autocomplete="off"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('put')

                                                    <h6 class="heading-small text-muted mb-4">{{ __('Event information') }}</h6>
                                                    <div class="pl-lg-4">
                                                        <div class="form-group{{ $errors->has('priority') ? ' has-danger' : '' }}">
                                                            <label class="form-control-label" for="input-priority">{{ __('Priority') }}</label>
                                                            <input type="number" name="priority" id="input-priority" class="form-control{{ $errors->has('priority') ? ' is-invalid' : '' }}" placeholder="{{ __('Priority') }}" value="{{ old('priority', $event->priority) }}" autofocus>

                                                            @include('alerts.feedback', ['field' => 'priority'])
                                                        </div>



                                                        <div class="form-group{{ $errors->has('category_id') ? ' has-danger' : '' }}">
                                                            <label class="form-control-label" for="input-category_id">{{ __('Category') }}</label>
                                                            <select name="category_id" id="input-category_id" class="form-control" placeholder="{{ __('Category') }}" required>
                                                                <option value="">-</option>
                                                                @foreach ($categories as $category)
                                                                    <option <?php if(count($event->category) != 0){
                                                                        if($event->category[0]->id == $category->id){
                                                                            echo 'selected';
                                                                        }else{
                                                                            echo '';
                                                                        }
                                                                    }
                                                                    ?> value="{{ $category->id }}" >{{ $category->name }}</option>
                                                                @endforeach
                                                            </select>

                                                            @include('alerts.feedback', ['field' => 'category_id'])
                                                        </div>

                                                        <div class="form-group{{ $errors->has('type_id') ? ' has-danger' : '' }}">
                                                            <label class="form-control-label" for="input-type_id">{{ __('Type') }}</label>
                                                            <select name="type_id" id="input-type_id" class="form-control" placeholder="{{ __('Type') }}" required>
                                                                <option value="">-</option>
                                                                @foreach ($types as $type)
                                                                    <option <?php if(count($event->type) != 0){
                                                                        if($event->type[0]->id == $type->id){
                                                                            echo 'selected';
                                                                        }else{
                                                                            echo '';
                                                                        }
                                                                    }
                                                                    ?> value="{{ $type->id }}" >{{ $type->name }}</option>
                                                                @endforeach
                                                            </select>

                                                            @include('alerts.feedback', ['field' => 'type_id'])
                                                        </div>

                                                        <div class="form-group{{ $errors->has('published') ? ' has-danger' : '' }}">
                                                            <label class="form-control-label" for="input-published">{{ __('Published') }}</label>
                                                            <select name="published" id="input-published" class="form-control" placeholder="{{ __('Published') }}" >
                                                                <option value="">-</option>
                                                                    <option <?= ($event['published'] == 1) ? 'selected="selected"' : ''; ?> value="1">{{ __('Published') }}</option>
                                                                    <option <?= ($event['published'] == 0) ? 'selected="selected"' : ''; ?> value="0">{{ __('Unpublished') }}</option>
                                                            </select>

                                                            @include('alerts.feedback', ['field' => 'published'])
                                                        </div>

                                                        <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                                            <label class="form-control-label" for="input-status">{{ __('Status') }}</label>
                                                            <select name="status" id="input-status" class="form-control" placeholder="{{ __('Status') }}" >
                                                                <option value="">-</option>
                                                                    <option <?= ($event['status'] == 3) ? 'selected="selected"' : ''; ?> value="3">{{ __('Soldout') }}</option>
                                                                    <option <?= ($event['status'] == 2) ? 'selected="selected"' : ''; ?> value="2">{{ __('Completed') }}</option>
                                                                    <option <?= ($event['status'] == 1) ? 'selected="selected"' : ''; ?> value="1">{{ __('Open') }}</option>
                                                                    <option <?= ($event['status'] == 0) ? 'selected="selected"' : ''; ?> value="0">{{ __('Close') }}</option>
                                                            </select>

                                                            @include('alerts.feedback', ['field' => 'status'])
                                                        </div>

                                                        <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                                            <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                                            <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title', $event->title) }}" required autofocus>

                                                            @include('alerts.feedback', ['field' => 'title'])
                                                        </div>

                                                        <div class="form-group{{ $errors->has('htmlTitle') ? ' has-danger' : '' }}">
                                                            <label class="form-control-label" for="input-htmlTitle">{{ __('HTML Title') }}</label>
                                                            <input type="text" name="htmlTitle" id="input-htmlTitle" class="form-control{{ $errors->has('htmlTitle') ? ' is-invalid' : '' }}" placeholder="{{ __('HTML Title') }}" value="{{ old('Short title', $event->htmlTitle) }}" autofocus>

                                                            @include('alerts.feedback', ['field' => 'htmlTitle'])
                                                        </div>

                                                        <div class="form-group{{ $errors->has('subtitle') ? ' has-danger' : '' }}">
                                                            <label class="form-control-label" for="input-subtitle">{{ __('Subtitle') }}</label>
                                                            <input type="text" name="subtitle" id="input-subtitle" class="form-control{{ $errors->has('subtitle') ? ' is-invalid' : '' }}" placeholder="{{ __('subtitle') }}" value="{{ old('Subtitle', $event->subtitle) }}" autofocus>

                                                            @include('alerts.feedback', ['field' => 'subtitle'])
                                                        </div>

                                                        <div class="form-group{{ $errors->has('header') ? ' has-danger' : '' }}">
                                                            <label class="form-control-label" for="input-header">{{ __('Header') }}</label>
                                                            <input type="text" name="header" id="input-header" class="form-control{{ $errors->has('header') ? ' is-invalid' : '' }}" placeholder="{{ __('Header') }}" value="{{ old('header', $event->header) }}" autofocus>

                                                            @include('alerts.feedback', ['field' => 'header'])
                                                        </div>

                                                        <div class="form-group{{ $errors->has('summary') ? ' has-danger' : '' }}">
                                                            <label class="form-control-label" for="input-summary">{{ __('Summary') }}</label>
                                                            <input type="text" name="summary" id="input-summary" class="form-control{{ $errors->has('summary') ? ' is-invalid' : '' }}" placeholder="{{ __('Summary') }}" value="{{ old('summary', $event->summary) }}" autofocus>

                                                            @include('alerts.feedback', ['field' => 'summary'])
                                                        </div>

                                                        <div class="form-group{{ $errors->has('body') ? ' has-danger' : '' }}">
                                                            <label class="form-control-label" for="input-body">{{ __('Body') }}</label>
                                                            <input type="text" name="body" id="input-body" class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" placeholder="{{ __('Body') }}" value="{{ old('body', $event->body) }}" autofocus>

                                                            @include('alerts.feedback', ['field' => 'body'])
                                                        </div>

                                                        <div class="form-group{{ $errors->has('hours') ? ' has-danger' : '' }}">
                                                            <label class="form-control-label" for="input-hours">{{ __('Hours') }}</label>
                                                            <input type="number" name="hours" id="input-hours" class="form-control{{ $errors->has('hours') ? ' is-invalid' : '' }}" placeholder="{{ __('Hours') }}" value="{{ old('hours', $event->hours) }}"autofocus>

                                                            @include('alerts.feedback', ['field' => 'hours'])
                                                        </div>

                                                        <div class="form-group{{ $errors->has('view_tpl') ? ' has-danger' : '' }}">
                                                            <label class="form-control-label" for="input-view_tpl">{{ __('View tpl') }}</label>
                                                            <input type="text" name="view_tpl" id="input-view_tpl" class="form-control{{ $errors->has('view_tpl') ? ' is-invalid' : '' }}" placeholder="{{ __('View tpl') }}" value="{{ old('view_tpl', $event->view_tpl) }}"autofocus>

                                                            @include('alerts.feedback', ['field' => 'view_tpl'])
                                                        </div>
                                                            <input type="hidden" name="creator_id" id="input-creator_id" class="form-control" value="{{$event->creator_id}}">
                                                            <input type="hidden" name="author_id" id="input-author_id" class="form-control" value="{{$event->author_id}}">

                                                            @include('alerts.feedback', ['field' => 'ext_url'])


                                                        <div class="text-center">
                                                            <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-2_inside" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab_inside">
                                                <div class="col-12 mt-2">
                                                    @include('alerts.success')
                                                    @include('alerts.errors')
                                                </div>
                                                @include('summary.index')
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-3_inside" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab_inside">

                                                @include('benefit.index')
                                            </div>

                                            <div class="tab-pane fade show" id="tabs-icons-text-4_inside" role="tabpanel" aria-labelledby="tabs-icons-text-4-tab_inside">
                                                @include('topics.event.index')


                                                <div class="row align-items-center">
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
                                                                @if(count($event->type) > 0 && $event->type[0]['name'] == 'In-Class')
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
                                                                            @if(count($event->type) > 0 && $event->type[0]['name'] == 'In-Class')
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
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-5_inside" role="tabpanel" aria-labelledby="tabs-icons-text-5-tab_inside">
                                                @include('ticket.index')
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-6_inside" role="tabpanel" aria-labelledby="tabs-icons-text-6-tab_inside">
                                                @include('city.index')
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-7_inside" role="tabpanel" aria-labelledby="tabs-icons-text-7-tab_inside">
                                                @include('venue.index')
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-8_inside" role="tabpanel" aria-labelledby="tabs-icons-text-8-tab_inside">
                                                @include('partner.event.index')
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-9_inside" role="tabpanel" aria-labelledby="tabs-icons-text-9-tab_inside">
                                                @include('section.index')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                                @include('event.students')
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>



    <div class="row">
        <div class="col-md-4">
            <div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h6 class="modal-title" id="modal-title-default"></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Select instructor</label>
                                <select class="form-control" id="instFormControlSelect">
                                <option>-</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="lesson_update_btn" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
            </div>
        </div>
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

        $(document).on('click','#lesson_update_btn',function(){
            let start = $('#time_starts').val()
            let date = $('#date').val()
            let end =  $('#time_ends').val()
            let duration = $('#duration').val()
            let room = $('#room').val()
            let priority = $('#priority').val()
            let topic_id = $('#topic_id').val()
            let event_id = $('#event_id').val()
            let lesson_id = $('#lesson_id').val()
            let instructor_id = $('#instFormControlSelect').val()




            data = {date:date, start:start, event_id:event_id, end:end, duration:duration, room:room, priority:priority, instructor_id:instructor_id, topic_id:topic_id, lesson_id:lesson_id}

            $.ajax({
                type: 'POST',
                headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },
               Accept: 'application/json',
                url: "/lesson/save_instructor",
                data:data,
                success: function(data) {
                    data = JSON.parse(data)
                    console.log(data)
                    $('#inst_lesson_'+data.lesson_id).text(data.instructor.title)
                    $('#modal-default').modal()

                }
            });

        });




    </script>

    <script>

        $( "#instFormControlSelect" ).change(function() {
            if($('#instFormControlSelect').val() == '-'){
                $('#lesson_update_btn').prop('disabled', true);
                $('#lesson_update_btn').css('opacity', '0.4')
            }else{
                $('#lesson_update_btn').prop('disabled', false);
                $('#lesson_update_btn').css('opacity', '1')
            }
        //alert( "Handler for .change() called." );
        });

    </script>

    <script>
            function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [month, day,year].join('/');
        }

        function convertTimestamptoTime(timestamp) {
            unixTimestamp = timestamp;


            // convert to milliseconds and
            // then create a new Date object
            dateObj = new Date(unixTimestamp * 1000);
            utcString = dateObj.toUTCString();

            time = utcString.slice(-12, -4);

            return time;
        }

        $(document).on('click', '#remove_lesson', function() {
            var confirmation = confirm("are you sure you want to remove the item?");
            let elem = $(this).data('lesson-id');
            elem = elem.split("_")
            let topic_id = $(this).data('topic-id')
            topic_id = topic_id.split("_")
            const event_id = $('#topic_lessons').data('event-id')

            console.log(elem[1])
            console.log(topic_id[1])
            console.log(event_id)

            data = {lesson_id:elem[1], topic_id:topic_id[1], event_id:event_id}
            $.ajax({
                type : 'POST',
                headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },
               Accept: 'application/json',
                url: "/lesson/remove_lesson",
                data:data,
                success: function(data){
                    data = JSON.parse(data)
                    $('#inst_lesson_'+data.lesson_id).parent().remove()
                }
            });
        });

        $(document).on('click','#open_modal',function(){
            let id = 0
            let elem = $(this).data('lesson-id');
            elem = elem.split("_")
            let topic_id = $(this).data('topic-id')
            topic_id = topic_id.split("_")
            const event_id = $('#topic_lessons').data('event-id')
            let instructor_id = $('#instFormControlSelect').val()


            data = {lesson_id:elem[1], topic_id:topic_id[1], event_id:event_id}
            $.ajax({
                type: 'POST',
                headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },
               Accept: 'application/json',
                url: "/lesson/edit_instructor",
                data:data,
                success: function(data) {
                    date = ''

                    data = JSON.parse(data)

                    let event_type = data.event.type[0].name
                    let event_id = data.event

                    lesson = data.lesson[0].pivot

                    if(lesson.instructor_id == null){

                        $('#lesson_update_btn').prop('disabled', true);
                        $('#lesson_update_btn').css('opacity', '0.4')
                    }else{
                        $('#lesson_update_btn').prop('disabled', false);
                        $('#lesson_update_btn').css('opacity', '1')
                    }
                    $('#modal-title-default').text(data.lesson[0].title)

                    $.each( data.instructors, function( key, value ) {
                        $('#instFormControlSelect').append(`<option ${lesson.instructor_id == value.id ? 'selected' : ''} value="${value.id}">${value.title}</option>`)
                    });

                    if(lesson.date != null){
                        date =  new Date(lesson.date).toDateString()
                        date = formatDate(date)
                    }

                    if(lesson.time_starts != null)
                    {
                        d = new Date(lesson.time_starts)

                        time_starts = convertTimestamptoTime(d.getTime())
                    }

                    if(lesson.time_ends != null)
                    {
                        d = new Date(lesson.time_ends)
                        time_ends = convertTimestamptoTime(d.getTime())

                    }


                    if(event_type == 'In-Class'){
                        let row = `
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                    </div>
                                    <input class="form-control datepicker" name="date" id="date" placeholder="Select date" type="text" value="${date}" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="date">Time starts</label>
                                <input type="time" name="time_starts" class="form-control" id="time_starts" value="${lesson.time_starts != null ? time_starts : ''}" placeholder="Start">
                            </div>
                            <div class="form-group">
                                <label for="date">Time ends</label>
                                <input type="time" name="time_ends" class="form-control" id="time_ends" value="${lesson.time_ends != null ? time_ends : ''}" placeholder="End">
                            </div>
                            <div class="form-group">
                                <label for="date">Duration</label>
                                <input type="text" name="duration" class="form-control" id="duration" value="${lesson.duration != null ? lesson.duration : ''}" placeholder="Duration">
                            </div>
                            <div class="form-group">
                                <label for="date">Room</label>
                                <input type="text" name="room" class="form-control" id="room" value="${lesson.room != null ? lesson.room : ''}" placeholder="Room">
                            </div>
                            <div class="form-group">
                                <label for="date">Priority</label>
                                <input type="number" name="priority" class="form-control" id="priority" value="${lesson.priority != null ? lesson.priority : ''}" placeholder="Priority">
                            </div>
                        `

                        $('.modal-body form').append(row)


                    }

                    let input_hidden = `
                            <input type="hidden" name="topic_id" id="topic_id" value="${data.topic_id}">
                            <input type="hidden" name="event_id" id="event_id" value="${data.event.id}">
                            <input type="hidden" name="lesson_id" id="lesson_id" value="${data.lesson_id}">
                        `

                    $('.modal-body form').append(input_hidden)



                    $('#modal-default').modal('show');
                }
            });

        });
    </script>
@endpush
