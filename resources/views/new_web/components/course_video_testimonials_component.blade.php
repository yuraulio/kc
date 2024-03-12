@php
    use App\Library\CMS;
    use App\Model\Event;

    $title = true;
    if (!isset($dynamic_page_data["testimonials"])) {
        $title = false;
        $video_testimonials = [];
        foreach ($column->template->inputs as $input){
            $video_testimonials[$input->key] = $input->value ?? "";
        }
        $eventId = $video_testimonials["course_video_testimonials_event"]->id ?? null;
        $event = Event::where("id", $eventId)->first() ?? null;
        $dynamic_page_data = CMS::getEventData($event) ?? null;
    }

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
        if(isset($sections['testimonials']) && $title){
            $title = $sections['testimonials']->first()->title;
            $body = $sections['testimonials']->first()->description;
        }
    ?>

    <div class="course-full-text full-w-pad video-testimonials">
        <h2 class="text-align-center text-xs-left tab-title">{!!$title!!}</h2>
        <h3 class="text-align-center text-xs-left tab-title"> {!!$body!!} </h3>
        <div class="testimonial-carousel-wrapper hidden-xs">

            <div class="video-carousel-big owl-carousel">
                @foreach($testimonials as $key => $video)
                <?php

                    if(!$video['video_url']){
                      continue;
                    }
                    $q = parse_url($video['video_url'], PHP_URL_QUERY);
                    parse_str($q, $data);
                    if (empty($data['v'])) {
                      continue;
                    }
                    // Generate youtube thumbnail url
                    $thumbURL = 'https://img.youtube.com/vi/'.$data['v'].'/mqdefault.jpg';
                    ?>
                <div class="slide">
                    <div class="testimonial-box">
                    <a data-fancybox href="{{ $video['video_url'] }}"><img loading="lazy" class="resp-img" src="{{ $thumbURL }}" alt="thumb-youtube" title="thumb-youtube" width="200" height="100"/></a>
                        <div style="margin-top:0.5rem" class="author-infos text-center">
                            <span>{{ $video['title']}}</span>
                        </div>
                    </div>


                </div>
                @endforeach
            </div>
            <!-- /.testimonial-carousel-wrapper -->
        </div>
        <!-- /.course-full-text -->
    </div>
@endif
