@extends('layouts.app', [
    'title' => __('Exams Management'),
    'parentSection' => 'laravel',
    'elementName' => 'exams-management'
    ])
    @section('content')

    @component('layouts.headers.auth')
    @component('layouts.headers.breadcrumbs')
    @slot('title')
    {{ __('') }}
    @endslot
    <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">{{ __('Exams Management') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Add Exam') }}</li>
    @endcomponent
    @endcomponent
    <div class="container-fluid mt--6">
       <div class="nav-wrapper" style="margin-top: 65px;">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0 ml-1">{{ $exam->exam_name }}</h3>
                    </div>
                </div>
            </div>

          <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
             <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Settings</a>
             </li>
             @if($edit)
                <li class="nav-item">
                   <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#questions" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Content</a>
                </li>
                <li class="nav-item">
                   <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#results" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i> Results</a>
                </li>

                @if(count($liveResults)>0)
                   <li class="nav-item">
                      <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#live_result" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i> Live Result</a>
                   </li>
                @endif

             @endif
          </ul>
       </div>
       <div class="tab-content">
          <div class="tab-pane fade show active" id="settings" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
             <div class="row">
                <div class="col-xl-12 order-xl-1">
                   <div class="card-body">
                      @if($edit)
                      <form method="post" action="{{ route('exams.update',$exam->id) }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @method('put')
                      @else
                      <form method="post" action="{{ route('exams.store') }}" autocomplete="off"
                            enctype="multipart/form-data">

                      @endif
                      @csrf
                         <h6 class="heading-small text-muted mb-4">{{ __('Exam information') }}</h6>
                         <div class="pl-lg-4">
                            <div class ="row">
                               <div class="col-md-6">
                                  <div class="form-group{{ $errors->has('exam_name') ? ' has-danger' : '' }}">
                                     <label class="form-control-label" for="input-title">{{ __('Exam Name') }}</label>
                                     <input type="text" name="exam_name" id="input-title" class="form-control{{ $errors->has('exam_name') ? ' is-invalid' : '' }}" placeholder="{{ __('Exam Name') }}" value="{{ old('exam_name',$exam->exam_name) }}" required autofocus>
                                     @include('alerts.feedback', ['field' => 'title'])
                                  </div>
                                  <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                     <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="indicate_crt_incrt_answers" class="custom-control-input checkboxes" id="indicate_crt_incrt_answers" checked>
                                        <label class="custom-control-label" for="indicate_crt_incrt_answers">{{ __('Indicate correct or incorrect answers') }}</label>
                                     </div>
                                     @include('alerts.feedback', ['field' => 'indicate_crt_incrt_answers'])
                                  </div>
                                  <div class="form-group{{ $errors->has('random_questions') ? ' has-danger' : '' }}">
                                     <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="random_questions" class="custom-control-input checkboxes" id="random_questions" checked>
                                        <label class="custom-control-label" for="random_questions">{{ __('Randomize questions ') }}</label>
                                     </div>
                                     @include('alerts.feedback', ['field' => 'random_questions'])
                                  </div>
                                  <div class="form-group{{ $errors->has('duration') ? ' has-danger' : '' }}">
                                     <label class="form-control-label" for="input-duration">{{ __('Duration') }}</label>
                                     <input type="number" name="duration" id="input-duration" class="form-control{{ $errors->has('duration') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter exam duration') }}" value="{{ old('duration',$exam->duration) }}" required autofocus>
                                     @include('alerts.feedback', ['field' => 'duration'])
                                  </div>

                                  <div class="form-group">
                                      <label class="form-control-label" for="input-q_limit">{{ __('Date') }}</label>
                                      <div class='input-group date' id='datetimepicker1'>
                                          <input name="publish_time" type='text' class="form-control" value="{{ old('publish_time',date('d-m-Y H:i',strtotime($exam->publish_time))) }}"/>
                                          <span class="input-group-addon input-group-append">

                                              <button class="btn btn-outline-primary" type="button" id="button-addon2">  <span class="fa fa-calendar"></span></button>
                                          </span>
                                      </div>
                                  </div>
                                  <div class="form-group{{ $errors->has('examCheckbox') ? ' has-danger' : '' }}">
                                     <label class="form-control-label" for="input-examCheckbox">{{ __('Exam Password') }}</label>
                                     <input type="text" name="examCheckbox" id="input-examCheckbox" class="form-control{{ $errors->has('examCheckbox') ? ' is-invalid' : '' }}" placeholder="{{ __('Exam Password') }}" value="{{ old('examCheckbox',$exam->examCheckbox) }}" autofocus>
                                     @include('alerts.feedback', ['field' => 'examCheckbox'])
                                  </div>
                               </div>
                               <div class="col-md-6 plan">
                                  <div class="form-group{{ $errors->has('event_id') ? ' has-danger' : '' }}">
                                     <label class="form-control-label" for="input-event_id">{{ __('Event') }}</label>
                                     <select name="event_id" id="input-event_id" class="form-control" placeholder="{{ __('Event') }}">
                                        <option value="">-</option>
                                        @foreach ($events as $key => $event)
                                        <option value="{{ $key }}" @if(old('event_id',$event_id) == $key) selected @endif>{{ $event }}</option>
                                        @endforeach
                                     </select>
                                     @include('alerts.feedback', ['field' => 'event_id'])
                                  </div>
                                  <div class="form-group{{ $errors->has('display_crt_answers') ? ' has-danger' : '' }}">
                                     <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="display_crt_answers" class="custom-control-input checkboxes" id="display_crt_answers" checked>
                                        <label class="custom-control-label" for="display_crt_answers">{{ __('Display correct answer') }}</label>
                                     </div>
                                     @include('alerts.feedback', ['field' => 'display_crt_answers'])
                                  </div>
                                  <div class="form-group{{ $errors->has('random_answers') ? ' has-danger' : '' }}">
                                     <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="random_answers" class="custom-control-input checkboxes" id="random_answers" checked>
                                        <label class="custom-control-label" for="random_answers">{{ __('Randomize answers ') }}</label>
                                     </div>
                                     @include('alerts.feedback', ['field' => 'random_answers'])
                                  </div>
                                  <div class="form-group{{ $errors->has('examMethods') ? ' has-danger' : '' }}">
                                     <label class="form-control-label" for="input-examMethods">{{ __('Exam Methods') }}</label>
                                     <select name="examMethods" id="input-examMethods" class="form-control" placeholder="{{ __('Choose Method') }}">
                                        <option value="">Choose Method</option>
                                        <option @if(old("examMethods",$exam->examMethods) == "percentage" ) selected @endif value="percentage">Percentage</option>
                                        <option @if(old("examMethods",$exam->examMethods) == "point" ) selected @endif value="point">Point</option>
                                     </select>
                                     @include('alerts.feedback', ['field' => 'examMethods'])
                                  </div>
                                  <div class="form-group{{ $errors->has('q_limit') ? ' has-danger' : '' }}">
                                     <label class="form-control-label" for="input-q_limit">{{ __('Qualification Limit') }}</label>
                                     <input type="number" name="q_limit" id="input-q_limit" class="form-control{{ $errors->has('q_limit') ? ' is-invalid' : '' }}" placeholder="{{ __('Qualification Limit') }}" value="{{ old('q_limit',$exam->q_limit) }}" required autofocus>
                                     @include('alerts.feedback', ['field' => 'q_limit'])
                                  </div>
                                  <label class="form-control-label" for="status">{{ __('Status') }}</label>
                                  <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">

                                      <label class="custom-toggle">
                                          <input name="status" type="checkbox" @if($exam->status) checked @endif>
                                          <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                                      </label>
                                  </div>

                               </div>

                            </div>

                            <div class="form-group{{ $errors->has('intro_text') ? ' has-danger' : '' }}">
                               <label class="form-control-label" for="input-intro_text">{{ __('Exam Introduction Text') }}</label>
                               {{--<textarea name="intro_text" id="input-intro_text"  class="ckeditor form-control{{ $errors->has('intro_text') ? ' is-invalid' : '' }}" placeholder="{{ __('Replace this text as exam introduction text') }}"  required autofocus> {{old('intro_text',$exam->intro_text)}} </textarea>--}}

                                <!-- anto's editor -->
                                <input class="hidden" name="intro_text" value="{{ old('intro_text') }}"/>
                                <?php $data = isset($exam->intro_text) ? $exam->intro_text : ''; ?>
                                @include('event.editor.editor', ['keyinput' => "input-intro_text", 'data'=> "$data", 'inputname' => "'intro_text'" ])
                                <!-- anto's editor -->

                                @include('alerts.feedback', ['field' => 'intro_text'])
                            </div>
                            <div class="form-group{{ $errors->has('end_of_time_text') ? ' has-danger' : '' }}">
                               <label class="form-control-label" for="input-end_of_time_text">{{ __('Exam End of Time Text') }}</label>
                               {{--<textarea name="end_of_time_text" id=""  class="ckeditor form-control{{ $errors->has('end_of_time_text') ? ' is-invalid' : '' }}" placeholder="{{ __('Replace this text as exam end of time text') }}"  required autofocus> {{ old('end_of_time_text',$exam->end_of_time_text) }} </textarea>--}}

                               <!-- anto's editor -->
                                <input class="hidden" name="end_of_time_text" value="{{ old('end_of_time_text') }}"/>
                                <?php $data = isset($exam->end_of_time_text) ? $exam->end_of_time_text : ''; ?>
                                @include('event.editor.editor', ['keyinput' => "input-end_of_time_text", 'data'=> "$data", 'inputname' => "'end_of_time_text'" ])
                                <!-- anto's editor -->

                                @include('alerts.feedback', ['field' => 'end_of_time_text'])
                            </div>
                            <div class="form-group{{ $errors->has('success_text') ? ' has-danger' : '' }}">
                               <label class="form-control-label" for="input-success_text">{{ __('Exam Success Text') }}</label>
                               {{--<textarea name="success_text" id="input-success_text"  class="ckeditor form-control{{ $errors->has('success_text') ? ' is-invalid' : '' }}" placeholder="{{ __('Replace this text as exam success text') }}"  required autofocus>{{ old('success_text',$exam->success_text) }}</textarea>--}}

                                <!-- anto's editor -->
                                <input class="hidden" name="success_text" value="{{ old('success_text') }}"/>
                                <?php $data = isset($exam->success_text) ? $exam->success_text : ''; ?>
                                @include('event.editor.editor', ['keyinput' => "input-success_text", 'data'=> "$data", 'inputname' => "'success_text'" ])
                                <!-- anto's editor -->

                               @include('alerts.feedback', ['field' => 'success_text'])
                            </div>
                            <div class="form-group{{ $errors->has('failure_text') ? ' has-danger' : '' }}">
                               <label class="form-control-label" for="input-failure_text">{{ __('Exam Failure Text') }}</label>
                               {{--<textarea name="failure_text" id="input-failure_text"  class="ckeditor form-control{{ $errors->has('failure_text') ? ' is-invalid' : '' }}" placeholder="{{ __('Replace this text as exam success text') }}"  required autofocus>{{ old('failure_text',$exam->failure_text) }}</textarea>--}}

                                <!-- anto's editor -->
                                <input class="hidden" name="failure_text" value="{{ old('failure_text') }}"/>
                                <?php $data = isset($exam->failure_text) ? $exam->failure_text : ''; ?>
                                @include('event.editor.editor', ['keyinput' => "input-failure_text", 'data'=> "$data", 'inputname' => "'failure_text'" ])
                                <!-- anto's editor -->

                                @include('alerts.feedback', ['field' => 'failure_text'])
                            </div>
                            <div class="text-center">
                               <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                            </div>
                         </div>
                      </form>
                   </div>
                </div>
             </div>
          </div>
          @if($edit)
          <div class="tab-pane fade" id="questions" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
             <div class="accordion accord_topic" id="accordionExample">
                <div class="card">
                   <div class="card-header"  data-toggle="collapse" data-target="#questions-list" aria-expanded="false" >
                      <h5 class="mb-0">Questions List</h5>
                   </div>
                   <div id="questions-list" class="collapse" aria-labelledby="questions-list" data-parent="#accordionExample">
                      <div class="card-body">
                         <div class="table-responsive py-4">
                            <table class="table align-items-center table-flush"  id="datatable-basic6">
                               <thead class="thead-light">
                                  <tr>
                                     <th scope="col">{{ __('No') }}</th>
                                     <th scope="col">{{ __('Title') }}</th>
                                     <th scope="col">{{ __('Operations') }}</th>
                                  </tr>
                               </thead>

                               <tbody id="question-body" class="question-order">
                                  @foreach((array)json_decode($exam->questions,true) as $key => $question)
                                  <tr id="question-{{$key}}" data-id="{{$key}}" class="question-list">
                                     <td class="sortable-number"></td>
                                     <td>
                                     <span>{!! $question['question'] !!}</span>
                                     </td>
                                     <td class="text-right">
                                        <div class="dropdown">
                                           <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                           <i class="fas fa-ellipsis-v"></i>
                                           </a>
                                           <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                              <a class="dropdown-item question-item" data-toggle="modal" data-target="#editModal" data-id="{{$key}}" data-question="{{json_encode(json_decode($exam->questions,true)[$key])}}">{{ __('Edit') }}</a>
                                              <a class="dropdown-item delete-question" data-id="{{$key}}">{{ __('Delete') }}</a>
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
             </div>
             <div class="row">
                <div class="col-xl-12 order-xl-1">
                   <div class="card-body">
                      <h6 class="heading-small text-muted mb-4">{{ __('Add Exam Question') }}</h6>
                      <div class="pl-lg-4">
                         <div class="form-group">
                            <label class="form-control-label" for="question">{{ __('Exam Question') }}</label>
                            {{--<textarea name="question" id="question"  class="ckeditor form-control"  required autofocus>  </textarea>--}}
                            <!-- anto's editor -->
                                <input class="hidden" name="question" value="{{ old('question') }}"/>
                                <?php $data = old('question') ?>
                                @include('event.editor.editor', ['keyinput' => "question", 'data'=> "$data", 'inputname' => "'question'" ])
                            <!-- anto's editor -->
                            @include('alerts.feedback', ['field' => 'intro_text'])
                         </div>
                         <div class="row">
                            <div class="col-6">
                               <label class="form-control-label" for="question">{{ __('Question Type') }}</label>
                               <select id="question-types" class="form-control" name="question-type">
                                  <option value="true or false"> True or False </option>
                                  <option value="radio buttons"> Radio Buttons (one correct answer) </option>
                                  <option value="check boxes"> Check Boxes (several correct answers) </option>
                               </select>
                            </div>
                            <div class="col-6">
                            <label class="form-control-label" for="question">{{ __('Answer Credit') }}</label>
                               <div class="form-group">
                                  <div class="input-group">
                                     <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-default">{{ __('Answer Credit') }}</span>
                                     </div>
                                     <input id="answer-credit" type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                  </div>
                               </div>
                            </div>
                         </div>
                         <div class="form-group row answer-types">
                            <div class="custom-control custom-radio mb-3">
                               <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">
                               <label class="custom-control-label" for="customRadio1">Toggle this custom radio</label>
                            </div>
                            <div class="custom-control custom-radio">
                               <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input">
                               <label class="custom-control-label" for="customRadio2">Or toggle this other custom radio</label>
                            </div>
                         </div>
                      </div>
                   </div>
                </div>
             </div>
             <div class="text-center">
                <button type="button" class="btn btn-success mt-4 add-question">{{ __('Add Question') }}</button>
             </div>
          </div>
          <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="benefitModalLabel" aria-hidden="true">
             <div id="exam-edit-modal" class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                   <div class="modal-header">
                      <h5 class="modal-title" id="benefitModalLabel">Modal title</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                   </div>
                   <div class="modal-body">
                      <div class="card-body">
                         <h6 class="heading-small text-muted mb-4">{{ __('Edit Exam Question') }}</h6>
                         <div class="pl-lg-4">
                            <div class="form-group">
                               <label class="form-control-label" for="question">{{ __('Exam Question') }}</label>
                               {{--<textarea name="edit-question" id="edit-question"  class="ckeditor form-control"  required autofocus>  </textarea>--}}
                               <!-- anto's editor -->
                                <input class="hidden" name="edit-question" value="{{ old('edit-question') }}"/>
                                <?php $data = old('edit-question') ?>
                                @include('event.editor.editor', ['keyinput' => "edit-question", 'data'=> "$data", 'inputname' => "'edit-question'" ])
                                <!-- anto's editor -->
                               @include('alerts.feedback', ['field' => 'intro_text'])
                            </div>
                            <div class="row">
                               <div class="col-6">
                                  <label class="form-control-label" for="question">{{ __('Question Type') }}</label>
                                  <select id="edit-question-types" class="form-control" name="question-type">
                                     <option value="true or false"> True or False </option>
                                     <option value="radio buttons"> Radio Buttons (one correct answer) </option>
                                     <option value="check boxes"> Check Boxes (several correct answers) </option>
                                  </select>
                               </div>
                               <div class="col-6">
                                  <div class="form-group">
                                     <div class="input-group">
                                        <div class="input-group-prepend">
                                           <span class="input-group-text" id="inputGroup-sizing-default">{{ __('Answer Credit') }}</span>
                                        </div>
                                        <input id="answer-credit" type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                     </div>
                                  </div>
                               </div>
                            </div>
                            <div class="form-group row edit-answer-types">

                            </div>
                         </div>
                      </div>
                   </div>
                   <div class="modal-footer">
                      <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                      <button type="button" data-qu="0" id="update-question" class="btn btn-primary">Save changes</button>
                   </div>
                </div>
             </div>
          </div>

          <div class="tab-pane fade" id="results" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
             <div class="row">
             <div class="col-md-6 col-sm-6 col-xs-12">

                <div class="progress-wrapper">
                  <div class="progress-info">
                    <div class="progress-label">
                      <span>The average percentage of the exam</span>

                      <span id="avScore">{{$averageScore}}%</span>
                    </div>
                  </div>
                  <div class="progress">
                    <div id="avScoreBar" class="progress-bar bg-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {{$averageScore}}%;"></div>
                  </div>
                </div>

             </div>

             <div class="col-md-6 col-sm-6 col-xs-12">

                <div class="progress-wrapper">
                  <div class="progress-info">
                    <div class="progress-label">
                      <span>The average time of every participant</span>

                      <span id="avHour">{{$averageHour}}</span>
                    </div>
                  </div>
                  <div class="progress">
                    <div class="progress-bar bg-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                  </div>
                </div>

             </div>
             </div>
             <table class="table align-items-center table-flush"  id="results-table">
                <thead class="thead-light">
                   <tr>
                      <th scope="col">{{ __('SL NO.') }}</th>
                      <th scope="col">{{ __('Name') }}</th>
                      <th scope="col">{{ __('Score') }}</th>
                      <th scope="col">{{ __('Percentage') }}</th>
                      <th scope="col">{{ __('Start Time') }}</th>
                      <th scope="col">{{ __('End Time') }}</th>
                      <th scope="col">{{ __('Total Time') }}</th>
                      <th scope="col">{{ __('Action') }}</th>

                   </tr>
                </thead>
                <tbody id="resultsBody">
               @foreach($results as $key => $result)
                   <tr>
                      <td>
                         {{ $key + 1 }}
                      </td>
                      <td>
                         {{ $result['first_name'] }} {{ $result['last_name'] }}
                      </td>

                      <td>
                         {{ $result['score'] }}
                      </td>

                      <td>
                         {{ $result['scorePerc'] }}
                      </td>

                      <td>
                         {{ $result['start_time'] }}
                      </td>

                      <td>
                         {{ $result['end_time'] }}
                      </td>

                      <td>
                         {{ $result['total_time'] }}
                      </td>

                      <td class="text-right">
                         <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                               <a class="dropdown-item" href="/admin/student-summary/{{$result['exam_id']}}/{{$result['user_id']}}" target="_blank">{{ __('Show') }}</a>
                            </div>
                         </div>
                      </td>
                   </tr>
                  @endforeach
                </tbody>
             </table>
          </div>

          @if(count($liveResults)>0)
             <?php
                $duration = $exam->duration;
                $hours = intval($duration/60);
                $minutes = $duration%60;
                $seconds = 0;
             ?>
             <div class="tab-pane fade" id="live_result" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">

                <div class="row">

                      <div class="col-sm-12">
                         <div class="box">
                            <div class="box-header">
                               <h3 class="box-title">Live Results</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                               <table class="table align-items-center table-flush"  id="liveResultTable">

                                  <thead>
                                     <tr>
                                        <th>SL No.</th>
                                        <th>Name</th>
                                        <th>Answered</th>
                                        <th>Correct</th>
                                        <th>Total Questions</th>
                                        <th>Started At</th>
                                        {{--<th>Remaining Time</th>--}}
                                        <th>Finished At</th>

                                     </tr>
                                  </thead>
                                  <tbody>
                                     <?php $i=1; ?>
                                     @foreach($liveResults as $resultt)
                                     <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{$resultt['name']}}</td>
                                        <td id="{{$resultt['id']}}">{{ $resultt['answered'] }}</td>
                                        <td id="correct-{{$resultt['id']}}">{{ $resultt['correct'] }}</td>
                                        <td id="total-{{$resultt['id']}}">{{ $resultt['totalAnswers'] }}</td>
                                        <td>{{ $resultt['started_at'] }}</td>

                                        <!--<input type="hidden" name="time_spent" id="time_spent{{$resultt['id']}}" value="0">
                                        <td id="remainingTime{{$resultt['id']}}">
                                           <span id="hours{{$resultt['id']}}">{{$hours}}</span> :
                                           <span id="mins{{$resultt['id']}}"><?php //if($minutes<10){ echo '0'; }?>{{$minutes}}</span> :
                                           <span id="seconds{{$resultt['id']}}">00</span>
                                        </td>-->

                                        <td id="finish-at{{$resultt['id']}}">{{ $resultt['finish_at'] }}</td>

                                     </tr>
                                     <!--Result Delete Modal-->

                                     <!-- /.modal -->
                                     @endforeach
                                  </tbody>
                                  <tfoot>
                                     <tr>
                                        <th>SL No.</th>
                                        <th>Name</th>
                                        <th>Answered</th>
                                        <th>Correct</th>
                                        <th>Total Questions</th>
                                        <th>Started At</th>
                                        {{--<th>Remaining Time</th>--}}
                                        <th>Finished At</th>
                                     </tr>
                                  </tfoot>
                               </table>
                            </div>
                            <!-- /.box-body -->
                         </div>
                         <!-- /.box -->
                      </div>
                      <!-- /.col -->
                </div>
                <!-- /.row -->
             </div>
          @endif

          @endif
       </div>
    </div>
    <script src="{{asset('js/app.js')}}"></script>
    <script src="{{asset('admin_assets/js/vendor.min.js')}}"></script>
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
    <script src="{{ asset('assets/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap-datetimepicker.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $('#datetimepicker1').datetimepicker({
              icons: {
                time: "fa fa-clock",
                date: "fa fa-calendar-day",
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
              },
              format: 'DD-MM-YYYY HH:mm',
            });
        });
    </script>
    <script src="{{ asset('js/sortable/Sortable.js') }}"></script>
    <script>
       $(document).ready(function(){
           $(".checkboxes").each(function( ) {
               if($(this).is(':checked')){
                   $(this).val(1)
               }else{
                   $(this).val(0)
               }
           });
       })
       $(".checkboxes").click(function(){

           if($(this).is(':checked')){
               $(this).val(1)
           }else{
               $(this).val(0)
           }
       })
    </script>
    <script>
       $("#question-types").change(function(){

           if($(this).val() == 'true or false'){
               $('.answer-types').empty();
               $('.answer-types').append(`<div class="custom-control custom-radio mb-3">
                                         <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">
                                         <label class="custom-control-label" for="customRadio1">True</label>
                                       </div>
                                       <div class="custom-control custom-radio">
                                         <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input">
                                         <label class="custom-control-label" for="customRadio2">False</label>
                                       </div>`
               )
           }else if($(this).val() == 'radio buttons'){
               $('.answer-types').empty();
               $('.answer-types').append(
                   `<div class="col-6">
                       <div class="form-group">
                           <div class="input-group answers">
                             <div class="input-group-prepend">
                               <div class="input-group-text">
                                 <input data-id='1' class="answer-check" type="radio" aria-label="Checkbox for following text input">
                               </div>
                             </div>
                             <input type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                           </div>
                       </div>
                   </div>

                   <div class="col-6">
                       <div class="form-group">
                           <div class="input-group answers">
                             <div class="input-group-prepend">
                               <div class="input-group-text">
                                 <input data-id='2' class="answer-check" type="radio" aria-label="Checkbox for following text input">
                               </div>
                             </div>
                             <input type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                           </div>
                       </div>
                   </div>

                   <div class="col-6">
                       <div class="form-group">
                           <div class="input-group answers">
                             <div class="input-group-prepend">
                               <div class="input-group-text">
                                 <input data-id='3' class="answer-check" type="radio" aria-label="Checkbox for following text input">
                               </div>
                             </div>
                             <input type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                           </div>
                       </div>
                   </div>

                   <div class="col-6">
                       <div class="form-group">
                           <div class="input-group answers">
                             <div class="input-group-prepend">
                               <div class="input-group-text">
                                 <input data-id='4' class="answer-check" type="radio" aria-label="Checkbox for following text input">
                               </div>
                             </div>
                             <input type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                           </div>
                       </div>
                   </div>`)
           }else if($(this).val() == 'check boxes'){

               $('.answer-types').empty();
               $('.answer-types').append(
                   `<div class="col-6">
                       <div class="form-group">
                           <div class="input-group answers">
                             <div class="input-group-prepend">
                               <div class="input-group-text">
                                 <input class="answer-check" type="checkbox" aria-label="Checkbox for following text input">
                               </div>
                             </div>
                             <input type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                           </div>
                       </div>
                   </div>

                   <div class="col-6">
                       <div class="form-group">
                           <div class="input-group answers">
                             <div class="input-group-prepend">
                               <div class="input-group-text">
                                 <input class="answer-check" type="checkbox" aria-label="Checkbox for following text input">
                               </div>
                             </div>
                             <input type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                           </div>
                       </div>
                   </div>

                   <div class="col-6">
                       <div class="form-group">
                           <div class="input-group answers">
                             <div class="input-group-prepend">
                               <div class="input-group-text">
                                 <input class="answer-check" type="checkbox" aria-label="Checkbox for following text input">
                               </div>
                             </div>
                             <input  type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                           </div>
                       </div>
                   </div>

                   <div class="col-6">
                       <div class="form-group">
                           <div class="input-group answers">
                             <div class="input-group-prepend">
                               <div class="input-group-text">
                                 <input class="answer-check" type="checkbox" aria-label="Checkbox for following text input">
                               </div>
                             </div>
                             <input  type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                           </div>
                       </div>
                   </div>`)

           }

       })


        $("#edit-question-types").change(function(){

        if($(this).val() == 'true or false'){
             $('.edit-answer-types').empty();
             $('.edit-answer-types').append(`<div class="custom-control custom-radio mb-3">
                                                <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">
                                                <label class="custom-control-label" for="customRadio1">True</label>
                                             </div>
                                             <div class="custom-control custom-radio">
                                                <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input">
                                                <label class="custom-control-label" for="customRadio2">False</label>
                                             </div>`
             )
        }else if($(this).val() == 'radio buttons'){
             $('.edit-answer-types').empty();
             $('.edit-answer-types').append(
                  `<div class="col-12">
                        <div class="form-group">
                             <div class="input-group answers">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                     <input data-id='1' class="answer-check" type="radio" aria-label="Checkbox for following text input">
                                  </div>
                                </div>
                                <textarea type="text" class="form-control answer-input" aria-label="Text input with checkbox"></textarea>
                             </div>
                        </div>
                  </div>

                  <div class="col-12">
                        <div class="form-group">
                             <div class="input-group answers">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                     <input data-id='2' class="answer-check" type="radio" aria-label="Checkbox for following text input">
                                  </div>
                                </div>
                                <textarea type="text" class="form-control answer-input" aria-label="Text input with checkbox"></textarea>
                             </div>
                        </div>
                  </div>

                  <div class="col-12">
                        <div class="form-group">
                             <div class="input-group answers">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                     <input data-id='3' class="answer-check" type="radio" aria-label="Checkbox for following text input">
                                  </div>
                                </div>
                                <textarea type="text" class="form-control answer-input" aria-label="Text input with checkbox"></textarea>
                             </div>
                        </div>
                  </div>

                  <div class="col-12">
                        <div class="form-group">
                             <div class="input-group answers">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                     <input data-id='4' class="answer-check" type="radio" aria-label="Checkbox for following text input">
                                  </div>
                                </div>
                                <textarea type="text" class="form-control answer-input" aria-label="Text input with checkbox"></textarea>
                             </div>
                        </div>
                  </div>`)
        }else if($(this).val() == 'check boxes'){

             $('.edit-edit-answer-types').empty();
             $('.answer-types').append(
                  `<div class="col-6">
                        <div class="form-group">
                             <div class="input-group answers">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                     <input class="answer-check" type="checkbox" aria-label="Checkbox for following text input">
                                  </div>
                                </div>
                                <input type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                             </div>
                        </div>
                  </div>

                  <div class="col-6">
                        <div class="form-group">
                             <div class="input-group answers">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                     <input class="answer-check" type="checkbox" aria-label="Checkbox for following text input">
                                  </div>
                                </div>
                                <input type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                             </div>
                        </div>
                  </div>

                  <div class="col-6">
                        <div class="form-group">
                             <div class="input-group answers">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                     <input class="answer-check" type="checkbox" aria-label="Checkbox for following text input">
                                  </div>
                                </div>
                                <input  type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                             </div>
                        </div>
                  </div>

                  <div class="col-6">
                        <div class="form-group">
                             <div class="input-group answers">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">
                                     <input class="answer-check" type="checkbox" aria-label="Checkbox for following text input">
                                  </div>
                                </div>
                                <input  type="text" class="form-control answer-input" aria-label="Text input with checkbox">
                             </div>
                        </div>
                  </div>`)

        }

    })

       $(document).on('click',".answer-check",function(){
           if($(this).attr('type') == 'radio'){
               let self = $(this);
               $("input.answer-check").each(function( ) {
                   if(self.data('id') != $(this).data('id')){
                       $(this).prop('checked',false);
                   }
               });
           }
       })

       @if($edit)

          $(".add-question").click(function(){

           let question = {};


           question['question'] = tinymce.get("question").getContent();
           question['answer-credit'] = $("#answer-credit").val() ? $("#answer-credit").val() : 1;
           question['question-type'] = $("#question-types").val();

           var answer = [];
           var correctAnswers = [];
           let answerChecked = false;
           $(".answers").each(function(){

               if(!$(this).find('.answer-input').val()){
                   alert('πρεπει να βαλετε ολες τις ερωτησεις')

                   return;
               }


               answer.push($(this).find('.answer-input').val());
               if($(this).find('.answer-check').is(':checked')){
                   answerChecked = true;
                   correctAnswers.push($(this).find('.answer-input').val());
               }


           })
           if(!answerChecked){
               alert('πρεπει να επιλέξετε τη σωστη απάντηση')

               return;
           }

           question['answers'] = answer;
           question['correct_answer'] =  correctAnswers;


           $.ajax({
               type: 'POST',
               headers: {
                   'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },
               Accept: 'application/json',
               url: "{{ route ('exam.add_question',$exam->id) }}",
               data:{'question':question},
               success: function(data) {
                   initQuestionFields()
                   var  questionsList = '';
                   let questions = JSON.parse(data.questions);
                   $.each(questions,function(index, value){

                      questionsList += `<tr id="question-` + index + `" data-id="` + index + `" class="question-list">` +
                         `<td>` + value['question'] + `</td>` +
                         `<td class="text-right">
                            <div class="dropdown">
                               <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <i class="fas fa-ellipsis-v"></i>
                               </a>
                               <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                  <a class="dropdown-item question-item" data-toggle="modal" data-target="#editModal" data-id="`+ index +`" data-question='`+ JSON.stringify(value) +`'>{{ __('Edit') }}</a>
                               </div>
                            </div>
                         </td>
                      </tr>`


                   })

                   $("#question-body").empty();
                   $("#question-body").append(questionsList)
                   questionOrder()
               }
           });


          })

          $(".delete-question").click(function(){

             let question =$(this).data('id');
             $('#question-'+question).remove();

             $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                Accept: 'application/json',
                url: "{{ route ('exam.delete_question',$exam->id) }}",
                data:{'question':question},
                success: function(data) {

                }
             });


             })

          $(document).on('click','#update-question' ,function(){
           //$("edit-question").click(function(){

             let question = {};

           //question['question'] = (CKEDITOR.instances['edit-question'].getData()).replace(/[&#39;]+/g, '');
           //question['question'] =  question['question'].replace(/[quot]+/g, '');


           question['question'] = tinymce.get("edit-question").getContent();
           question['answer-credit'] = $(".modal #answer-credit").val() ? $(".modal #answer-credit").val() : 1;
           question['question-type'] = $("#edit-question-types").val();

           var answer = [];
           var correctAnswers = [];
           let answerChecked = false;
           $(".modal .answers").each(function(){

               if(!$(this).find('.answer-input').val()){
                   alert('πρεπει να βαλετε ολες τις ερωτησεις')

                   return;
               }


               answer.push($(this).find('.answer-input').val());
               if($(this).find('.answer-check').is(':checked')){
                   answerChecked = true;
                   correctAnswers.push($(this).find('.answer-input').val());
               }


           })
           if(!answerChecked){
               alert('πρεπει να επιλέξετε τη σωστη απάντηση')

               return;
           }

           question['answers'] = answer;
           question['correct_answer'] =  correctAnswers;

           $.ajax({
               type: 'POST',
               headers: {
                   'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },
               Accept: 'application/json',
               url: "{{ route ('exam.update-question',$exam->id) }}",
               data:{'question':question,'key':$("#update-question").attr('data-qu')},
               success: function(data) {
                   //initQuestionFields()
                   $(".close-modal").click();
               }
           });


          })

          function questionOrder(){

             var el = document.getElementById('question-body');
             new Sortable(el, {
                 group: "words",
                 handle: ".my-handle",
                 draggable: ".item",
                 ghostClass: "sortable-ghost",

             });

             new Sortable(el, {
                 // Element dragging ended
                 onEnd: function ( /**Event*/ evt) {
                     orderQuestions(evt)
                 },
             });



          }

          function orderQuestions(evt){


             let questions = {}
             $( ".question-list" ).each(function( index ) {

                 questions[index] = $(this).data('id')
                 $(this).attr('data-id',index)

                var newVal = $(this).index() + 1;
                $(this).children('.sortable-number').html(newVal);

             });

             $( ".dropdown-item.question-item" ).each(function( index ) {

               $(this).attr('data-id',index)

             });

             $.ajax({
                 type: 'POST',
                 headers: {
                     'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                 },
                 Accept: 'application/json',
                 url: "{{route('exam.order-questions',$exam->id)}}",
                 data:{'questions':questions},
                 success: function(data) {


                 }
             });
          }

       @endif

       function initQuestionFields(){
           $("input.answer-check").each(function( ) {

               $(this).prop('checked',false);

           });

           $(".answer-input").each(function( ) {

               $(this).val('');

           });


           tinymce.get("question").setContent("");
           $("#answer-credit").val(1);

       }





       $(document).ready( function () {
        $('#results-table').DataTable( {
          destroy: true,
            language: {
                paginate: {
                    next: '&#187;', // or '→'
                    previous: '&#171;' // or '←'
                }
            }
           });

           $('#liveResultTable').DataTable( {
             "lengthMenu": [[50, 10, 25,  -1], [50, 10, 25,  "All"]],
             language: {
                paginate: {
                    next: '&#187;', // or '→'
                    previous: '&#171;' // or '←'
                }
            }
           });

           questionOrder();
           var t = $('#datatable-basic6').DataTable( {
               "ordering": false,
               "paging": false
           });

           t.on('order.dt search.dt', function () {
            let i = 1;

            t.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
                this.data(i++);
            });
        }).draw();


       });



    </script>

    <script>
       $(document).on('shown.bs.modal', '#editModal',function(e) {
           //e.preventDefault()
           let elem = document.getElementsByClassName('tox-editor-header');
           elem.forEach(function(element, index){
                elem[index].style.removeProperty('position')
                elem[index].style.removeProperty('left')
                elem[index].style.removeProperty('top')
                elem[index].style.removeProperty('width')
            })


           var link  = e.relatedTarget,
                modal    = $(this),
             question = JSON.parse(e.relatedTarget.dataset.question);

                modal.find("#edit-question-types").val(question['question-type'])
                modal.find("#edit-question-types").change();

                modal.find("#answer-credit").val(question['answer-credit']);

                modal.find(".answer-input").each(function(index){
                    $(this).val(question['answers'][index])

                    if(jQuery.inArray(question['answers'][index], question['correct_answer']) !== -1){
                        $(this).parent().find('.answer-check').prop('checked',true);
                    }

                })

                modal.find("#update-question").attr('data-qu',e.relatedTarget.dataset.id)


                tinymce.get("edit-question").setContent(question['question']);

              //CKEDITOR.instances['edit-question'].setData(question['question'])
           //    modal.find("#benefit-id").val(id)

       });


    </script>



    @if(count($liveResults) > 0)
    <script>
       var HOURS = [];
       var MINUTES = [];
       var SECONDS = [];

       setInterval(function(){

        $.get({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                type:'get',
                datatType : 'json',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                url:'/admin/live-results/{{$exam->id}}',
                success:function(data){

                   let resultsHtmll = '';
                   var tl = $('#liveResultTable').DataTable();
                   tl.clear();

                   if(data['liveResults'].length > 0){
                     $.each(data['liveResults'], function(key1, value1) {


                        tl.row.add([
                           key1 + 1,
                           value1['name'],
                           value1['answered'],
                           value1['correct'],
                           value1['totalAnswers'],
                           value1['started_at'],
                           value1['finish_at']
                        
                        ]).draw();
                        
                     })

                   }else{
                     tl.row.add([
                        
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        ''
                        
                     ]).draw();
                        
                   }

                   
                  //$('avScoreBar')
                   $('#avScore').html(data['averageScore'])
                   $('#avHour').html(data['averageHour'])

                   let resultsHtml = '';
                   var t = $('#results-table').DataTable();
                   t.clear();
                   $.each(data['results'], function(key1, value1) {


                      /*resultsHtml += `<tr><td>${key1+1}</td>
                         <td>
                            ${value1['first_name'] } ${ value1['last_name'] }
                         </td>

                         <td>
                            ${ value1['score'] }
                         </td>

                         <td>
                            ${ value1['scorePerc'] }
                         </td>

                         <td>
                            ${ value1['start_time'] }
                         </td>

                         <td>
                            ${ value1['end_time'] }
                         </td>

                         <td>
                            ${ value1['total_time'] }
                         </td>

                         <td class="text-right">
                            <div class="dropdown">
                               <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <i class="fas fa-ellipsis-v"></i>
                               </a>
                               <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                  <a class="dropdown-item" href="/admin/student-summary/${value1['exam_id']}/${value1['user_id']}" target="_blank">{{ __('Show') }}</a>
                               </div>
                            </div>
                         </td></tr>`*/



                      t.row.add([
                         key1+1,
                         value1['first_name'] + ' ' + value1['last_name'],
                         value1['score'],
                         value1['scorePerc'],
                         value1['start_time'],
                         value1['end_time'],
                         value1['total_time'],
                         `  <div class="dropdown">
                                  <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <i class="fas fa-ellipsis-v"></i>
                                  </a>
                                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                     <a class="dropdown-item" href="/admin/student-summary/${value1['exam_id']}/${value1['user_id']}" target="_blank">{{ __('Show') }}</a>
                                  </div>
                               </div>`
                      ]).draw()
                   })



                   /*$('#results-table').dataTable().fnDestroy();
                   table = $('#results-table').DataTable({
                       destroy: true,

                       language: {
                         paginate: {
                             next: '&#187;', // or '→'
                             previous: '&#171;' // or '←'
                         }
                      }
                   });




                   $("#resultsBody").empty();
                   $("#resultsBody").append(resultsHtml)*/

                }
        });
       },1000 * 60)


       setTimeout('start()', 1000)

    function start(){

       @foreach($liveResults as $resultt)

          var startTime = '<?php echo date( "Y-m-d")."T".$resultt['started_at'] ?>'
          var currentTime = '<?php echo date( "Y-m-d")."T".date( "H:i:s") ?>';
          var sTime = new Date(startTime);
          var cTime = new Date(currentTime);
          var cSeconds = (cTime.getTime() - sTime.getTime()) / 1000;
          var sSeconds = "{{$exam->duration}}"*60;
          var tSeconds = sSeconds - cSeconds;


          minutes = parseInt(tSeconds/60);
          seconds = tSeconds- minutes*60;
          hours = parseInt(minutes/60);
          minutes = minutes%60;

          intilizetimer(hours, minutes, seconds,"{{$resultt['id']}}");

       @endforeach

    }
    function intilizetimer(hrs, mins, sec,idd) {


       if(idd in  HOURS == false){
          HOURS[idd] = 0;
          MINUTES[idd] = 0;
          SECONDS[idd] = 0;
       }

       HOURS[idd]      = hrs;
       MINUTES[idd]     = mins;
       SECONDS[idd]     = sec;

       $("#hours"+idd).text(HOURS[idd]);
       if(MINUTES[idd]<10)
        $("#mins"+idd).text("0"+MINUTES[idd]);
       else
       $("#mins").text(MINUTES[idd]);
       if(SECONDS[idd]<10)
        $("#seconds"+idd).text("0"+SECONDS[idd]);
       else
        $("#seconds"+idd).text(SECONDS[idd]);

    }



    setInterval("tictac()", 1000);
    function tictac(){

       @foreach($liveResults as $resultt)
       if('{{$resultt["finish_at"]}}' == '00:00:00'){
          SECONDS['{{$resultt["id"]}}']--;
          SPENT_TIME = jQuery('#time_spent{{$resultt["id"]}}').val();
          if(isNaN(SPENT_TIME))
             SPENT_TIME = 0;
          else
             SPENT_TIME = parseInt(SPENT_TIME)+1;
          jQuery('#time_spent{{$resultt["id"]}}').val(SPENT_TIME);
          if(SECONDS['{{$resultt["id"]}}']<=0)
          {
             MINUTES['{{$resultt["id"]}}'] = MINUTES['{{$resultt["id"]}}'] - 1;

             if(MINUTES['{{$resultt["id"]}}']<1)
             {

             }
             if(MINUTES['{{$resultt["id"]}}']<0)
             {
                   if(HOURS['{{$resultt["id"]}}']!=0) {
                      MINUTES['{{$resultt["id"]}}'] = 59;
                      HOURS['{{$resultt["id"]}}'] =  HOURS['{{$resultt["id"]}}']-1;
                      SECONDS['{{$resultt["id"]}}'] = 59;
                      $("#mins{{$resultt['id']}}").text(MINUTES['{{$resultt["id"]}}']);
                      $("#hours{{$resultt['id']}}").text(HOURS['{{$resultt["id"]}}']);
                      return;
                   }
                   //stopInterval();
                  // forceFinish();
                   return;
             }
             if(MINUTES['{{$resultt["id"]}}']<10)
                   $("#mins{{$resultt['id']}}").text("0"+MINUTES['{{$resultt["id"]}}']);
             else
                   $("#mins{{$resultt['id']}}").text(MINUTES['{{$resultt["id"]}}']);
          SECONDS['{{$resultt["id"]}}']=60;
          }
          if(MINUTES['{{$resultt["id"]}}']>=0) {
             if(SECONDS['{{$resultt["id"]}}']<10)
                $("#seconds{{$resultt['id']}}").text("0"+SECONDS['{{$resultt["id"]}}']);
             else
                $("#seconds{{$resultt['id']}}").text(SECONDS['{{$resultt["id"]}}']);
          }
          else
          $("#seconds{{$resultt['id']}}").text('00');
       }
       @endforeach
    }
    </script>
    @endif



    @endpush
