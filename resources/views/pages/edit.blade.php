@extends('layouts.app', [
    'title' => __('Pages Management'),
    'parentSection' => 'laravel',
    'elementName' => 'pages-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('pages.index') }}">{{ __('Pages Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Page') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Pages Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('pages.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('pages.update', $page) }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Page information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                    <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title', $page->title) }}"  required autofocus>

                                    @include('alerts.feedback', ['field' => 'title'])
                                </div>

                                <div class="form-group{{ $errors->has('content') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-content">{{ __('Page Editor') }}</label>
                                    <textarea name="content" id="input-content"  class="ckeditor form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" placeholder="{{ __('Page editor') }}" value="{{ old('content', $page->content) }}"  required autofocus>{{ old('content', $page->content) }}</textarea>

                                    @include('alerts.feedback', ['field' => 'content'])
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

@push('js')

    <script type="text/javascript">
        $(document).ready(function () {
            $('.ckeditor').ckeditor();
        });
    </script>
@endpush


