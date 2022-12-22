@extends('theme.layouts.master')

@section('metas')

   {!! $page->metable->getMetas() !!}

@endsection
@section('css')
<meta name="robots" content="NOINDEX,NOFOLLOW">
@stop

@section('content')

<main id="main-area" class="with-hero" role="main">
   @if (!Auth::user())
   <div  class="login-popup-wrapper-subscription">
      <div id="login-popup" class="login-popup">
         <a href="#" class="close-btn"><img width="26" src="{{cdn('theme/assets/images/icons/icon-close.svg')}}" class="replace-with-svg" alt="Close"></a>
         <div class="heading">
            <span>Account login</span>
            <p>Access your courses, schedule & files.</p>
         </div>
         <div class="alert-outer" hidden>
            <div class="alert-wrapper error-alert">
               <div class="alert-inner">
                  <p id="account-error"></p>
                  {{--<a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>--}}
               </div>
            </div>
            <!-- /.alert-outer -->
         </div>
         <form autocomplete="off" class="login-form">


            <label>Email <span class="required">(*)</span></label>
            <div class="input-wrapper input-wrapper--text input-wrapper--email">
                <input type="text"  id="email-sub" autocomplete="off">

            </div>

            </br>

            <label> Password <span class="required">(*)</span></label><span data-id="password-sub" class="icon sub"><img width="20" src="{{cdn('/theme/assets/images/icons/eye-password.svg')}}" alt="">Show</span>
            <div class="input-wrapper input-wrapper--text">
                <input type="password"  id="password-sub" autocomplete="off">
            </div>


            <div class="form-group">
               <label for="remember-me"><input id="remember-me-sub" type="checkbox">Remember me</label>
               {{--<a id="forgot-pass" href="javascript:void(0)">Forgot password?</a>--}}
            </div>
            <input type="button" onclick="loginAjaxSubscription()" value="LOGIN">
         </form>
      </div>
      <!-- ./login-popup -->
      <div id="forgot-pass-input" class="login-popup" hidden>
         <a href="#" class="close-btn"><img width="26" src="{{cdn('theme/assets/images/icons/icon-close.svg')}}" class="replace-with-svg" alt="Close"></a>
         <div class="heading">
            <span>Change your Password</span>
            <p>Use your account email to change your password</p>
         </div>
         {{--
         <form method="post" action="/myaccount/reset" autocomplete="off" class="validate-form change-password-form"> --}}
         <form autocomplete="off" class="login-form">
            {!!csrf_field()!!}
            <div id="error-mail" class="alert-outer" hidden>
               <div class="alert-wrapper error-alert">
                  <div class="alert-inner">
                     <p id="message-error"></p>
                  </div>
               </div>
               <!-- /.alert-outer -->
            </div>
            <div id="success-mail" class="alert-outer" hidden>
               <div class="container">
                  <div class="alert-wrapper success-alert">
                     <div class="alert-inner">
                        <p id="message-success"></p>
                     </div>
                  </div>
               </div>
               <!-- /.alert-outer -->
            </div>
            <div class="input-wrapper input-wrapper--text input-wrapper--email">
               <div class="input-safe-wrapper">
                  <span class="icon"><img width="14" src="{{cdn('/theme/assets/images/icons/icon-email.svg')}}" alt=""></span>
                  <input type="email"  placeholder="Email" name="email" id="email-forgot" class="required">
               </div>
            </div>
            <button type="button" class="btn btn--lg btn--secondary change-password"  value="Change">Change</button>
         </form>
      </div>
      <!-- ./login-popup -->
   </div>
   <!-- ./login-popup-wrapper -->
   @endif
   @if (!empty($page['medias']))
   <section class="section-hero" style="background-image:url({{cdn(get_image($page['medias'], 'header-image'))}})">
      <div class="overlay"></div>
      <div class="container">
         <div class="hero-message pad-r-col-6">
         <h1>{{ $page['name'] }}</h1>
                    <h2>{{ $page['title'] }}</h2>
         </div>
      </div>
   </section>
   @else
   <section class="section-hero section-hero-small section-hero-blue-bg">
      <div class="container">
         <div class="hero-message">
         <h1>{{ $page['name'] }}</h1>
                    <h2>{{ $page['title'] }}</h2>
         </div>
      </div>
   </section>
   @endif
   <section class="form-section form-section-sub">
      <div class="container">
         <div class="row">
            <div class="col6 col-sm-12">
               <div class="text-area">
               {!! $page['content'] !!}
               </div>
            </div>
            <div class="col6 col-sm-12">
               <div class="form-area-wrapper">
                  <div class="form-wrapper blue-form sub-blue-form">
                     <form method="GET" action="/myaccount/subscription/{{$event}}/{{$plan}}" id="doall" novalidate>
                        <h3 class="form-h3 subscription">Get full access for one year</h3>
                        <ul class="subs-page-list">
                           <li>
						   		<img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/checkmark-sqaure.svg')}}" alt=""> <span class="subs-page-span">Access to presentations</span>
                           </li>
                           <li>
						   <img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/checkmark-sqaure.svg')}}" alt=""> <span class="subs-page-span"> Access to bonus files</span>
                           </li>
                           <li>
						   <img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/checkmark-sqaure.svg')}}" alt=""> <span class="subs-page-span"> Access to videos</span>
                           </li>
                           <li>
						   <img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/checkmark-sqaure.svg')}}" alt=""> <span class="subs-page-span"> Personal notes</span>
                           </li>
                        </ul>
                        <div class="submit-area-custom">
                           <button  type="button" class="btn btn--md btn--primary subscription-enroll">SUBSCRIBE NOW</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- /.form-section -->
   </section>
   <section class="">
      <div class="content-wrapper">
         <div class="tabs-wrapper fixed-tab-controls">
            <div class="tabs-content">
               <div class="tab-content-wrapper tab-no-pad active-tab">
                  <div class="container">
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
                                 $youtubeVideoId = explode('v=',$youtubeVideoId);
                                 $youtubeVideoId = isset($youtubeVideoId[1]) ? $youtubeVideoId[1] : $youtubeVideoId[0];
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
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <section class="">
      <div id="testimonials" class="tab-content-wrapper tab-no-pad">
         <div class="content-wrapper">
            <div class="tabs-wrapper fixed-tab-controls">
               <div class="tabs-content">
                  <div class="tab-content-wrapper tab-no-pad active-tab">
                     <div class="user-testimonial-wrapper">
                        <div class="container">
                           <div class="user-testimonial-big owl-carousel">
                              @if (!empty($testimonials))
                              @foreach ($testimonials as $key => $row)
                           @if($row['video_url'])
                                 <?php continue;?>
                           @endif
                           <div class="slide">
                              <div class="testimonial-box">
                                 <div class="author-infos">
                                    <div class="author-img">
                                       <img onerror="this.src='{{cdn('/theme/assets/images/icons/user-circle-placeholder.svg')}}'" src="{{ cdn(get_image($row['mediable'],'users')) }}" alt="{!! $row['name'] !!}">
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
														    "name": "{!!$page['title']!!}",
                                                            "description": "{!! $page['subtitle'] !!}"
														  },
														  "reviewRating": {
														    "@type": "Rating",
														    "ratingValue": "5"
														  },
														  "name": "{!!$page['title']!!}",
														  "author": {
														    "@type": "Person",
														    "name": "{!! $row['name'] !!} {!! $row['lastname'] !!}"
														  },
														  "reviewBody": "{!! $rev !!}",
														  "publisher": {
														    "@type": "Organization",
														    "name": "Knowcrunch"
														  }
														}
													</script>

                              <!-- /.slide -->
                           </div>
                           @endforeach
                              @endif
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</main>
@endsection
@section('scripts')
<script>
   document.getElementById('header').classList.add('header-transparent');
</script>
<script type="text/javascript">
   //login function function
   $(".subscription-enroll").click(function() {

   	@if(!Auth::user())
   		$('.login-popup-wrapper-subscription').addClass('active')
   @else

   $('#doall').submit();

   	@endif

   	//myaccount/subscription/1350/2

   });

   $('.close-btn').click(function(e){
   	e.preventDefault();
   	$('.login-popup-wrapper-subscription').removeClass('active')
   })

   @if(!Auth::user())
   function loginAjaxSubscription(){
       var email = $('#email-sub').val();
       var password = $('#password-sub').val();
       var remember = document.getElementById("remember-me-sub").checked;

       if (email.length > 4 && password.length > 4) {
       $.ajax({ url: routesObj.baseUrl+"studentlogin", type: "post",
               data: {email:email, password:password, remember:remember},
               success: function(data) {
                   switch (data.status) {
                       case 0:
                           if (data.message.length > 0) {

                               var p = document.getElementById('account-error').textContent = data['message'];
                             /*  var img = document.createElement('img');
                               img.setAttribute('src',"/theme/assets/images/icons/alert-icons/icon-error-alert.svg" )
                               img.setAttribute('alt',"Info Alert" )

                               $('#account-error').append(img);*/
                           //	console.log(p);
                               $('.alert-outer').show()

                           } else {


                           }
                           break;
                       case 1:

   			               location.reload();
                           /*setTimeout( function(){
                               window.location.replace(data.redirect);
                           }, 1000 );*/

                           break;

                       default:
                           //shakeModal();
                           break;
                   }



               },
               error: function(data) {
                   //shakeModal();
               }
           });

           }
           else {
             //  shakeModal();

           }


   }

   $(document).keyup(function(event){

      if($('.login-popup-wrapper-subscription').hasClass('active')){

         if(event.keyCode == 13){
           loginAjaxSubscription()
         }
      }
   })

   @endif

</script>

<script>

    $('.icon sub').click(function(){
        let input = $(`#${$(this).data('id')}`);

        if(input.attr('type') === "password"){
            input.attr('type','text')

            $(this).find('img').attr('src', "{{cdn('/theme/assets/images/icons/eye-password-active.svg')}}");


        }else{
            input.attr('type','password')
            $(this).find('img').attr('src', "{{cdn('/theme/assets/images/icons/eye-password.svg')}}");
        }

    })

</script>
@stop
