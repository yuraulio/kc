@extends('layouts.app', [
    'title' => __('Faqs Management'),
    'parentSection' => 'laravel',
    'elementName' => 'faqs-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('faqs.index') }}">{{ __('Faqs Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Faq') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Faqs Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('faqs.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('faqs.update', $faq) }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Faq information') }}</h6>
                            <div class="pl-lg-4">

                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                    <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title', $faq->title) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'title'])
                                </div>

                                <div class="form-group{{ $errors->has('answer') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-answer">{{ __('Answer') }}</label>
                                    {{--<textarea name="answer" id="input-answer" class="ckeditor form-control{{ $errors->has('answer') ? ' is-invalid' : '' }}" placeholder="{{ __('Faq') }}">{{ old('answer', $faq->answer) }}</textarea>--}}

                                    <!-- anto's editor -->
                                    <input class="hidden" name="answer" value="{{ old('answer', $faq->answer) }}"/>
                                    <?php $data = old('answer', $faq->answer); ?>
                                    @include('event.editor.editor', ['keyinput' => "input-answer", 'data'=> "$data", 'inputname' => "'answer'" ])
                                    <!-- anto's editor -->

                                    @include('alerts.feedback', ['field' => 'answer'])
                                </div>

                                <div class="form-group{{ $errors->has('category_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-category_id">{{ __('Category') }}</label>
                                    <select multiple name="category_id[]" id="input-category_id" class="form-control" placeholder="{{ __('Category') }}" required>
                                        @foreach ($categories as $category)

                                        <option value="{{ $category->id }}" @if(in_array($category->id,$faqCategories)) selected @endif>{{ $category->name }}</option>


                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'category_id'])
                                </div>
                                {{--<div class="form-group{{ $errors->has('category_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-category_id">{{ __('Event Category') }}</label>
                                    <select multiple name="eventcategory_id[]" id="input-category_id" class="form-control" placeholder="{{ __('Event Category') }}">
                                        <option value="">-</option>
                                        @foreach ($eventCategories as $category)
                                            <option value="{{ $category->id }}" @if(in_array($category->id,$faqEventCategories)) selected @endif>{{ $category->name }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'eventcategory_id'])
                                </div>--}}

                                <div class="form-group{{ $errors->has('category_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-category_id">{{ __('Event Category') }}</label>
                                    <select name="type" id="input-category_id" class="form-control" placeholder="{{ __('Event Category') }}">
                                        <option value="">-</option>
                                        
                                        <option @if(old('type', $faq->type) == 'both') selected @endif value="both">Both</option>
                                        <option @if(old('type', $faq->type) == 'in_class') selected @endif value="in_class">In Class</option>
                                        <option @if(old('type', $faq->type) == 'elearning') selected @endif value="elearning">Elearning</option>
                                       
                                    </select>

                                    @include('alerts.feedback', ['field' => 'type'])
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
<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('admin_assets/js/vendor.min.js')}}"></script>
@endpush
