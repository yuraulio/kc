@extends('theme.layouts.master')
@section('metas')
{!! $event->metable->getMetas() !!}
@endsection
@section('content')
<?php $estatus = $event->status ?>
<main id="main-area" role="main">
   <script type="application/ld+json">
      {
        "@context": "https://schema.org",
        "@type": "Course",
        "name": "{!!$event->title!!}",
        "description": "{{ $event->subtitle }}",
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
   <div class="elearning-page-wrapper" itemscope itemtype="https://schema.org/Course">
      <div class="container">
         <div class="row">
            <div class="col8 col-sm-12">
               <div class="page-title">
                  <h1 itemprop="headline">{!!$event->title!!}</h1>
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
                     
                     @else
                     <div id="courses-explain-video" class="responsive-fb-video">
                        <img src="{{cdn(get_image($event->mediable,'header-image'))}}">
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
                                    <li class="tw-icon "><a target="_blank" title="Share on Twitter" href="http://twitter.com/share?text={{ $event->title }}&amp;url={{ Request::url() }}&amp;via=KnowCrunch" onclick="javascript:window.open(this.href,
                                       '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="icon-twitter"></i></a></li>
                                    <li class="in-icon"><a target="_blank" title="Share on LinkedIn" href="https://www.linkedin.com/shareArticle?mini=true&amp;url={{ Request::url() }}&amp;title={{ $event->title }}
                                       &amp;summary={{ $event->summary }}&amp;source=KnowCrunch" onclick="javascript:window.open(this.href,
                                       '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="icon-linkedin"></i></a></li>
                                 </ul>
                              </div>
                              <div class="course-overview clearfix">
                                 <div class="course-tab-text" itemprop="abstract">
                            
              
                                    {!! $event->body !!}
                                    <!-- /.course-overview-text -->
                                 </div>
                                 <div class="course-tab-sidebar">
                                    <div class="course-details">
                                       <ul>
                                       @foreach($summary as $sum)
                                 @if($sum['title'] && $sum['section'] != 'students')
                                 <li>
                                 @if($sum['mediable'])<img class="info-icon" class="replace-with-svg" src="{{cdn(get_image($sum['mediable']))}}" width="30" />@endif
                                    <div class="info-text">

                                       <p>{{  $sum['title'] }}</br>
                                          {!!  $sum['description'] !!}
                                       </p>
                                    </div>
                                 </li>

                                 @elseif($sum['title'] && $sum['section'] == 'students')

                                 <li>
                                 @if($sum['mediable'])<img class="info-icon" class="replace-with-svg" src="{{cdn(get_image($sum['mediable']))}}" width="30" />@endif
                                    <div class="info-text">

                                          @if($sumStudents<=0)
                                             <p>{{  $sum['title'] }}</br></p>
                                          @else
                                             <p>{{$sumStudents}} {{preg_replace('/[0-9]+/', '', $sum['title'])}}</br></p>
                                          @endif
                                       
                                    </div>
                                 </li>

                                 @endif

                              @endforeach
                                          @if(count($syllabus) > 0)
                                          <?php
                                             $alt='';
                                             $img = get_image($syllabus[0]['mediable'],'instructors-small'); //$event->mediable->original_name;
                                             //dd($syllabus);
                                             ?>
                                          <li>
                                             <span class="author-icon"> <a href="" id="syllabus-link"><img src="{{ cdn($img) }}" alt="{{$alt}}"></a></span>
                                             <div class="info-text">
                                                <p> Syllabus Manager <br/><span itemprop="author" class="link-a"><a href="{{$syllabus[0]['slugable']['slug']}}">{{ $syllabus[0]['title'] }} {!! $syllabus[0]['subtitle'] !!}</a></span></p>
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
@if($estatus == 0 || $estatus == 2)
{{--<script>
   fbq('track', 'ViewContent', {
     content_name: '<?php echo $event->title ?>',
     content_category: '<?php echo $categoryScript ?>',
     content_ids: ['{{$event->id}}'],
     content_type: 'product',
     value: {{$priceForScript}},
     currency: 'EUR'
    });
</script>--}}
{{--<script>
   gtag('event', 'page_view', {
     'send_to': 'AW-859787100',
     'value': '{{$priceForScript}}',
     'items': [{
       'id': '{{$event->id}}',
       'google_business_vertical': 'custom'
     }]
   });
   
</script>--}}
@endif
<script>
   /// set link to syllabus manager image
   let syllabusLink = $('.info-text').find('a').attr('href');
   document.getElementById('syllabus-link').setAttribute('href',syllabusLink);
   
</script>
<script src="{{ cdn('theme/assets/js/cart.js') }}"></script>
@stop
@section('fbchat')
<div id="fb-root"></div>
{{--@if(Agent::isDesktop())
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
   }(document, 'script', 'facebook-jssdk'));
</script>
<!-- Your customer chat code -->
<div class="fb-customerchat"
   attribution=install_email
   page_id="486868751386439"></div>
@endif--}}
@stop