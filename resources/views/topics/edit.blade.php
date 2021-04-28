@extends('layouts.app', [
    'title' => __('User Management'),
    'parentSection' => 'laravel',
    'elementName' => 'user-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Examples') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('topics.index') }}">{{ __('Topics Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Topic') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Topics Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('topics.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>>
                    <div class="card-body">
                        <form method="post" action="{{ route('topics.update', $topic) }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Topic information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('priority') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-priority">{{ __('Priority') }}</label>
                                    <input type="number" name="priority" id="input-priority" class="form-control{{ $errors->has('priority') ? ' is-invalid' : '' }}" placeholder="{{ __('Priority') }}" value="{{ old('priority', $topic->priority) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'priority'])
                                </div>

                                <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-status">{{ __('Status') }}</label>
                                    <input type="number" name="status" id="input-status" class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}" placeholder="{{ __('Status') }}" value="{{ old('status', $topic->status) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'status'])
                                </div>

                                <div class="form-group{{ $errors->has('comment_status') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-comment_status">{{ __('Comment status') }}</label>
                                    <input type="text" name="comment_status" id="input-comment_status" class="form-control{{ $errors->has('comment_status') ? ' is-invalid' : '' }}" placeholder="{{ __('Comment status') }}" value="{{ old('comment_status', $topic->comment_status) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'comment_status'])
                                </div>

                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                    <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title', $topic->title) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'title'])
                                </div>

                                <?php //dd($topic->category[0]->id); ?>

                                <div class="form-group{{ $errors->has('event_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-event">{{ __('Event') }}</label>
                                    <select name="event_id" id="input-event_id" class="form-control{{ $errors->has('event_id') ? ' is-invalid' : '' }}" placeholder="{{ __('Event') }}" required>
                                        
                                        <option value="">-</option>
                                        @foreach ($topic->event as $event)
                                            <option value="{{ $event->id }}" {{ $event->id == $topic->event[0]->id ? 'selected' : '' }}>{{ $event->title }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'event_id'])
                                </div>
                                <?php //dd($topic->event[0]->id); ?>
                                <div class="form-group{{ $errors->has('category_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-category_id">{{ __('Category') }}</label>
                                    <select name="category_id" id="input-category_id" class="form-control{{ $errors->has('category_id') ? ' is-invalid' : '' }}" placeholder="{{ __('Category') }}" required>
                                    
                                        <option value="">-</option>
                                        @foreach ($topic->category as $category)
                                        <?php print_r($category->id); ?>
                                            <option value="{{ $category->id }}" {{ $category->id == $topic->category[0]->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'category_id'])
                                </div>

                                <div class="form-group{{ $errors->has('short_title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-short_title">{{ __('Short title') }}</label>
                                    <input type="text" name="short_title" id="input-short_title" class="form-control{{ $errors->has('short_title') ? ' is-invalid' : '' }}" placeholder="{{ __('short_title') }}" value="{{ old('Short title', $topic->short_title) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'short_title'])
                                </div>

                                <div class="form-group{{ $errors->has('subtitle') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-subtitle">{{ __('Subtitle') }}</label>
                                    <input type="text" name="subtitle" id="input-subtitle" class="form-control{{ $errors->has('subtitle') ? ' is-invalid' : '' }}" placeholder="{{ __('subtitle') }}" value="{{ old('Subtitle', $topic->subtitle) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'subtitle'])
                                </div>

                                <div class="form-group{{ $errors->has('header') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-header">{{ __('Header') }}</label>
                                    <input type="text" name="header" id="input-header" class="form-control{{ $errors->has('header') ? ' is-invalid' : '' }}" placeholder="{{ __('Header') }}" value="{{ old('header', $topic->header) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'header'])
                                </div>

                                <div class="form-group{{ $errors->has('summary') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-summary">{{ __('Summary') }}</label>
                                    <input type="text" name="summary" id="input-summary" class="form-control{{ $errors->has('summary') ? ' is-invalid' : '' }}" placeholder="{{ __('Summary') }}" value="{{ old('summary', $topic->summary) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'summary'])
                                </div>

                                <div class="form-group{{ $errors->has('body') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-body">{{ __('Body') }}</label>
                                    <input type="text" name="body" id="input-body" class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" placeholder="{{ __('Body') }}" value="{{ old('body', $topic->body) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'body'])
                                </div>                              
                                    <input type="hidden" name="creator_id" id="input-creator_id" class="form-control" value="{{$topic->creator_id}}">
                                    <input type="hidden" name="author_id" id="input-author_id" class="form-control" value="{{$topic->author_id}}">

                                    @include('alerts.feedback', ['field' => 'ext_url'])
                                

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
