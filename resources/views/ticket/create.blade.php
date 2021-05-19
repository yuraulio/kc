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

            <li class="breadcrumb-item"><a href="{{ route('ticket.index') }}">{{ __('Events Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Add Event') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Ticket Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('ticket.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('ticket.store') }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">{{ __('Ticket information') }}</h6>
                            <div class="pl-lg-4">


                            <div class="form-group{{ $errors->has('ticket_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-ticket_id">{{ __('Ticket') }}</label>
                                    <select name="ticket_id" id="input-ticket_id" class="form-control" placeholder="{{ __('Ticket') }}">
                                        <option value="">-</option>
                                        @foreach ($tickets as $ticket)
                                            <option value="{{ $ticket->id }}" >{{ $ticket->title }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'ticket_id'])
                                </div>

                                <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-price">{{ __('Price') }}</label>
                                    <input type="number" min="0.00" max="10000.00" step="0.50" name="price" id="input-price" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" placeholder="{{ __('Price') }}" value="{{ old('price') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'price'])
                                </div>

                                <div class="form-group{{ $errors->has('priority') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-priority">{{ __('Priority') }}</label>
                                    <input type="number" min="0" name="priority" id="input-priority" class="form-control{{ $errors->has('priority') ? ' is-invalid' : '' }}" placeholder="{{ __('Priority') }}" value="{{ old('priority') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'priority'])
                                </div>

                                <div class="form-group{{ $errors->has('quantity') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-quantity">{{ __('Quantity') }}</label>
                                    <input type="number" min="0" name="quantity" id="input-quantity" class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}" placeholder="{{ __('Quantity') }}" value="{{ old('quantity') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'quantity'])
                                </div>

                                <div class="form-group{{ $errors->has('option') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-option">{{ __('Option') }}</label>
                                    <textarea type="textarea" name="option" id="input-option" class="form-control{{ $errors->has('option') ? ' is-invalid' : '' }}" placeholder="{{ __('Option') }}" value="{{ old('option') }}" autofocus></textarea>

                                    @include('alerts.feedback', ['field' => 'option'])
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
