@extends('layouts.app', [
    'title' => __('Faqs Edit Category Management'),
    'parentSection' => 'laravel',
    'elementName' => 'faqs-category-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('faqs.categories') }}">{{ __('Faqs Category Management') }}</a></li>
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
                                <h3 class="mb-0">{{ __('Faqs Edit Category Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('faqs.categories') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    <?php //dd($category); ?>
                        <form method="post" action="{{ route('faqs.update_category', $category) }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Faq Category information') }}</h6>
                            <div class="pl-lg-4">

                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', $category->name) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>

                                <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-description">{{ __('Description') }}</label>
                                    {{--<textarea type="text" name="description" id="input-description" class="ckeditor form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}">{{ old('description', $category->description) }}</textarea>--}}

                                    <!-- anto's editor -->
                                    <input class="hidden" name="description" value="{{ old('description', $category->description) }}"/>
                                    <?php $data = old('description', $category->description); ?>
                                    @include('event.editor.editor', ['keyinput' => "input-description", 'data'=> "$data", 'inputname' => "'description'" ])
                                    <!-- anto's editor -->

                                    @include('alerts.feedback', ['field' => 'description'])
                                </div>

                                <input type="hidden" name="id" value="{{$category['id']}}">

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
