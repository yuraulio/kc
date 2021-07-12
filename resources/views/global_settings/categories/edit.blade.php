@extends('layouts.app', [
    'title' => __('Role Management'),
    'parentSection' => 'laravel',
    'elementName' => 'role-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Examples') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('global.index') }}">{{ __('Global Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Category') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Category Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('global.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('category.update', $category) }}" autocomplete="off">
                            @csrf
                            @method('put')
                            <h6 class="heading-small text-muted mb-4">{{ __('Category information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', $category->name) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>
                                <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-description">{{ __('Description') }}</label>
                                    <textarea name="description" id="input-description" cols="30" rows="10" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}">{{ old('description', $category->description) }}</textarea>

                                    @include('alerts.feedback', ['field' => 'description'])
                                </div>

                                <div class="form-group{{ $errors->has('hours') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-hours">{{ __('Hours') }}</label>
                                    <input type="text" name="hours" id="input-hours" class="form-control{{ $errors->has('hours') ? ' is-invalid' : '' }}" placeholder="{{ __('Hours') }}" value="{{ old('hours', $category->hours) }}">

                                    @include('alerts.feedback', ['field' => 'hours'])
                                </div>

                                <?php //dd($data['folders']); ?>

                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Select Dropbox Folder</label>
                                    <?php //dd($already_assign[0]); ?>
                                    <select class="form-control" name="folder_name" id="folder_name">

                                        @foreach($data['folders'] as $folder)

                                            <?php $found = false; ?>
                                            @foreach($already_assign as $ass)
                                                @if(isset($ass) && $ass['folder_name'] == $folder)
                                                <?php $found = true; ?>

                                                @endif
                                            @endforeach
                                            @if($found)
                                                <option selected value="{{ $folder }}">{{ $folder }}</option>
                                            @else
                                                <option value="{{ $folder }}">{{ $folder }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'dropbox'])
                                </div>

                                <?php //dd($category->show_homepage); ?>

                                <div class="form-group{{ $errors->has('show_homepage') ? ' has-danger' : '' }}">
                                    <div class="status-label">
                                        <label class="form-control-label" for="input-show_homepage">{{ __('Show Homepage') }}</label>
                                    </div>
                                    <div class="status-toogle">
                                        <label class="custom-toggle">
                                            <input type="checkbox" name="show_homepage" id="input-show_homepage" <?= ($category->show_homepage == 1) ? 'checked' : ''; ?>>
                                            <span class="custom-toggle-slider rounded-circle"></span>
                                        </label>
                                        @include('alerts.feedback', ['field' => 'show_homepage'])
                                    </div>
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
