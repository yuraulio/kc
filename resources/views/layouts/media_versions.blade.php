@extends('layouts.app', [
    'title' => __('Event Management'),
    'parentSection' => 'laravel',
    'elementName' => 'events-management'
])

@section('content')
    @component('layouts.headers.auth')
    @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __() }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('events.index') }}">{{ __('Events Management') }}</a></li>
        @endcomponent
    @endcomponent
    <?php
    //dd($media);
    //$model_type = get_class($media);
    if($media['mediable_type'] == 'App\Model\User'){
        $versions = [];
    }else if($media['mediable_type'] == 'App\Model\Event'){
        $versions = ['social-media-sharing','instructors-testimonials', 'event-card', 'users' ,'header-image', 'instructors-small' ,'feed-image'];
    }else if($media['mediable_type'] == 'App\Model\Pages' || $media['mediable_type'] == 'App\Model\Logos'){
        $versions = ['social-media-sharing','instructors-testimonials', 'event-card', 'users' ,'header-image', 'instructors-small' ,'feed-image'];
    }else if($media['mediable_type'] == 'App\Model\Instructor'){
        $versions = ['social-media-sharing','instructors-testimonials', 'event-card', 'users' ,'header-image', 'instructors-small' ,'feed-image'];
    }else if($media['mediable_type'] == 'App\Model\Testimonial'){
        $versions = ['social-media-sharing','instructors-testimonials', 'event-card', 'users' ,'header-image', 'instructors-small' ,'feed-image'];
    }
    ?>

    <?php
     ?>
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col image-version-page">
                @include('event.image_versions_new', ['event' => $media,'versions1'=> $versions])
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>

@endsection
