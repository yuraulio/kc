@php
    $event = $dynamic_page_data["event"] ?? null;
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
    </div>

@endif