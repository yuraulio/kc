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

            <li class="breadcrumb-item"><a href="{{ route('ticket.index') }}">{{ __('Ticket Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Ticket') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Tickets Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('ticket.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <?php //dd($ticket[0]); ?>
                    <div class="card-body">
                        <form method="post" action="{{ route('ticket.update_main', $ticket) }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Ticket information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                    <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title', $ticket->title) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'title'])
                                </div>

                                <div class="form-group{{ $errors->has('subtitle') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-subtitle">{{ __('Subtitle') }}</label>
                                    <input type="text" name="subtitle" id="input-subtitle" class="form-control{{ $errors->has('subtitle') ? ' is-invalid' : '' }}" placeholder="{{ __('Subtitle') }}" value="{{ old('subtitle', $ticket->subtitle) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'subtitle'])
                                </div>


                                <?php //dd($type); ?>
                                <div class="form-group{{ $errors->has('type') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-type">{{ __('Type') }}</label>
                                    <select name="type" id="input-type" class="form-control" placeholder="{{ __('Type') }}" required>
                                        <option value="">-</option>
                                        <option <?php if($ticket['type'] == 'Alumni')  echo 'selected'; ?> value="Alumni">Alumni</option>
                                        <option <?php if($ticket['type'] == 'Regular')  echo 'selected'; ?> value="Regular">Regural</option>
                                        <option <?php if($ticket['type'] == 'Special')  echo 'selected'; ?> value="Special">Special</option>
                                    </select>

                                    @include('alerts.feedback', ['field' => 'type'])
                                </div>

                                <div class="form-group{{ $errors->has('features') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-features">{{ __('Features') }}</label>
                                    <input type="text" name="features" id="input-features" class="form-control{{ $errors->has('features') ? ' is-invalid' : '' }}" placeholder="{{ __('Features') }}" value="{{ old('features', $ticket->features) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'features'])
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
