@extends('layouts.app', [
    'title' => __('Career Edit'),
    'parentSection' => 'laravel',
    'elementName' => 'careers-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('career.index') }}">{{ __('Career Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Career') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Career Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('career.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('career.update', $career) }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Career information') }}</h6>
                            <div class="pl-lg-4">

                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Title') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', $career->name) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>

                                <div class="form-group{{ $errors->has('event_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-event_id">{{ __('Event') }}</label>
                                    <select multiple name="event_id[]" id="input-event_id" class="form-control events" placeholder="{{ __('Topic') }}" required>
                                        <option value="">-</option>
                                        @foreach ($events as $event)
                                            <?php $selected = false; ?>
                                            @foreach($career->events as $selected_event)
                                                @if($event->id == $selected_event['id'])
                                                    {{$selected = true}}
                                                @endif

                                            @endforeach


                                            <option <?php if($selected === true){echo 'selected';} ?> data-event="{{ $event->category[0]->id }}" value="{{ $event->id }}" > {{ $event->title }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'event_id'])
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
