 <!-- VIDEOS -->
@if(isset($section_videos))
<div id="section-videos" class="event-section hidden-xs">
<?php //dd($section_videos['c_fields']['simple_textarea']['value']);
/*$data = file_get_contents("https://www.googleapis.com/youtube/v3/videos?key=YOUR_API_KEY&part=snippet&id=T0Jqdjbed40");
$json = json_decode($data);
var_dump($json->items[0]->snippet->thumbnails);*/

 ?>
    <div class="container">
       {{-- <div class="row">
            <div class="col-lg-12">
            <h2>{{ $section_videos->title }}</h2>
            @if($section_videos->body != '')
            {!! $section_videos->body !!}
            @else
            <p></p>
            @endif
            </div>
        </div>--}}
        <style type="text/css">

        </style>

    	           

            <div class="col-lg-10" style="margin: 0 auto;float: none;width: 90% !important;">
                @if(isset($section_videos['c_fields']['simple_textarea']))
                 <?php $lines = preg_split("/(\r\n|\n|\r)/", $section_videos['c_fields']['simple_textarea']['value']); ?>
                @else
                	<?php $lines = []; ?>
                	<p>There are no videos right now</p>
                @endif

                <div id="owlvideos" class="owl-carousel " data-pagi="true">
                @foreach($lines as $key => $video)

                <?php
                    $urlArr = explode("/",$video);
                    $urlArrNum = count($urlArr);

                    // YouTube video ID
                    $youtubeVideoId = $urlArr[$urlArrNum - 1];

                    // Generate youtube thumbnail url
                    $thumbURL = 'https://img.youtube.com/vi/'.$youtubeVideoId.'/mqdefault.jpg';
                ?>
                <div>
                <div class="video-thumbnail">
                    <img alt="video" src="{{ $thumbURL }}"/>
                    <a data-toggle="modal" href="#playvideo" data-target="#playvideo" data-theVideo="{{ $video }}" class="playWrapper">
                    <span class="playBtn"><img src="theme/assets/img/play.png" width="50" height="50" alt="play" title="play" /></span>
                  </a>
                  
                </div>
                </div>
                @endforeach
                </div>

            </div>
            @if(isset($lines) && count($lines) > 4)
            {{--<div class="col-lg-12">
                <div class="testimonials_navigation">

                    <span id="videos_carousel_back" class="testi-nav"><img src="theme/assets/img/round_arrow_left.svg" alt="Previous" title="Previous" /></span>
                    <span id="videos_carousel_next" class="testi-nav"><img src="theme/assets/img/round_arrow_right.svg" alt="Next" title="Next" /></span>
                </div>
            </div>--}}
            @endif
         </div>

 	
 </div>

<div class="fullscreen-modal modal fade" id="playvideo" tabindex="-1" role="dialog" aria-labelledby="playvideo" aria-hidden="true" style="z-index: 10001 !important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" style="padding: 0px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <div class="hs-responsive-embed-youtube">
                    <iframe style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allowFullScreen="true" src=""></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
 @endif


