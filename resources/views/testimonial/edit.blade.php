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

            <li class="breadcrumb-item"><a href="{{ route('testimonials.index') }}">{{ __('Testimonials Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Testimonial') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Testimonials Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('testimonials.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('testimonials.update', $testimonial) }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Testimonial information') }}</h6>
                            <div class="pl-lg-4">

                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', $testimonial->name) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>

                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                    <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title', $testimonial->title) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'title'])
                                </div>

                                <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-status">{{ __('Status') }}</label>
                                    <select name="status" id="input-status" class="form-control" placeholder="{{ __('Status') }}" >
                                        <option value="">-</option>
                                            <option <?= ($testimonial['status'] == 1) ? 'selected="selected"' : ''; ?> value="1">{{ __('Enable') }}</option>
                                            <option <?= ($testimonial['status'] == 0) ? 'selected="selected"' : ''; ?> value="0">{{ __('Disable') }}</option>
                                    </select>

                                    @include('alerts.feedback', ['field' => 'status'])
                                </div>

                                <div class="form-group{{ $errors->has('category_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-category_id">{{ __('Category') }}</label>
                                    <select name="category_id" id="input-category_id" class="form-control" placeholder="{{ __('Category') }}" required>
                                        <option value="">-</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                            <?php if(count($testimonial->category) != 0){
                                                if($category->id == $testimonial->category[0]->id){
                                                    echo 'selected';
                                                }else{
                                                    echo '';
                                                }
                                            }
                                            ?>

                                                >{{ $category->name }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'category_id'])
                                </div>

                                <div class="form-group{{ $errors->has('testimonial') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-testimonial">{{ __('Testimonial') }}</label>
                                    <input type="text" name="testimonial" id="input-testimonial" class="form-control{{ $errors->has('testimonial') ? ' is-invalid' : '' }}" placeholder="{{ __('Testimonial') }}" value="{{ old('testimonial', $testimonial->testimonial) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'testimonial'])
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
