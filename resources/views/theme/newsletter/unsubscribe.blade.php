@extends('theme.layouts.master')
@section('content')
    @include('theme.layouts.header_pages')
<div class="page-content-register">
    <div class="container">
        {!! Form::model($subscriber, ['route' => 'newsletter.unsubscribeSbt', 'method' => 'post']) !!}
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <h1 class="register-main-head">Remove Newsletter Email</h1>
                    <div class="row form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            {!! Form::label('email', 'Email : ') !!}
                            {!! Form::input('text', 'email', null, ['required' => 'required', 'class' => 'newsletter_remove_email', 'placeholder' => 'Email']) !!}
                            {!! Form::submit('Remove', ['class' => 'btn btn-danger btn-sm']) !!}
                        </div>
                    </div>
                    <div id="errors"></div>
                    <span id="loadingDiv">
                        <img class="img-responsive center-block" src="theme/assets/img/ajax-loader.gif" />
                    </span>
                    <div id="success"></div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    @if (count($errors) > 0)
                    <h1 class="register-main-head">Errors</h1>
                    <div class="alert alert-danger">
                        <ul class="register-required">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if (isset($opstatus) && $opstatus)
                    <script>
                        @if ($opstatus)
                        notify("{!! $opmessage !!}", 'success', 5000);
                        @else
                        notify("{!! $opmessage !!}", 'error', 5000);
                        @endif
                    </script>
                    @endif
                    <h1 class="register-main-head">Why stay registered</h1>
                    <ul class="register-required">
                        <li>
                            Reason 1
                        </li>
                        <li>
                            Reason 2
                        </li>
                    </ul>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

