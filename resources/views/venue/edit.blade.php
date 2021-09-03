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

            <li class="breadcrumb-item"><a href="{{ route('events.index') }}">{{ __('Venues Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit venue') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Venues Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('events.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <?php //dd($ticket[0]); ?>
                    <div class="card-body">
                        <form method="post" action="{{ route('venue.update', $venue) }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Venue information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', $venue->name) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>

                                <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-address">{{ __('Address') }}</label>
                                    <input type="text" name="address" id="input-address" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" placeholder="{{ __('Address') }}" value="{{ old('address', $venue->address) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'address'])
                                </div>

                                <div class="form-group{{ $errors->has('longitude') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-longitude">{{ __('Longitude') }}</label>
                                    <input type="number" min="-180" max="180" step="any" name="longitude" id="input-longitude" class="form-control{{ $errors->has('longitude') ? ' is-invalid' : '' }}" placeholder="{{ __('Longitude') }}" value="{{ old('longitude', $venue->longitude) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'longitude'])
                                </div>

                                <div class="form-group{{ $errors->has('latitude') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-latitude">{{ __('Latitude') }}</label>
                                    <input type="number" min="-90" max="90" step="any" name="latitude" id="input-latitude" class="form-control{{ $errors->has('latitude') ? ' is-invalid' : '' }}" placeholder="{{ __('Latitude') }}" value="{{ old('latitude', $venue->latitude) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'latitude'])
                                </div>

                                <input type="hidden" name="event_id" value="{{$event_id}}">

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
