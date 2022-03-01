@extends('layouts.app', [
    'title' => __('Topic Edit'),
    'parentSection' => 'laravel',
    'elementName' => 'topics-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
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
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('topics.update', $topic) }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <input name="fromCategory" value="{{$fromCategory}}" hidden>
                            <h6 class="heading-small text-muted mb-4">{{ __('Topic information') }}</h6>
                            <div class="pl-lg-4">

                                <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">


                                    <label class="custom-toggle custom-published">
                                            <input type="checkbox" name="status" id="input-status" @if($topic['status'] == '1') checked @endif>
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                                        </label>
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

                                <div class="form-group{{ $errors->has('category_id') ? ' has-danger' : '' }} topic-list">
                                    <label class="form-control-label" for="input-category_id">{{ __('Category') }}</label>
                                    <select multiple name="category_id[]" id="input-category_id" class="form-control" placeholder="{{ __('Category') }}" required>
                                        <option value="">-</option>
                                        @foreach ($categories as $category)
                                            <option <?php if(count($topic->category) != 0){


                                                if(in_array($category->id,$topic->category()->pluck('category_id')->toArray())){
                                                    echo 'selected';
                                                }else{
                                                    echo '';
                                                }
                                            }
                                            ?>
                                            value="{{ $category->id }}" >{{ $category->name }}</option>
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
                                    <textarea name="summary" id="input-summary"  class="ckeditor form-control{{ $errors->has('summary') ? ' is-invalid' : '' }}" placeholder="{{ __('Summary') }}" required autofocus>{{ old('summary', $topic->summary) }}</textarea>

                                    @include('alerts.feedback', ['field' => 'summary'])
                                </div>

                                <div class="form-group{{ $errors->has('body') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-body">{{ __('Body') }}</label>
                                    <textarea name="body" id="input-body"  class="ckeditor form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" placeholder="{{ __('Body') }}" required autofocus>{{ old('body', $topic->body) }}</textarea>

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
