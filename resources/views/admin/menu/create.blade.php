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

            <li class="breadcrumb-item"><a href="{{ route('menu.index') }}">{{ __('Menu Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Add Menu') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Menu Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('menu.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('menu.store') }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">{{ __('Menu information') }}</h6>
                            <div class="pl-lg-4">


                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>

                                <div class="form-group{{ $errors->has('menu') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-menu">{{ __('Menu') }}</label>
                                    <select multiple name="menu[]" id="input-menu" class="form-control" placeholder="{{ __('Menu') }}">
                                        @foreach($categories as $category)
                                            <option value="Category-{{$category['id']}}">{{$category['name']}}</option>
                                        @endforeach
                                        @foreach($types as $type)
                                            <option value="Type-{{$type['id']}}">{{$type['name']}}</option>
                                        @endforeach
                                        @foreach($deliveries as $delivery)
                                            <option value="Delivery-{{$delivery['id']}}">{{$delivery['name']}}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'menu'])
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
