@php
    $sections = $dynamic_page_data["sections"] ?? null;
    $topics = $dynamic_page_data["topics"] ?? null;
    $testimonials = $dynamic_page_data["testimonials"] ?? null;
    $title = '';
    $body = '';
    if(isset($sections['testimonials'])){
        $title = $sections['testimonials']->first()->title ?? null;
        $body = $sections['testimonials']->first()->description ?? null;
    }
@endphp

@if(count($testimonials) > 0)
    <?php
        $title = '';
        $body = '';
        if(isset($sections['testimonials'])){
            $title = $sections['testimonials']->first()->title;
            $body = $sections['testimonials']->first()->description;
        }
    ?>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="course-full-text full-w-pad">
                <h2 class="text-align-center text-xs-left tab-title">{!!$title!!}</h2>
                <h3 class="text-align-center text-xs-left tab-title"> {!!$body!!} </h3>
                <div class="testimonial-carousel-wrapper hidden-xs">

                    <div class="video-carousel-big owl-carousel">
                        @foreach($testimonials as $key => $video)
                        <?php

                            if(!$video['video_url']){
                            continue;
                            }
                            $urlArr = explode("/",$video['video_url']);
                            $urlArrNum = count($urlArr);

                            // YouTube video ID
                            $youtubeVideoId = $urlArr[$urlArrNum - 1];

                            // Generate youtube thumbnail url
                            $thumbURL = 'https://img.youtube.com/vi/'.$youtubeVideoId.'/mqdefault.jpg';
                            ?>
                        <div class="slide">
                            <a data-fancybox href="{{ $video['video_url'] }}"><img src="{{ $thumbURL }}" alt=""/></a>
                        </div>
                        @endforeach
                    </div>
                    <!-- /.testimonial-carousel-wrapper -->
                </div>
                <!-- /.course-full-text -->
                </div>
            </div>
        </div>
    </div>

    <div class="user-testimonial-wrapper">
        <div class="container">
        <div class="user-testimonial-big owl-carousel">

            @foreach ($testimonials as $key => $row)
            @if($row['video_url'])
                    <?php continue;?>
            @endif
            <div class="slide">
                <div class="testimonial-box">
                    <div class="author-infos">
                    <div class="author-img">
                        <img src="{{ cdn(get_image($row['mediable'],'users')) }}" alt="{!! $row['name'] !!}">
                    </div>
                    <span class="author-name">
                    {!! $row['name'] !!} {!! $row['lastname'] !!}</span>
                    <span class="author-job">{!! $row['title'] !!}</span>
                    </div>
                    <div class="testimonial-text">
                    <?php
                            $rev = $row['testimonial'];
                            $rev = str_replace('"','',$rev);
                    ?>
                    {!! $row['testimonial'] !!}
                    </div>
                </div>
                <script type="application/ld+json">
                                        {
                                            "@context": "https://schema.org/",
                                            "@type": "UserReview",
                                            "itemReviewed": {
                                            "@type": "Course",
                                            "provider": "Know Crunch",
                                            "image": "",
                                            "name": "{!!$event->title!!}",
                                            "description": "{!! $event->subtitle !!}"
                                            },
                                            "reviewRating": {
                                            "@type": "Rating",
                                            "ratingValue": "5"
                                            },
                                            "name": "{!!$event->title!!}",
                                            "author": {
                                            "@type": "Person",
                                            "name": "{!! $row['name'] !!} {!! $row['lastname'] !!}"
                                            },
                                            "reviewBody": "{!! $rev !!}",
                                            "publisher": {
                                            "@type": "Organization",
                                            "name": "KnowCrunch"
                                            }
                                        }
                                    </script>

                <!-- /.slide -->
            </div>
            @endforeach

        </div>
        </div>

@endif