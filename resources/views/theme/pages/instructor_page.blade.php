@extends('theme.layouts.master')
@section('metas')
    <title>{{ $title }}</title>
@endsection
@section('content')
@include('theme.preview.preview_warning', ["id" => $content->id, "type" => "content", "status" => $content->status])

<main id="main-area" role="main">

                <div class="instructor-wrapper">
                    <div class="container">

                        <div class="instructor-area instructor-profile">
                            <div class="row">
                                <div class="col4 col-xs-12">
                                    <div class="avatar-wrapper">


                                        <div class="avatar" alt="{{ $title }}" title="{{ $title }}"  style="background-image:url({{cdn(get_image($content['medias'],'instructors-testimonials'))}});"></div>
                                        <div class="social-links">
                                            <?php $social_media = json_decode($content['social_media'], true); ?>

                                            @if(isset($social_media['facebook']) && $social_media['facebook'] != '')
                                             <a target="_blank" href="{{ $social_media['facebook'] }}">
                                             <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Facebook.svg')}}" width="23" alt="Visit"></a>
                                             @endif
                                             @if(isset($social_media['instagram']) && $social_media['instagram'] != '')
                                             <a target="_blank" href="{{ $social_media['instagram'] }}">
                                             <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Instagram.svg')}}" width="23" alt="Visit"></a>
                                             @endif
                                             @if(isset($social_media['linkedin']) && $social_media['linkedin'] != '')
                                             <a target="_blank" href="{{ $social_media['linkedin'] }}">
                                             <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Linkedin.svg')}}" width="23" alt="Visit"></a>
                                             @endif
                                             @if(isset($social_media['twitter']) && $social_media['twitter'] != '')
                                             <a target="_blank" href="{{ $social_media['twitter'] }}">
                                             <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Twitter.svg')}}" width="23" alt="Visit"></a>
                                             @endif
                                             @if(isset($social_media['youtube']) && $social_media['youtube'] != '')
                                             <a target="_blank" href="{{ $social_media['youtube'] }}">
                                             <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Youtube.svg')}}" width="23" alt="Visit"></a>
                                             @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col8 col-xs-12">
                                <?php
                                    if(isset($content['ext_url'])){
                                        $field2 = $content['ext_url'];
                                        $field2 = str_replace ( "https://www.", "", $field2 );
                                        $field2 = str_replace ( "https://.", "", $field2 );
                                        $field2 = str_replace ( "http://www.", "", $field2 );
                                        
                                    }
                                ?>
                                    <div class="text-area">

                                        <h1>{{ $title }}</h1>
                                        <h2>{{ $content['header'] }},@if(isset($content['ext_url'])) <a target="_blank" title="{{ $field2 }}" href="{{ $content['ext_url'] }}"> {{ $field2 }}</a> @endif</h2>
                                        {!! $content['body'] !!}
                                    </div>
                                </div>
                            </div>
                        </div><!-- ./instructor-area -->

                        @if(count($instructorTeaches) >0)
                        <div class="instructor-area instructor-studies">
                            <h2>{{ $content->title }} {{ $content->subtitle }} teaches:</h2>
                            <ul>
                                @foreach($instructorTeaches as $teach)
                                <li><img width="25" src="{{cdn('/theme/assets/images/icons/graduate-hat.svg')}}" alt="">{{$teach}}</li>

                                @endforeach
                            </ul>
                        </div><!-- ./instructor-area -->
                        @endif

                        @if(isset($instructorEvents) && count($instructorEvents) > 0)

                            <div class="instructor-area instructor-courses">
                                <h2>{{ $content->title }} {{ $content->subtitle }} participates in:</h2>

                                <div class="dynamic-courses-wrapper">

                                @foreach($instructorEvents as $key => $row)
                                    @if(isset($row))
                                        <?php $estatus = $row['status']; ?>

                                        @if($estatus == 0 || $estatus == 2)

                                            @if($row['view_tpl'] =='elearning_event' || $row['view_tpl'] =='elearning_greek' || $row['view_tpl'] =='elearning_event' || $row['view_tpl'] =='elearning_free')

                                            <div class="item">
                                                <div class="left">
                                                    <h2>{{ $row['title'] }}</h2>



                                                </div>
                                                <div class="right right--no-price">
                                                    <a href="{{ $row['slugable']['slug'] }}" class="btn btn--secondary btn--md">Course Details</a>
                                                </div>
                                            </div><!-- ./item -->
                                            @endif
                                        @endif
                                    @endif
                                @endforeach
                                </div><!-- ./dynamic-courses-wrapper -->

                                <div class="dynamic-courses-wrapper dynamic-courses-wrapper--style2">
                                    @foreach($instructorEvents as $key => $row)

                                        <?php $estatus = $row['status']; ?>

                                        @if($estatus == 0 || $estatus == 2)
                                        <?php //dd($row); ?>
                                            @if($row['view_tpl'] !='elearning_event' && $row['view_tpl'] !='elearning_greek' && $row['view_tpl'] !='elearning_free')
                                            <div class="item">
                                                <div class="left">
                                                    <h2>{{ $row['title'] }}</h2>
                                                    {{--<?php
                                                    if(isset($row['city']) && count($row['city']) > 0){
                                                        dd($row['summary1']);
                                                    }
                                                     ?>--}}
                                                    <?php
                                                    if(isset($row['summary1']) && count($row['summary1']) >0){
                                                        foreach($row['summary1'] as $sum){
                                                            if($sum['section'] == 'date')
                                                                $date = $sum['title'];
                                                        }
                                                    }
                                                     ?>

                                                    <div class="bottom">
                                                    @if(count($row['city']) > 0 )<a href="{{ $row['city'][0]['slugable']['slug'] }}" title="{{ $row['city'][0]['name'] }}" class="location"><img width="20" src="/theme/assets/images/icons/marker.svg" alt="">{{ $row['city'][0]['name'] }}</a> @endif
                                                    @if (isset($date) && $date != '') <div class="duration"><img width="20" src="theme/assets/images/icons/icon-calendar.svg" alt=""> {{ $date }} </div>@endif
                                                    @if($row['hours'] && (is_numeric(substr($row['hours'], 0, 1))))  <div class="expire-date"><img width="20" src="theme/assets/images/icons/Start-Finish.svg" alt="">{{ $row['hours'] }}</div>@endif
                                                    </div>

                                                </div>
                                                <div class="right right--no-price">
                                                    <a href="{{ $row['slugable']['slug'] }}" class="btn btn--secondary btn--md">Course Details</a>
                                                </div>
                                            </div><!-- ./item -->
                                            @endif
                                        @endif

                                    @endforeach
                                </div><!-- ./dynamic-courses-wrapper -->

                            </div><!-- ./instructor-area -->

                        @endif

                    </div>
                </div><!-- ./instructor-wrapper -->

			<!-- /#main-area -->
			</main>

@endsection

@section('scripts')

@stop

