@extends('layouts.app', [
    'title' => __('Skill Edit'),
    'parentSection' => 'laravel',
    'elementName' => 'skills-management'
])

@section('content')
  @component('layouts.headers.auth')
    @component('layouts.headers.breadcrumbs')
      @slot('title')
        {{ __('') }}
      @endslot

      <li class="breadcrumb-item"><a href="{{ route('skill.index') }}">{{ __('Skills Management') }}</a></li>
      <li class="breadcrumb-item active" aria-current="type">{{ __('Edit Skill') }}</li>
    @endcomponent
  @endcomponent

  <div class="container-fluid mt--6">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-8">
                <h3 class="mb-0">{{ __('Skills Management') }}</h3>
              </div>
              <div class="col-4 text-right">
                <a href="{{ route('skill.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <form method="post" action="{{ route('skill.update', $skill) }}" autocomplete="off"
                  enctype="multipart/form-data">
              @csrf
              @method('put')

              <h6 class="heading-small text-muted mb-4">{{ __('Skill information') }}</h6>
              <div class="pl-lg-4">
                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                  <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', $skill->name) }}"  required autofocus>

                  @include('alerts.feedback', ['field' => 'name'])
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



