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

            <li class="breadcrumb-item"><a href="{{ route('topics.index') }}">{{ __('Topics Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Add Topic') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Assign Topic on Event Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('topics.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <?php //dd($topics); ?>
                    <div class="card-body">
                        <form method="post" action="{{ route('topics.store_event') }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">{{ __('Topic information') }}</h6>
                            <div class="pl-lg-4">

                                <div class="form-group{{ $errors->has('topic_ids') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-topic_ids">{{ __('Topic') }}</label>
                                    <select multiple name="topic_ids[]" id="input-topic_ids" class="form-control" placeholder="{{ __('Topic') }}" >
                                        <option value="">-</option>
                                        @foreach ($topics as $topic)
                                            <option value="{{ $topic->id }}">{{ $topic->title }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'topic_id'])
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
