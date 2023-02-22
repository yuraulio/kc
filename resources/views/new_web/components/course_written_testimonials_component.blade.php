@php
    use App\Library\CMS;
    use App\Model\Event;

    if (!isset($dynamic_page_data["testimonials"])) {
        $written_testimonials = [];
        foreach ($column->template->inputs as $input){
            $written_testimonials[$input->key] = $input->value ?? "";
        }
        $eventId = $written_testimonials["course_written_testimonials_event"]->id ?? null;
        $event = Event::where("id", $eventId)->first() ?? null;
        $dynamic_page_data = CMS::getEventData($event) ?? null;
    }

    $event = $dynamic_page_data["event"] ?? null;
    $sections = $dynamic_page_data["sections"] ?? null;
    $topics = $dynamic_page_data["topics"] ?? null;
    $testimonials = $dynamic_page_data["testimonials"] ?? [];
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

    <div class="user-testimonial-wrapper written-testimonials video-testimonials">
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
                                    <?php
                                    $imageDetails = get_image_version_details('users');
                                    ?>
                                    <img loading="lazy" onerror="this.src='{{cdn('/theme/assets/images/icons/user-circle-placeholder.svg')}}'" src="{{ cdn(get_image($row['mediable'],'users')) }}" alt="{!! $row['name'] !!}" title="{!! $row['name'] !!}" width="{{ $imageDetails['w'] }}" height="{{ $imageDetails['h'] }}">
                                </div>
                                <span class="author-name">
                                {!! $row['name'] !!} {!! $row['lastname'] !!}</span>
                                <span class="author-job">{!! $row['title'] !!}</span>
                                <?php $socials = json_decode($row['social_url'], true) ?>

                                <ul class="social-wrapper">
                                     @if(isset($socials['facebook']))

                                        @if(strpos($socials['facebook'],'https://') === false)
                                           <?php $socials['facebook'] = 'https://'.$socials['facebook']; ?>
                                        @endif

                                        <li><a target="_blank" href="{{$socials['facebook']}}"><img class="replace-with-svg"  src="/theme/assets/images/icons/social/Facebook.svg" width="16" alt="Visit"></a></li>
                                     @endif

                                     @if(isset($socials['linkedin']))

                                        @if(strpos($socials['linkedin'],'https://') === false)
                                           <?php $socials['linkedin'] = 'https://'.$socials['linkedin']; ?>
                                        @endif

                                        <li><a target="_blank" href="{{$socials['linkedin']}}"><img class="replace-with-svg"  src="/theme/assets/images/icons/social/Linkedin.svg" width="16" alt="Visit"></a></li>
                                     @endif


                                </ul>
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
