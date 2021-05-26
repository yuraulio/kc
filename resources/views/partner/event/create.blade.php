@extends('layouts.app', [
    'title' => __('Partner Management'),
    'parentSection' => 'laravel',
    'elementName' => 'item-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Examples') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('partner.index') }}">{{ __('Partner Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Assign partner') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Partner Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('partner.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    <?php //dd($event); ?>
                        <form method="post" class="item-form" action="{{ route('partner.store_event') }}" autocomplete="off" enctype="multipart/form-data">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">{{ __('Partner information') }}</h6>
                            <div class="pl-lg-4">

                            <div class="form-group{{ $errors->has('partner_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-partner_id">{{ __('Partner') }}</label>
                                    <select name="partner_id" id="input-partner_id" class="form-control" placeholder="{{ __('Partner') }}">
                                        <option value="">-</option>

                                        @foreach ($partners as $partner)
                                            <?php $found = false; ?>
                                            @foreach($event->partners as $event_partner)
                                                @if($event_partner['id'] == $partner->id)
                                                    <?php $found = true; ?>
                                                @endif
                                            @endforeach
                                            @if($found == false)
                                            <option value="{{ $partner->id }}" >{{ $partner->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'partner_id'])
                                </div>

                                <input type="hidden" name="event_id" value="{{$event['id']}}">


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

@push('css')
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/quill/dist/quill.core.css">
@endpush

@push('js')
    <script src="{{ asset('argon') }}/vendor/select2/dist/js/select2.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/quill/dist/quill.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('argon') }}/js/items.js"></script>
@endpush
