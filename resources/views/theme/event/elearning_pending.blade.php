@extends('theme.layouts.master')
@inject('frontHelp', 'Library\FrontendHelperLib')
@section('content')
<?php $cart = Cart::content(); ?>
@if(isset($content['c_fields']['dropdown_select_status']['value']))
<?php $estatus = $content['c_fields']['dropdown_select_status']['value']; ?>
@else
<?php $estatus = 0; ?>
@endif
<main id="main-area" role="main">
   <script type="application/ld+json">
      {
        "@context": "https://schema.org",
        "@type": "Course",
        "name": "{!!$content->title!!}",
        "description": "{!!$content->title!!}",
        "award": "Awarded as the 'Best Digital & Social Media Education', by Social Media World in 2016, 2017 and 2018.",
        "inLanguage": "Greek/Ellinika",
        "educationalCredentialAwarded": "EQF 5+ level",
        "author": {
        	"@type": "Person",
        	"name": "Tolis Aivalis"
        },
        "provider": {
          "@type": "Organization",
          "name": "Know Crunch",
          "sameAs": "https://knowcrunch.com/"
        }
      }
   </script>

   <?php
      
      $img = '';
      if (!empty($content['featured']) && isset($content['featured'][0]) &&isset($content['featured'][0]['media']) && !empty($content['featured'][0]['media'])){
        $img =  $frontHelp->pImg($content, 'header-image');
      }

      /*$img_path = $content['featured'][0]['media']['path'];
      $img_name = $content['featured'][0]['media']['name'].$content['featured'][0]['media']['ext'];
      $img = url('/') . '/uploads/originals/1/'.$img_name;*/

      ?>

   <div class="elearning-page-wrapper" itemscope itemtype="https://schema.org/Course">

      

      <div class="container">

  

         <div class="row">
            <div class="col8 col-sm-12">
               <div class="page-title">
                  <h1 itemprop="headline">{!!$content->title!!}</h1>
               </div>
            </div>
            
          
         </div>
         <div class="row">

            <div class="col8 col-sm-12">
          

               <div class="page-content">
               <div class="video-wrapper video-point-scroll  hidden-xs">
                     @if(isset($section_fullvideo) && $section_fullvideo->body == '')
                     <div id="courses-explain-video" class="responsive-fb-video">
                        {!! $section_fullvideo->body !!}
                     </div>
                     <div id="courses-video" class="responsive-video" hidden>
                        @if($is_event_paid!=0 && $video_access)
                        <?php $fv = 'https://player.vimeo.com/video/' . $last_video_seen . '?title=false' ?>
                        <div class="initframe" data-vimeo-url="{{$fv}}" data-vimeo-width="740" id="{vimeo}"></div>

                        @endif
                     </div>
                     @else

                     <div id="courses-explain-video" class="responsive-fb-video">
                     <?php
                           $img = '';
                           if (!empty($content['featured']) && isset($content['featured'][0]) &&isset($content['featured'][0]['media']) && !empty($content['featured'][0]['media'])){
                           $img =  $frontHelp->pImg($content, 'header-image');
                           }
                           ?>

                           <img src="{{$img}}">
                     </div>
                     

                     @endif
                     <!-- /.video-wrapper -->
                  </div>
                  <div class="content-wrapper">
                     <div class="tabs-wrapper">
                      
                        <div class="tab-controls">
                           <a href="#" class="mobile-tabs-menu">Menu</a>
                           <ul class="clearfix tab-controls-list">
                              <li><a href="#overview"  class="active">Overview</a></li>
                             

                           </ul>
                           <!-- /.tab-controls -->
                        </div>
                        <div class="tabs-content">
                           <div id="topics" class="tab-content-wrapper">
                              
                              <div class="sidebar-wrapper topics-tab">
                                 <div class="accordion-wrapper custom-scroll-area">
                                  
                              </div>
                           </div>
                        </div>
                        <div id="overview" class="tab-content-wrapper active-tab">
                      
                           <div class="social-share">
                              <ul class="clearfix">
                                 <li class="fb-icon"><a target="_blank" title="Share on facebook" href="http://www.facebook.com/sharer.php?u={{ Request::url() }}" onclick="javascript:window.open(this.href,
                                    '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=300');return false;"><i class="icon-facebook"></i></a></li>
                                 <li class="tw-icon "><a target="_blank" title="Share on Twitter" href="http://twitter.com/share?text={{ $content->title }}&amp;url={{ Request::url() }}&amp;via=KnowCrunch" onclick="javascript:window.open(this.href,
                                    '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="icon-twitter"></i></a></li>
                                 <li class="in-icon"><a target="_blank" title="Share on LinkedIn" href="https://www.linkedin.com/shareArticle?mini=true&amp;url={{ Request::url() }}&amp;title={{ $content->title }}
                                    &amp;summary={{ $content->summary }}&amp;source=KnowCrunch" onclick="javascript:window.open(this.href,
                                    '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="icon-linkedin"></i></a></li>
                              </ul>
                           </div>
                           <div class="course-overview clearfix">
                              <div class="course-tab-text" itemprop="abstract">
                              <?php
                                 $title = '' ;
                                 $body = '' ;
                                 $cont = $content->titles()->where('category','overview');
                                 
                                 if($cont->first() && ($cont->first()->title!=null || $cont->first()->title!='')){
                                       $title = $cont->first()->title;
                                       $body = $cont->first()->body;
                                    }
                                 
                                 ?>
                                 <h2> {{$title}}</h2>
                                 {!! $content->body !!}
                                 <!-- /.course-overview-text -->
                              </div>
                              <div class="course-tab-sidebar">
                                 <div class="course-details">
                                    <ul>
                                       <?php if (isset($content['c_fields']['simple_text'][2]) && $content['c_fields']['simple_text'][2]['value'] != '') : ?>
                                       <li>
                                          <img class="info-icon" src="{{cdn('/theme/assets/images/icons/Times.svg')}}" width="21" />
                                          <div class="info-text">
                                             <p>{{ $content['c_fields']['simple_text'][2]['value'] }}<br/>{{ $content['c_fields']['simple_text'][3]['value'] }}</p>
                                          </div>
                                       </li>
                                       <?php endif ?>
                                       
                                          <?php if (isset($content['c_fields']['simple_text'][8]) && $content['c_fields']['simple_text'][8]['value'] != '') : ?>
                                          <li>
                                             <img class="info-icon" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}"  width="21" />
                                             <div class="info-text">
                                                <p>{{ $content['c_fields']['simple_text'][8]['value'] }}<br/>{{ $content['c_fields']['simple_text'][9]['value'] }}</p>
                                             </div>
                                          </li>
                                          <?php endif ?>
                                       
                                          <?php if (isset($content['c_fields']['simple_text'][6]) && $content['c_fields']['simple_text'][6]['value'] != '') : ?>
                                       <li>
                                          <img class="info-icon" src="{{cdn('/theme/assets/images/icons/Language.svg')}}"  width="21" />
                                          <div class="info-text">
                                             <p>{{ $content['c_fields']['simple_text'][6]['value'] }}<br/>{{ $content['c_fields']['simple_text'][7]['value'] }}</p>
                                          </div>
                                       </li>
                                       <?php endif ?>

                                       <?php if (isset($content['c_fields']['simple_text'][4]) && $content['c_fields']['simple_text'][4]['value'] != '') : ?>
                                       <li>
                                          <img class="info-icon" src="{{cdn('/theme/assets/images/icons/Level.svg')}}" width="21" />
                                          <div class="info-text">
                                             <p>{{ $content['c_fields']['simple_text'][4]['value'] }}<br/>{{ $content['c_fields']['simple_text'][5]['value'] }}</p>
                                          </div>
                                       </li>
                                       <?php endif ?>

                                          <?php if (isset($content['c_fields']['simple_text'][0]) && $content['c_fields']['simple_text'][0]['value'] != '') : ?>
                                          <li>
                                             <img class="info-icon" src="{{cdn('/theme/assets/images/icons/Duration_Hours.svg')}}"  width="21" />
                                             <div class="info-text">
                                                <p>{{ $content['c_fields']['simple_text'][0]['value'] }}<br/>{{ $content['c_fields']['simple_text'][1]['value'] }}</p>
                                             </div>
                                          </li>
                                          <?php endif ?>
                                      
                                      
                                       
                                          <?php if (isset($content['c_fields']['simple_text'][10]) && $content['c_fields']['simple_text'][10]['value'] != '') : ?>
                                          <li>
                                             <img class="info-icon" src="{{cdn('/theme/assets/images/icons/Days-Week.svg')}}"  width="21" />
                                             <div class="info-text">
                                                <p>{{ $content['c_fields']['simple_text'][10]['value'] }}<br/>{{ $content['c_fields']['simple_text'][11]['value'] }}</p>
                                             </div>
                                          </li>
                                          <?php endif ?>

                                          <?php if (isset($content['c_fields']['simple_text'][13]) && $content['c_fields']['simple_text'][13]['value'] != '') : ?>
                                          <li>
                                             <img class="info-icon" src="{{cdn('/theme/assets/images/icons/messages-warning-information.svg')}}"  width="21" />
                                             <div class="info-text">
                                                <p>Students that took this course<br/>{{ $sumStudent }} other fellow students also enrolled in this course.</p>
                                             </div>
                                          </li>
                                          <?php endif ?>

                                          <?php if (isset($content['c_fields']['simple_text'][15]) && isset($content['c_fields']['simple_text'][16]) && $content['c_fields']['simple_text'][16]['value']!='' && isset($sumStudent) && $sumStudent > 0) : ?>
                                          <li>
                                             <img class="info-icon" src="{{cdn('/theme/assets/images/icons/Group_User.1.svg')}}"  width="21" />
                                             <div class="info-text">
                                             <p>@if($content['c_fields']['simple_text'][15]['value'] != ''){{ $content['c_fields']['simple_text'][15]['value'] }}<br/>@endif @if($content['c_fields']['simple_text'][16]['value'] != ''){{ $sumStudent }} {{ $content['c_fields']['simple_text'][16]['value'] }}@endif</p>
                                             </div>
                                          </li>
                                          <?php endif ?>

                                       @if (!empty($section_syllabus_manager['featured']) && isset($section_syllabus_manager['featured'][0]) && isset($section_syllabus_manager['featured'][0]['media']) && !empty($section_syllabus_manager['featured'][0]['media']))
                                       <li>
                                         <span class="author-icon"> <a href="" id="syllabus-link"><img src="{{ cdn($frontHelp->pImg($section_syllabus_manager, 'instructors-small')) }}" alt="{{ $frontHelp->pField($section_syllabus_manager, 'title') }}"></a></span>
                                          <div class="info-text">
                                             <p> {{ $section_syllabus_manager->title }} <br/><span itemprop="author" class="link-a"> 	{!! $section_syllabus_manager->body !!}</span></p>
                                          </div>
                                       </li>
                                       @endif
                                    </ul>
                                    <!-- /.course-details -->
                                 </div>
                                 <!-- /.course-overview-sidebar -->
                              </div>
                              <!-- /.course-overview.clearfix -->
                           </div>
                           <!-- /.tab-content-wrapper -->
                        </div>
                      
                     
                     
                     
                  

                        <!-- /.tabs-content -->
                        <!-- /.tabs-wrapper -->
                     </div>
                     <!-- /.content-wrapper -->
                  </div>
                  <!-- /.page-content -->
               </div>
               <!-- /.col8 -->
            </div>
         </div>
     
         <!-- /.sidebar-wrapper -->
      </div>
      <!-- /.col4 -->
   </div>
   <!-- /.row -->
   </div>
   <!-- /.container -->
   </div>
   <!-- /.elearning-page-wrappera -->
                                               
</main>
@endsection
@section('scripts')


<script>
   /// set link to syllabus manager image
   let syllabusLink = $('.info-text').find('a').attr('href');
   document.getElementById('syllabus-link').setAttribute('href',syllabusLink);

</script>


@if($estatus == 0 || $estatus == 2)


<script>

fbq('track', 'ViewContent', {
  content_name: '<?php echo $content->title ?>',
  content_category: '<?php echo $categoryScript ?>',
  content_ids: ['{{$content->id}}'],
  content_type: 'product',
  value: {{$priceForScript}},
  currency: 'EUR'
 });
</script>

<script>

  gtag('event', 'page_view', {
    'send_to': 'AW-859787100',
    'value': '{{$priceForScript}}',
    'items': [{
      'id': '{{$content->id}}',
      'google_business_vertical': 'custom'
    }]
  });

</script>

@endif


@if($estatus == 0 || $estatus == 2)


<script>

fbq('track', 'ViewContent', {
  content_name: '<?php echo $content->title ?>',
  content_category: '<?php echo $categoryScript ?>',
  content_ids: ['{{$content->id}}'],
  content_type: 'product',
  value: {{$priceForScript}},
  currency: 'EUR'
 });
</script>

<script>

  gtag('event', 'page_view', {
    'send_to': 'AW-859787100',
    'value': '{{$priceForScript}}',
    'items': [{
      'id': '{{$content->id}}',
      'google_business_vertical': 'custom'
    }]
  });

</script>


@endif

@stop


@section('fbchat')
<div id="fb-root"></div>
@if(Agent::isDesktop())
<script>
window.fbAsyncInit = function() {
  FB.init({
    xfbml            : true,
    version          : 'v5.0'
  });
};
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- Your customer chat code -->
<div class="fb-customerchat"
  attribution=install_email
  page_id="486868751386439">
</div>
@endif
@stop