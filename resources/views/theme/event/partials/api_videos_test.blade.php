 <!-- VIDEOS -->

<!--  CONFIG ADD
 ,
    "videos": {
        "abbr": "videos",
        "title": "Videos Block Section",
        "view": "theme.event.partials.videos"
    }

ON EVENT POSITIONS -->
 @if(isset($section_videos))
<div id="section-videos" class="event-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
            <h2>{{ $section_videos->title }}</h2>
            </div>
        </div>
    	<div class="row">

        <!-- <div id="owlvideos" class="owl-carousel"> -->


            <div id="thevideos"></div>

        <!-- </div> -->
                <!-- <div class="testimonials_navigation">

                    <span id="testimonials_carousel_back" class="testi-nav"><img src="theme/assets/img/round_arrow_left.svg" alt="Previous" /></span>
                    <span id="testimonials_carousel_next" class="testi-nav"><img src="theme/assets/img/round_arrow_right.svg" alt="Next" /></span>
                </div> -->






                <?php
                  //$htmlinput = htmlentities($section_videos->body);
                /*$parentArray = explode('|video|', htmlentities($section_videos->body));*/
                ?>


<!-- <iframe src="https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2Ffacebook%2Fvideos%2F10153231379946729%2F&width=500&show_text=false&appId=1379279088749713&height=280" width="500" height="280" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allowFullScreen="true"></iframe> -->


<iframe src="https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2FKnowCrunch%2Fvideos%2F1587380654668571%2F&show_text=0&width=560" width="560" height="315" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allowFullScreen="true"></iframe>

https://www.facebook.com/plugins/video.php?href=https://www.facebook.com/Knowcrunch/videos/2F1587380654668571/
 		</div>
 	</div>
 </div>
 @endif
 <!-- VIDEOS END /{page-id}/video_lists -->
<!-- https://www.facebook.com/Knowcrunch/videos/1625323314207638/ -->
<!-- ?access_token=378574919191027|y8eycTxvHs1pFRKk2zxl5h-AQy4 -->2F1587380654668571
@section('scripts')
<script type="text/javascript">

$(document).ready(function() {
    var owl_videos = $("#owl-videos");


/*     FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
            //display user data
            console.log(response);
            //getFbUserData();
        }
        else {
            console.log(response);
        }
    });*/
        /*FB.api("/me?access_token=378574919191027|y8eycTxvHs1pFRKk2zxl5h-AQy4",
                function (response) {
                    //alert('Name is ' + response.name);
                    if (response && !response.error) {

EAANqRmAsOikBAN39XNZCE7JZCr05zL63kSf7JYiOkTZBeCuSfUiFDFj5VPRtZCh2X7M3OJAX10doWaxvSJGs84HZAA7rv8ZAfymZAoWZARL2ZCjZAew9vZBkg3IBQ8W7k820mn1Ik17d2fOXvh5viqLKDJj0E0ubiZCT05FuQ9LmffyXgmtUYVN9cV2HdtqnPiiYEjkZD

EAAFYT9eUSfMBAD7lxfdTKSKZAJMO9NvmxZAj1csxXvb3RJxd7fQ4A31JhtfxRvXfPHcBGNjVEdx03dZBPTrKF05CHq3jlMRNODHc04K6t7p7quVCXZBauYjvTbZBSeWkcAfr0yPg06NHW9BclSEcP8AvaK4hz05ys7qsD4ufA96FG8ZC99p2tvhNwUUyzmjVEZD
                          }
                          else {
                            console.log(response.error);
                          }
                });*/


                    FB.api(
                        "/486868751386439/video_lists?access_token=EAAFYT9eUSfMBAD7lxfdTKSKZAJMO9NvmxZAj1csxXvb3RJxd7fQ4A31JhtfxRvXfPHcBGNjVEdx03dZBPTrKF05CHq3jlMRNODHc04K6t7p7quVCXZBauYjvTbZBSeWkcAfr0yPg06NHW9BclSEcP8AvaK4hz05ys7qsD4ufA96FG8ZC99p2tvhNwUUyzmjVEZD",
                        function (response) {
                          if (response && !response.error) {

                            for (var i = 0; i < response.data.length; i++){
                                if(response.data[i]['title'] == 'Instructors') {
                                    //console.log(response.data[i]['id']);
                                    getVideoList(response.data[i]['id']);
                                }
                                /*idArray.push(response.data[i].user.id);  */
                              }
                            //console.log(response);
                          }
                          else {
                            console.log(response.error);
                          }
                        }
                    );

                    function getVideoList(playlist_id) {

                        var placeme = $('#thevideos');
                        FB.api(
                        "/"+playlist_id+"/videos?access_token=EAAFYT9eUSfMBAD7lxfdTKSKZAJMO9NvmxZAj1csxXvb3RJxd7fQ4A31JhtfxRvXfPHcBGNjVEdx03dZBPTrKF05CHq3jlMRNODHc04K6t7p7quVCXZBauYjvTbZBSeWkcAfr0yPg06NHW9BclSEcP8AvaK4hz05ys7qsD4ufA96FG8ZC99p2tvhNwUUyzmjVEZD",
                        function (response) {
                          if (response && !response.error) {
                            //console.log(response);
                            var html = '';
                            for (var i = 0; i < response.data.length; i++){
                                 /*FB.api(
                                "/"+response.data[i]['id']+"?access_token=EAAFYT9eUSfMBAF8vLT4Ex9pp0gx9ZBCflv7k0OQsoECGPVrOns9oZBtqIQE8UYRoK3Rv6gEBbnoJueJAEPEW7r0mhgMCvBfoeZB6yHFbLQzf9acYvNrKkBg9yVWsezUtxSQsVEaAkzbFMU2ND2C11Jg3VnfZBDoy9iMFAhRa6jKqjuSBDhC7ihbZC9535UC0ZD",
                                function (lastresponse) {
                                    if (lastresponse && !lastresponse.error) {
                                        console.log(lastresponse);
                                    }
                                    else {

                                    }
                                }
                                );*/
                                html += '<div class="col-lg-3 col-md-3 col-sm-3"><div class="hs-responsive-embed-youtube">';
                                /*html += '<iframe src="https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2Ffacebook%2Fvideos%2F'+response.data[i]['id']+'%2F&width=500&show_text=false&appId=378574919191027&height=280" width="500" height="280" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allowFullScreen="true"></iframe>';*/
                                html += '<iframe src="https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2Ffacebook%2Fvideos%2F'+response.data[i]['id']+'%2F&show_text=false&appId=378574919191027&height=280" width="100%" height="auto" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allowFullScreen="true"></iframe>';
                                html += '</div></div>'

                                /*html += response.data[i]['id'];*/
                            }

                            placeme.html(html);
                           // initowlafter();
                          }
                          else {
                            console.log(response.error);
                          }
                        }
                    );
                    }

/*  function initowlafter() {

owl_videos.owlCarousel({
            autoplay: false,
            loop:true,
            slideBy: 4,
            mouseDrag: false,
            touchDrag: true,
            responsive : {
          // breakpoint from 0 up
          0 : {
              items: 1,
          },
          // breakpoint from 480 up
          480 : {
              items: 1,
          },
          // breakpoint from 768 up
          768 : {
              items: 4,
          }
      }

        });

}*/

                    //alert('done');
});
                </script>
@stop
