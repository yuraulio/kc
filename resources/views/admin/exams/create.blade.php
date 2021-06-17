@extends('layouts.app', [
    'title' => __('Exams Management'),
    'parentSection' => 'laravel',
    'elementName' => 'exams-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Examples') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">{{ __('Exams Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Add Exam') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Exams Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('exams.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('exams.store') }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">{{ __('Exam information') }}</h6>
                            <div class="pl-lg-4">

                            <div class ="row">
                                <div class="col-md-6">

                                    <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                        <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title') }}" required autofocus>

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
                                        <input type="number" name="duration" id="input-duration" class="form-control{{ $errors->has('duration') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter exam duration') }}" value="{{ old('duration') }}" required autofocus>

                                        @include('alerts.feedback', ['field' => 'duration'])
                                    </div>



                                    <div class="form-group{{ $errors->has('publish_time') ? ' has-danger' : '' }}">
                                        <label for="example-date-input" class="form-control-label">{{ __('Publish time') }}</label>
                                        <input class="form-control{{ $errors->has('publish_time') ? ' is-invalid' : '' }}" placeholder="{{ __('DD/MM/YYYY') }}" value="{{ old('publish_time') }}" name="publish_time" type="date" id="publish_time">

                                        @include('alerts.feedback', ['field' => 'publish_time'])
                                    </div>

                                </div>
                                <div class="col-md-6">

                                    <div class="form-group{{ $errors->has('event_id') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-event_id">{{ __('Event') }}</label>
                                        <select name="event_id" id="input-event_id" class="form-control" placeholder="{{ __('Event') }}">
                                            <option value="">-</option>
                                            @foreach ($events as $event)
                                                <option value="{{ $event->id }}" @if(old('event_id') == "{{$event->id}}") checked @endif>{{ $event->title }}</option>
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
                                                <option value="Percentage">Percentage</option>
                                                <option value="Point">Point</option>

                                        </select>

                                        @include('alerts.feedback', ['field' => 'examMethods'])
                                    </div>

                                    <div class="form-group{{ $errors->has('q_limit') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-q_limit">{{ __('Qualification Limit') }}</label>
                                        <input type="number" name="q_limit" id="input-q_limit" class="form-control{{ $errors->has('q_limit') ? ' is-invalid' : '' }}" placeholder="{{ __('Qualification Limit') }}" value="{{ old('q_limit') }}" required autofocus>

                                        @include('alerts.feedback', ['field' => 'q_limit'])
                                    </div>

                                </div>
                            </div>



                                <div class="form-group{{ $errors->has('examCheckbox') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-examCheckbox">{{ __('Exam Checkbox Text') }}</label>
                                    <input type="text" name="examCheckbox" id="input-examCheckbox" class="form-control{{ $errors->has('examCheckbox') ? ' is-invalid' : '' }}" placeholder="{{ __('Exam Checkbox Text') }}" value="{{ old('examCheckbox') }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'examCheckbox'])
                                </div>

                                <div class="form-group{{ $errors->has('intro_text') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-intro_text">{{ __('Exam Introduction Text') }}</label>
                                    <textarea name="intro_text" id="input-intro_text"  class="ckeditor form-control{{ $errors->has('intro_text') ? ' is-invalid' : '' }}" placeholder="{{ __('Replace this text as exam introduction text') }}"  required autofocus> {{old('intro_text')}} </textarea>
                                    @include('alerts.feedback', ['field' => 'intro_text'])
                                </div>

                                <div class="form-group{{ $errors->has('end_of_time_text') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-end_of_time_text">{{ __('Exam End of Time Text') }}</label>
                                    <textarea name="end_of_time_text" id=""  class="ckeditor form-control{{ $errors->has('end_of_time_text') ? ' is-invalid' : '' }}" placeholder="{{ __('Replace this text as exam end of time text') }}"  required autofocus> {{ old('end_of_time_text') }} </textarea>
                                    @include('alerts.feedback', ['field' => 'end_of_time_text'])
                                </div>

                                <div class="form-group{{ $errors->has('success_text') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-success_text">{{ __('Exam Success Text') }}</label>
                                    <textarea name="success_text" id="input-success_text"  class="ckeditor form-control{{ $errors->has('success_text') ? ' is-invalid' : '' }}" placeholder="{{ __('Replace this text as exam success text') }}"  required autofocus>{{ old('success_text') }}</textarea>
                                    @include('alerts.feedback', ['field' => 'success_text'])
                                </div>

                                <div class="form-group{{ $errors->has('failure_text') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-failure_text">{{ __('Exam Failure Text') }}</label>
                                    <textarea name="failure_text" id="input-failure_text"  class="ckeditor form-control{{ $errors->has('failure_text') ? ' is-invalid' : '' }}" placeholder="{{ __('Replace this text as exam success text') }}"  required autofocus>{{ old('failure_text') }}</textarea>
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

        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')

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

@endpush
