@extends('layouts.app', [
    'title' => __('Partner Management'),
    'parentSection' => 'laravel',
    'elementName' => 'partners-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('partner.index') }}">{{ __('Partner Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Partner') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Partners Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('partner.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <?php //dd($partner[0]); ?>
                    <div class="card-body">
                        <form method="post" action="{{ route('partner.update', $partner) }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Partner information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', $partner->name) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>
                                <div class="form-group{{ $errors->has('url') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Url') }}</label>
                                    <input type="text" name="url" id="input-url" class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" placeholder="{{ __('Url') }}" value="{{ old('url', $partner->url) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'url'])
                                </div>



                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </form>
                        <div class="" id="media"  aria-labelledby="tabs-icons-text-2-tab">
                                  <div class="row">
                                     <div class="col-xl-12 order-xl-1">
                                     @include('admin.upload.upload', ['event' => ($media != null) ? $media : null, 'versions' => ['event-card', 'header-image', 'social-media-sharing']])
                                     </div>

                                  </div>
                                    @if($media != null && $media['name'] != '')
                                        <div id="version-btn" style="margin-bottom:20px" class="col">
                                            <a href="{{ route('media2.eventImage', $media) }}" target="_blank" class="btn btn-primary">{{ __('Versions') }}</a>
                                        </div>
                                    @endif
                                </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
