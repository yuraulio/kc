@extends('theme.layouts.master')
@inject('frontHelp', 'Library\FrontendHelperLib')

@section('header')
   @if($content->id == 1350)
<!-- Hotjar Tracking Code for https://knowcrunch.com/ -->
   <script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:525498,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>
   @endif
@stop

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
   <div class="elearning-page-wrapper" itemscope itemtype="https://schema.org/Course">

      

      <div class="container">

      <div id="favDialog1" hidden>
               <div class="alert-outer" >
                     <div class="container">
                        <div class="alert-wrapper success-alert">
                           <div class="alert-inner">
                              <p id ="message"></p>
                              
                           </div>
                        </div>
                     </div>
                  <!-- /.alert-outer -->
               </div>
            </div>

            <div id="examDialog" hidden>
            <div class="alert-wrapper success-alert">
               <div class="alert-inner">
                  <p>Your examination has now been activated.</p>
                  <p>Access from: My Account → My courses → E-learning Masterclass → Exams.</p>
                  <p>Good luck!</p>

                  <a id="close-exam-dialog" href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
               </div>
               <button class="btn btn-del btn--sm go-to-account">Go to my account </button>
               
               <!-- /.alert-outer -->
            </div>
         </div>

         <div class="row">
            <div class="col8 col-sm-12">
               <div class="page-title">
                  <h1 itemprop="headline">{!!$content->title!!}</h1>
               </div>
            </div>
            @if($is_event_paid==0)
            <div class="col4 col-sm-12">
               <div class="actions-wrapper text-right">
                  <a href="#seats" class="btn btn--lg btn--primary hidden-sm go-to-href">ENROLL NOW</a>
               </div>
            </div>
            @endif
          
         </div>
         <div class="row">

            <div class="col8 col-sm-12">
          

               <div class="page-content">
                  <div class="video-wrapper video-point-scroll  hidden-xs">
                     @if(isset($section_fullvideo) && $section_fullvideo->body != '')
                     <div id="courses-explain-video" class="responsive-fb-video">
                        {!! $section_fullvideo->body !!}
                     </div>
                     <div id="courses-video" class="responsive-video" hidden>
                        @if($is_event_paid!=0 && $video_access)
                                                
                        <?php $fv = 'https://player.vimeo.com/video/' . $last_video_seen . '?title=false' ?>
                        <div focus class="initframe" data-vimeo-url="{{$fv}}" data-vimeo-width="740" id="{vimeo}"></div>
                        
                        @endif
                     </div>
                     @endif
                     <!-- /.video-wrapper -->
                  </div>
                  <div class="content-wrapper">
                     <div class="tabs-wrapper">
                        @if($is_event_paid==0)
                           <a href="#seats" class="btn btn--lg btn--primary btn-tabs-ctr-toggle hidden-lg hidden-md visible-sm go-to-href">ENROLL NOW</a>
                        @endif
                        <div class="tab-controls">
                           <a href="#" class="mobile-tabs-menu">Menu</a>
                           <ul class="clearfix tab-controls-list">
                              <li><a href="#overview"  class="active">Overview</a></li>
                              <li><a href="#topics" onclick="dok()" class="hidden-lg visible-sm">Topics</a></li>
                              @if(!Sentinel::check() || $is_event_paid==0) <li><a href="#benefits">Benefits</a></li> @endif
                              <li><a href="#instructors">Instructors</a></li>
                              @if(isset($section_testimonials) && (!Sentinel::check() || $is_event_paid==0)) <li><a href="#testimonials">Testimonials</a></li>@endif
                              @if(isset($section_qnas))<li><a href="#faq">FAQ</a></li>@endif

                              @if(Sentinel::check() && $is_event_paid==1)
                                 @if(!$instructor_topics)
                                    <li><a href="#files"> Files </a></li>
                                 @endif
                                 @if($exam)
                                    <li><a href="#exam">Exam </a></li>
                                 @endif
                              @endif

                           </ul>
                           <!-- /.tab-controls -->
                        </div>
                        <div class="tabs-content">
                           <div id="topics" class="tab-content-wrapper">
                              <div id="courses_m" class="video-wrapper video-point-scroll hidden-lg visible-xs">
                                 <div id="courses-tab" class="responsive-video" >
                                    @if($is_event_paid!=0 && $video_access)
                                    <?php $fv = 'https://player.vimeo.com/video/' . $last_video_seen . '?title=false' ?>
                                    <div focus class="initframe" data-vimeo-url="{{$fv}}" data-vimeo-width="auto" id="{vimeom}"></div>
                                    @endif
                                 </div>
                                 <!-- /.video-wrapper -->
                              </div>
                              <div class="sidebar-wrapper topics-tab">
                                 <div class="accordion-wrapper custom-scroll-area">
                                    @if(isset($topics))
                                    <?php $tab = 1; ?>
                                    <?php $firstLesson = 1; ?>
                                    @foreach($topicss as $key => $value)
                                    <div class="accordion-item @if ($tab === 1 && $last_video_seen ===-1) active-tab @endif" >
                                       <h2 class="accordion-title scroll-to-top">{!! $key !!}</h2>
                                       <div class="accordion-content" @if($tab === 1 && $last_video_seen ===-1) style="display: block;" @endif">
                                       @foreach($value as $lke => $lvalu)
                                 @foreach($lvalu as $lkey => $lvalue)

                                 <?php 
                                                         
                                                         $tabb = $eventTitle.'_'.$lvalue['tab'];
                                                         $tabb = str_replace(' ','_',$tabb);
                                                         $tabb = str_replace('-','',$tabb);
                                                         $tabb = str_replace('&','',$tabb);
                                                         $tabb = str_replace('_','',$tabb);

                                                         //$tabb = $lke;
                                                         
                                                         
                                                      ?>

                                       <div id="{{$tabb}}m" class="topic-wrapper  @if($lvalue['seen']) watched @endif">
                                          @if($is_event_paid==0 || !$video_access)
                                          <a href="#seats" class="go-to-href"><img src="{{cdn('/theme/assets/images/icons/Lock.svg')}}" width="12" class="icon" alt=""/></a>
                                          <div class="topic-title-meta">
                                             <h3><a class="go-to-href" href="#seats">{!! $lkey !!}</a></h3>
                                             <div class="topic-meta">
                                                <span class="duration"><img src="{{cdn('/theme/assets/images/icons/Times.svg')}}" width="12" alt="" />{{$lvalue['duration']}}</span>
                                             </div>
                                          </div>
                                          @else
                                          <?php
                                             $path = $lvalue['vimeo'];
                                             if($firstLesson==1){
                                                $firstLesson = 0;          

                                             }
                                             ?>
                                          <a class="play-link" onclick="playvideo('{{$path}}','{{$tabb}}m', '{video{{$lvalue['id']}}}', '{{$lvalue['id']}}')" href="javascript:void(0)"> 
                                          @if($lvalue['seen'])
                                             <img src="{{cdn('/theme/assets/images/icons/check_lesson.svg')}}" width="12" class="icon" alt=""/></a>
                                          @else
                                          <img id="play-image-event{{$lvalue['id']}}m" src="{{cdn('/theme/assets/images/icons/Play.svg')}}" width="12" class="icon" alt=""/></a>

                                          @endif
                                          <div class="topic-title-meta">
                                             <h3><a class="play-link" onclick="playvideo('{{$path}}','{{$tabb}}m','{video{{$lvalue['id']}}m}', '{{$lvalue['id']}}')" href="javascript:void(0)">{!! $lkey !!}</a></h3>
                                             <div class="topic-meta">
                                                <span class="duration"><img src="{{cdn('/theme/assets/images/icons/Times.svg')}}" width="12" alt="" />{{$lvalue['duration']}}</span>
                                             </div>
                                          </div>
                                          @endif
                                          @if($lvalue['status'])
                                          <div class="author-img">
                                             <a href="{{ $lvalue['slug']}}">
                                             <span class="custom-tooltip">{{ $lvalue['inst'] }}</span>
                                             <img alt="{{ $lvalue['inst'] }}" src="{{ cdn($lvalue['inst_photo']) }}"/>
                                             </a>
                                          </div>
                                          @else
                                          <div class="author-img">
                                             <a class='non-pointer' href="javascript:void(0)">
                                             <span class="custom-tooltip">{{ $lvalue['inst'] }}</span>
                                             <img alt="{{ $lvalue['inst'] }}" src="{{ cdn($lvalue['inst_photo']) }}"/>
                                             </a>
                                          </div>
                                          @endif
                                       </div>
                                       @endforeach
                                       @endforeach
                                    </div>
                                 </div>
                                 <?php $tab = $tab +1?>
                                 @endforeach
                                 @endif
                              </div>
                           </div>
                        </div>
                        <div id="overview" class="tab-content-wrapper active-tab">
                           <div class="video-wrapper video-point-scroll hidden-lg visible-xs">
                              @if(isset($section_fullvideo) && $section_fullvideo->body != '')
                              <div class="responsive-fb-video">
                                 {!! $section_fullvideo->body !!}
                              </div>
                              @endif
                              <!-- /.video-wrapper -->
                           </div>
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
                                                <p>{{ $content['c_fields']['simple_text'][13]['value'] }}<br/>{{ $content['c_fields']['simple_text'][14]['value'] }}</p>
                                             </div>
                                          </li>
                                          <?php endif ?>

                                          <?php if (isset($content['c_fields']['simple_text'][15]) && isset($content['c_fields']['simple_text'][16]) && $content['c_fields']['simple_text'][16]['value']!='' && isset($sumStudent) && $sumStudent > 0) : ?>
                                          <li>
                                             <img class="info-icon" src="{{cdn('/theme/assets/images/icons/Group_User.1.svg')}}"  width="21" />
                                             <div class="info-text">
                                             <p>@if($content['c_fields']['simple_text'][15]['value'] != ''){{ $content['c_fields']['simple_text'][15]['value'] }}<br/>@endif{{ $sumStudent }} {{ $content['c_fields']['simple_text'][16]['value'] }}</p>
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
                        @if(!Sentinel::check() || $is_event_paid==0)
                        <div id="benefits" class="tab-content-wrapper">
                           <div class="course-benefits-text">
                              <?php
                                 $title = '' ;
                                 $body = '' ;
                                 $cont = $content->titles()->where('category','benefits');
                                 
                                 if($cont->first() && ($cont->first()->title!=null || $cont->first()->title!='')){
                                       $title = $cont->first()->title;
                                       $body = $cont->first()->body;
                                    }
                                 
                                 ?>
                              <h2>{!!$title!!}</h2>
                              <h3>{!!$body!!}</h3>
                              <div class="benefits-list">
                                 <div class="row-flex row-flex-17">
                                    <?php $category = 'freepresentations';  $benefit = $content->benefit()->where('category',$category)->first();
                                       if (isset($benefit) && $benefit->title != '') : ?>
                                    <div class="col-4 col-sm-6 col-xs-12">
                                       <div class="benefit-box">
                                          <div class="box-icon">
                                             <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" class="replace-with-svg" width="40" alt="{{$benefit->title}}">
                                          </div>
                                          <h3>{{ $benefit->title }}</h3>
                                          {!! $benefit->description !!}
                                          <!-- /.benefit-box -->
                                       </div>
                                    </div>
                                    <?php endif ?>
                                    <?php $category = 'e-learning';  $benefit = $content->benefit()->where('category',$category)->first();
                                       if (isset($benefit) && $benefit->title != '') : ?>
                                    <div class="col-4 col-sm-6 col-xs-12">
                                       <div class="benefit-box">
                                          <div class="box-icon">
                                             <img src="{{cdn('/theme/assets/images/icons/E-Learning.svg')}}" class="replace-with-svg" width="40"  alt="{{$benefit->title}}">
                                          </div>
                                          <h3>{{ $benefit->title }}</h3>
                                          {!! $benefit->description !!}
                                          <!-- /.benefit-box -->
                                       </div>
                                    </div>
                                    <?php endif ?>
                                    <?php $category = 'support group';  $benefit = $content->benefit()->where('category',$category)->first();
                                       if (isset($benefit) && $benefit->title != '') : ?>
                                    <div class="col-4 col-sm-6 col-xs-12">
                                       <div class="benefit-box">
                                          <div class="box-icon">
                                             <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Customer_Access.svg')}}" width="40"  alt="{{$benefit->title}}">
                                          </div>
                                          <h3>{{ $benefit->title }}</h3>
                                          {!! $benefit->description !!}
                                          <!-- /.benefit-box -->
                                       </div>
                                    </div>
                                    <?php endif ?>
                                    <?php $category = 'jobs access';  $benefit = $content->benefit()->where('category',$category)->first();
                                       if (isset($benefit) && $benefit->title != '') : ?>
                                    <div class="col-4 col-sm-6 col-xs-12">
                                       <div class="benefit-box">
                                          <div class="box-icon">
                                             <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Jobs_access.svg')}}" width="40" alt="{{$benefit->title}}">
                                          </div>
                                          <h3>{{ $benefit->title }}</h3>
                                          {!! $benefit->description !!}
                                          <!-- /.benefit-box -->
                                       </div>
                                    </div>
                                    <?php endif ?>
                                    <?php $category = 'projects info';  $benefit = $content->benefit()->where('category',$category)->first();
                                       if (isset($benefit) && $benefit->title != '') : ?>
                                    <div class="col-4 col-sm-6 col-xs-12">
                                       <div class="benefit-box">
                                          <div class="box-icon">
                                             <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Events_Access.svg')}}" width="40"  alt="{{$benefit->title}}">
                                          </div>
                                          <h3>{{ $benefit->title }}</h3>
                                          {!! $benefit->description !!}
                                          <!-- /.benefit-box -->
                                       </div>
                                    </div>
                                    <?php endif ?>
                                    <?php $category = 'events access';  $benefit = $content->benefit()->where('category',$category)->first();
                                       if (isset($benefit) && $benefit->title != '') : ?>
                                    <div class="col-4 col-sm-6 col-xs-12">
                                       <div class="benefit-box">
                                          <div class="box-icon">
                                             <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Recap-Events.svg')}}" width="40"  alt="{{$benefit->title}}">
                                          </div>
                                          <h3>{{ $benefit->title }}</h3>
                                          {!! $benefit->description !!}
                                          <!-- /.benefit-box -->
                                       </div>
                                    </div>
                                    <?php endif ?>
                                    <?php $category = 'free recaps';  $benefit = $content->benefit()->where('category',$category)->first();
                                       if (isset($benefit) && $benefit->title != '') : ?>
                                    <div class="col-4 col-sm-6 col-xs-12">
                                       <div class="benefit-box">
                                          <div class="box-icon">
                                             <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Support-Group.svg')}}" width="40"  alt="{{$benefit->title}}">
                                          </div>
                                          <h3>{{ $benefit->title }}</h3>
                                          {!! $benefit->description !!}
                                          <!-- /.benefit-box -->
                                       </div>
                                    </div>
                                    <?php endif ?>
                                    
                                 </div>
                              </div>
                           </div>
                           <!-- /.tab-content-wrapper -->
                        </div>
                        @endif
                        <div id="instructors" class="tab-content-wrapper tab-gray">
                              <?php
                                 //$title = $section_instructors->title ;
                                 //$body = $section_instructors->body ;
                                 $cont = $content->titles()->where('category','instructors');
                                 
                                 if($cont->first() && ($cont->first()->title!=null || $cont->first()->title!='')){
                                       $title = $cont->first()->title;
                                       $body = $cont->first()->body;
                                    }
                                 
                                 ?>
                                 <h2 class="hidden-lg visible-xs"> {!!$title!!} </h2>
                           <div class="course-full-text">
                             
                              <h2 class="hidden-xs">{!!$title!!}</h2>
                              <h3>{!!$body!!}</h3>
                              <div class="row row-flex">
                                 @foreach($instructors as $lkey => $lvalue)
                                 <div class="col-4 col-sm-4 col-xs-12">
                                    <div class="instructor-box">
                                       <div class="instructor-inner">
                                          <div class="profile-img">
                                             @if(isset($lvalue['featured'][0]['media']) )

                                                @if($lvalue->status)
                                                <a href="{{ $lvalue->slug }}"> 
                                                <img alt="{{ $frontHelp->pField($lvalue, 'title') }} {{ $lvalue['subtitle'] }}" 
                                                   title="{{ $frontHelp->pField($lvalue, 'title') }} {{ $lvalue['subtitle'] }}" 
                                                   src="{{ cdn($frontHelp->pImg($lvalue, 'instructors-testimonials')) }}" />
                                                </a>
                                                @else
                                                <img alt="{{ $frontHelp->pField($lvalue, 'title') }} {{ $lvalue['subtitle'] }}" 
                                                   title="{{ $frontHelp->pField($lvalue, 'title') }} {{ $lvalue['subtitle'] }}" 
                                                   src="{{ cdn($frontHelp->pImg($lvalue, 'instructors-testimonials')) }}" />
                                                @endif

                                             @else
                                             <img alt="{{ $frontHelp->pField($lvalue, 'title') }} {{ $lvalue['subtitle'] }}" 
                                                title="{{ $frontHelp->pField($lvalue, 'title') }} {{ $lvalue['subtitle'] }}" 
                                                src="{{cdn ('assets/img/no-featured-60.jpg') }}"/>
                                             @endif
                                          </div>

                                          @if($lvalue->status)
                                          <h3><a href="{{ $lvalue->slug }}">{{ $lvalue['title'] }} {{ $lvalue['subtitle'] }}</a></h3>
                                          @else
                                          <h3>{{ $lvalue['title'] }} {{ $lvalue['subtitle'] }}</h3>
                                          @endif
                                          <p>{{ $lvalue['header'] }}, 
                                             @if(isset($lvalue['ext_url']) && $lvalue['ext_url'] != '')
                                             <a target="_blank" title="{{ $lvalue['c_fields']['simple_text'][1]['value'] }}" href="{{ $lvalue['ext_url'] }}"> 
                                             {{ $lvalue['c_fields']['simple_text'][1]['value'] }}</a>.
                                          </p>
                                          @else
                                          {{ $lvalue['c_fields']['simple_text'][1]['value'] }}
                                          @endif
                                          <ul class="social-wrapper">
                                          @if(isset($lvalue['c_fields']['simple_text'][2]) && $lvalue['c_fields']['simple_text'][2]['value'] != '')
                                             <li><a target="_blank" href="{{ $lvalue['c_fields']['simple_text'][2]['value'] }}"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Facebook.svg')}}" width="16" alt="Visit"></a></li>
                                             @endif
                                             @if(isset($lvalue['c_fields']['simple_text'][4]) && $lvalue['c_fields']['simple_text'][4]['value'] != '')
                                             <li><a target="_blank" href="{{ $lvalue['c_fields']['simple_text'][4]['value'] }}"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Instagram.svg')}}" width="16" alt="Visit"></a></li>
                                             @endif
                                             @if(isset($lvalue['c_fields']['simple_text'][5]) && $lvalue['c_fields']['simple_text'][5]['value'] != '')
                                             <li><a target="_blank" href="$lvalue['c_fields']['simple_text'][5]['value']"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Linkedin.svg')}}" width="16" alt="Visit"></a></li>
                                             @endif
                                             @if(isset($lvalue['c_fields']['simple_text'][3]) && $lvalue['c_fields']['simple_text'][3]['value'] != '')
                                             <li><a target="_blank" href="{{ $lvalue['c_fields']['simple_text'][3]['value'] }}"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Twitter.svg')}}" width="16" alt="Visit"></a></li>
                                             @endif
                                             @if(isset($lvalue['c_fields']['simple_text'][6]) && $lvalue['c_fields']['simple_text'][6]['value'] != '')
                                             <li><a target="_blank" href="{{ $lvalue['c_fields']['simple_text'][6]['value'] }}"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Youtube.svg')}}" width="16" alt="Visit"></a></li>
                                             @endif
                                          </ul>
                                          <!-- /.instructor-inner -->
                                       </div>
                                       <!-- /.instructor-box -->
                                    </div>
                                    <!-- /.col-4.col-sm-12 -->
                                 </div>
                                 @endforeach
                                 <!-- /.row.row-flex -->
                              </div>
                              
                              <!-- /.course-full-text -->
                           </div>
                         
                           <!-- /.tab-content-wrapper -->
                        </div>
                        @if(isset($section_testimonials) && (!Sentinel::check() || $is_event_paid==0))
                        <div id="testimonials" class="tab-content-wrapper tab-no-pad">
                           @if(isset($section_testimonials))
                           <div class="course-full-text full-w-pad">
                              <?php
                                 $title = $section_testimonials->title ;
                                 $body = $section_testimonials->body ;
                                 $cont = $content->titles()->where('category','testimonials');
                                 
                                 if($cont->first() && ($cont->first()->title!=null || $cont->first()->title!='')){
                                       $title = $cont->first()->title;
                                       $body = $cont->first()->body;
                                    }
                                 
                                 ?>
                              <h2>{!!$title!!}</h2>
                              <h3>{!!$body!!}</h3>
                              @if(isset($section_videos))
                              <div class="testimonial-carousel-wrapper">
                                 <div class="video-carousel owl-carousel">
                                    <?php $lines = preg_split("/(\r\n|\n|\r)/", $section_videos['c_fields']['simple_textarea']['value']); ?>
                                    @foreach($lines as $key => $video)
                                    <?php
                                       $urlArr = explode("/",$video);
                                       $urlArrNum = count($urlArr);
                                       
                                       // YouTube video ID
                                       $youtubeVideoId = $urlArr[$urlArrNum - 1];
                                       
                                       // Generate youtube thumbnail url
                                       $thumbURL = 'https://img.youtube.com/vi/'.$youtubeVideoId.'/mqdefault.jpg';
                                       ?>
                                    <div class="slide">
                                       <a data-fancybox href="{{ $video }}"><img src="{{ $thumbURL }}" alt="video"/></a>
                                    </div>
                                    @endforeach
                                 </div>
                                 <!-- /.testimonial-carousel-wrapper -->
                              </div>
                              @endif
                              <!-- /.course-full-text -->
                           </div>
                           <div class="user-testimonial-wrapper">
                              <div class="user-testimonial owl-carousel">
                                 @if (!empty($testimonials))
                                 @foreach ($testimonials as $key => $row)
                                 <div class="slide">
                                    <div class="testimonial-box">
                                       <div class="author-infos">
                                          <div class="author-img">
                                             <img src="{{ $frontHelp->pImg($row, 'users') }}" alt="{!! $row->title !!}" title="{!! $row->title !!}" />
                                          </div>
                                          <span class="author-name">
                                          {{ $frontHelp->pField($row, 'title') }}</span>
                                          <span class="author-job">{{ $frontHelp->pField($row, 'header') }}</span>
                                       </div>
                                       <div class="testimonial-text">

                                          <?php $rev = $frontHelp->pField($row, 'excerpt');
                                          
                                             $rev = str_replace('"','',$rev);
                                          ?>

                                          <p>{!! $frontHelp->pField($row, 'excerpt') !!}</p>
                                       </div>
                                    </div>
                                    <script type="application/ld+json">
																	{
																	  "@context": "https://schema.org/",
																	  "@type": "UserReview",
																	  "itemReviewed": {
																	    "@type": "Course",
                                                                        "provider": "Know Crunch",
																	    "image": "{{ $frontHelp->pImg($row, 'users') }}",
																	    "name": "{!!$content->title!!}",
                                                                        "description": "{!!$content->title!!}"
																	  },
																	  "reviewRating": {
																	    "@type": "Rating",
																	    "ratingValue": "5"
																	  },
																	  "name": "{!!$content->title!!}",
																	  "author": {
																	    "@type": "Person",
																	    "name": "{{ $frontHelp->pField($row, 'title') }}"
                                                     },
                                                     
																	  "reviewBody": "{!! $rev !!}",
																	  "publisher": {
																	    "@type": "Organization",
																	    "name": "Know Crunch"
																	  }
																	}
																</script>
                                    <!-- /.slide -->
                                 </div>
                                 @endforeach
                                 @endif
                              </div>
                           </div>
                           @endif
                           <!-- /.tab-content-wrapper -->
                        </div>
                        @endif
                        <div id="faq" class="tab-content-wrapper">
                           @if(isset($section_qnas))
                           <?php
                              $title = $section_qnas->title ;
                              $body = $section_qnas->body ;
                              $cont = $content->titles()->where('category','questions');
                              
                              if($cont->first() && ($cont->first()->title!=null || $cont->first()->title!='')){
                                    $title = $cont->first()->title;
                                    $body = $cont->first()->body;
                                 }
                              
                              ?>
                           <div class="course-full-text">
                              <h2>{!! $title !!}</h2>
                              <h3> {!!$body!!} </h3>
                              @if (!empty($qnas_categories))
                              <?php $f=[] ?>
                              @foreach ($qnas_categories as $key => $row)
                              @if(in_array($row->id, $categoryQuestions))
                              <h3>{!! $row->name !!}</h3>
                              @if (!empty($qnas))
                              <div class="tab-faq-wrapper multiple-accordions">
                                 <div class="accordion-wrapper">
                                    @foreach ($qnas as $qkey => $qna)
                                    @if($row->id == $categoryQuestions[$qna->id])
                                    <div class="accordion-item">
                                       <h4 class="accordion-title scroll-to-top">{!! $frontHelp->pField($qna, 'title') !!}</h4>
                                       <div class="accordion-content">
                                          <div class="shorten-content">
                                             {!! $frontHelp->pField($qna, 'body') !!}
                                          </div>
                                       </div>
                                       <!-- /.accordion-item -->
                                    </div>

                                    @endif
                                    <?php
                                 $qq = [];
                                 $title = $frontHelp->pField($qna, 'title');
                                 $quest = $frontHelp->pField($qna, 'body');

                                 $questions['@type'] = "Question";
                                 $questions['name'] = $title;
                                 $qq["@type"] = "Answer";
                                 $qq["text"] = $quest;

                                 $questions["acceptedAnswer"] = $qq;//json_encode($qq);

                                 $f[]= $questions; //json_encode($questions);
                                 

                              ?>
                                    @endforeach
                                    <!-- /.accordion-wrapper -->
                                 </div>
                                 <!-- /.tab-faq-wrapper -->
                              </div>
                              @endif
                              @endif
                              @endforeach
                              <?php $f=json_encode($f);?>
                           <script type="application/ld+json">
                           {
                              "@context": "https://schema.org",
                              "@type": "FAQPage",
                              "mainEntity": {!!$f!!}
                              }
									</script>
                              @endif
                              <!-- /.course-full-text -->
                           </div>
                           <!-- /.tab-content-wrapper -->
                           @endif
                        </div>
                        @if(Sentinel::check() && !$instructor_topics && $is_event_paid !== 0)
                           <div id="files" class="tab-content-wrapper">
                              @include('theme.event.partials.files')
                           </div>
                           @if($exam)
                              <div id="exam" class="tab-content-wrapper">
                                 @include('theme.event.partials.exam')
                              </div>
                           @endif
                        @endif

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
         <div class="col4 col-sm-12">
            <div class="sidebar-wrapper ">
               <div class="accordion-wrapper custom-scroll-area">
                  @if(isset($topics))
                  <?php $tab = 1; ?>
                  <?php $firstLesson = 1; ?>
                  @foreach($topicss as $key => $value)
                  <div class="accordion-item @if ($tab === 1 && $last_video_seen ===-1) active-tab @endif" >
                     <h2 class="accordion-title scroll-to-top--inside">{!! $key !!}</h2>
                     
                     <div class="accordion-content" @if($tab === 1 && $last_video_seen ===-1) style="display: block;" @endif">
                           @foreach($value as $lke => $lvalu)
                                       @foreach($lvalu as $lkey => $lvalue)
                                    <?php 
                                       $tabb = $eventTitle.'_'.$lvalue['tab'];
                                       $tabb = str_replace(' ','_',$tabb);
                                       $tabb = str_replace('-','',$tabb);
                                       $tabb = str_replace('&','',$tabb);
                                       $tabb = str_replace('_','',$tabb);
                                       
                                       //$tabb = $lke;
                                    ?>
                           <div id="{{$tabb}}" class="topic-wrapper @if($lvalue['seen']) watched @endif">
                              
                              @if($is_event_paid==0 || !$video_access)
                              <a class="go-to-href" href="#seats"><img src="{{cdn('/theme/assets/images/icons/Lock.svg')}}" width="12" class="icon" alt=""/></a>
                              <div class="topic-title-meta">
                                 <h3><a class="go-to-href" href="#seats">{!! $lkey !!}</a></h3>
                                 <div class="topic-meta">
                                    <span class="duration"><img src="{{cdn('/theme/assets/images/icons/Times.svg')}}" width="12" alt="" />{{$lvalue['duration']}}</span>
                                 </div>
                              </div>
                              @else
                              <?php
                                 $path = $lvalue['vimeo'];
                                 if($firstLesson==1){
                                    $firstLesson = 0;
                                   
                                  //  echo "<script> document.getElementsByClass(initframe)[0].setAttribute('data-vimeo-url','$path') </script>";
                                 //   echo "<script> document.getElementsByClass(initframe)[1].setAttribute('data-vimeo-url','$path') </script>";
                                 }
                                 ?>
                              <a class="play-link" onclick="playvideo('{{$path}}','{{$tabb}}','{video{{$lvalue['id']}}}', '{{$lvalue['id']}}')" href="javascript:void(0)"> 
                              @if($lvalue['seen'])
                                 <img src="{{cdn('/theme/assets/images/icons/check_lesson.svg')}}" width="12" class="icon" alt=""/></a>
                              @else
                              <img id="play-image-event{{$lvalue['id']}}" src="{{cdn('/theme/assets/images/icons/Play.svg')}}" width="12" class="icon" alt=""/></a>
                              @endif                        <div class="topic-title-meta">
                                 <h3><a class="play-link" onclick="playvideo('{{$path}}','{{$tabb}}','{video{{$lvalue['id']}}}', '{{$lvalue['id']}}')" href="javascript:void(0)">{!! $lkey !!}</a></h3>
                                 <div class="topic-meta">
                                    <span class="duration"><img src="{{cdn('/theme/assets/images/icons/Times.svg')}}" width="12" alt="" />{{$lvalue['duration']}}</span>
                                 </div>
                              </div>
                              @endif

                              @if($lvalue['status'])
                              <div class="author-img">
                                 <a href="{{ $lvalue['slug']}}">
                                 <span class="custom-tooltip">{{ $lvalue['inst'] }}</span>
                                 <img alt="{{ $lvalue['inst'] }}" src="{{ cdn($lvalue['inst_photo']) }}"/>
                                 </a>
                              </div>

                              @else
                              <div class="author-img">
                                 <a class="non-pointer" href="javascript:void(0)">
                                 <span class="custom-tooltip">{{ $lvalue['inst'] }}</span>
                                 <img alt="{{ $lvalue['inst'] }}" src="{{ cdn($lvalue['inst_photo']) }}"/>
                                 </a>
                              </div>
                              @endif
                           </div>
                           @endforeach
                     @endforeach
                     
                  </div>
                 
               </div>
               <?php $tab = $tab +1?>
                  @endforeach
             
                  @endif
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
   @include('theme.event.partials.seats')                                                   
</main>
@endsection
@section('scripts')


<script>
   /// set link to syllabus manager image
   let syllabusLink = $('.info-text').find('a').attr('href');
   document.getElementById('syllabus-link').setAttribute('href',syllabusLink);

</script>

@if(Sentinel::check() && !$instructor_topics && $is_event_paid !== 0)
<script>
$('.getdropboxlink').click(function() {
      
      var dir = $(this).attr('data-dirname');
      var fname = $(this).attr('data-filename');
   
      $.ajax({ url: '/getdropbox', type: "post",
          data: {dir: dir, fname:fname},
   
          success: function(data) {
           //console.log(data);
            window.location.href = data;
          }
      });
   
   });

</script>
@endif

@if($is_event_paid!=0 && $video_access)

<script>



    $('body').click(function() {
      
      $('iframe').focus();
   

    });

   var width = $('#courses-explain-video').width();
   var html = `<div  focus class="initframe" data-vimeo-url="{{$fv}}" data-vimeo-width="` + width +`"id="{vimeo}"></div>`                  
   $('#courses-video').empty();
   $('#courses-video').append(html);

   var width = $('#courses-tab').width();
   var html = `<div focus class="initframe" data-vimeo-url="{{$fv}}" data-vimeo-width="` + width +`"id="{vimeom}"></div>`                  

   $('#courses-tab').empty();
   $('#courses-tab').append(html);
   
  
  

</script>

<script src="https://player.vimeo.com/api/player.js"></script>

   <script type="text/javascript">

      
      var videos = `<?php echo json_encode($videos); ?>`;
      videos = JSON.parse(videos);

      var previousVideo = false;
      if(window.innerWidth<=980){
         var previousK='{vimeom}';

      }else{
         var previousK='{vimeo}';

      }
      var video02Player;
      var videoId;
      var previousVideoId; 
      var playVi = false;
      
      
      if({{$last_video_seen}} != -1){
      // console.log('edww');
         let vimeoID ='"{video'+videos[{{$last_video_seen}}]['lesson_id'] +'}"';
      // console.log(vimeoID);
         videoId = {{$last_video_seen}}


         if(window.innerWidth<=980){
            previousVideo = videos[{{$last_video_seen}}]['tab']+'m';
         }else{
            previousVideo = videos[{{$last_video_seen}}]['tab'];

         }

         var cvl = document.getElementById(previousK).cloneNode(true);;
         cvl = document.getElementById(previousK).setAttribute('id',vimeoID);

         $('#courses-video').append(cvl)
         previousK = vimeoID;

         $('#courses-explain-video').hide()
         $('#courses-video').show()
      
      // $('#'+previousVideo).addClass('isWatching')
      //console.log('last video = ', {{$last_video_seen}})
      //console.log('previous videos = ', previousVideo)
      document.getElementById(previousVideo).classList.add('isWatching')

         video02Player = new Vimeo.Player(vimeoID);
         video02Player.loadVideo(videoId).then(function(id) {
      
            $('iframe').focus();
            videoId = id

            video02Player.setCurrentTime(videos[id]['stop_time'])
         
         }).catch(function(error) {
            switch (error.name) {
               case 'TypeError':
                     // the id was not a number
                     break;

               case 'PasswordError':
                     // the video is password-protected and the viewer needs to enter the
                     // password first
                     break;

               case 'PrivacyError':
                     // the video is password-protected or private
                     break;

               default:
                     // some other error occurred
                     break;
            }
         });

      }

      document.body.onkeydown= function(e){
         
         if(e.keyCode == 32){

            if(this.playVi){

               this.playVi = false;
               video02Player.pause().then(function() {

               }).catch(function(error) {
                  switch (error.name) {
                     case 'PasswordError':
                           // the video is password-protected and the viewer needs to enter the
                           // password first
                           break;

                     case 'PrivacyError':
                           // the video is private
                           break;

                     default:
                           // some other error occurred
                           break;
                  }
               });

            }else{
               this.playVi = true;

               video02Player.play().then(function() {
               // the video was paused
               }).catch(function(error) {
                  switch (error.name) {
                     case 'PasswordError':
                           // the video is password-protected and the viewer needs to enter the
                           // password first
                           break;

                     case 'PrivacyError':
                           // the video is private
                           break;

                     default:
                           // some other error occurred
                           break;
                  }
               });

            }

            $('iframe').focus();
            e.preventDefault();
         
         }
      }

   video02Player.on('play', function(e) {

     this.playVi = true; 
     
   });

   video02Player.on('fullscreenchange', function(e) {
      
      $('body').focus();
      window.focus()
   });
      function playvideo(video,playingVideo,vk,lesson){
   
         if(previousVideo !==false){
            //$('#'+previousVideo).removeClass('isWatching')
            document.getElementById(previousVideo).classList.remove('isWatching')
         }
            
      // $('#'+playingVideo).addClass('isWatching')
      document.getElementById(playingVideo).classList.add('isWatching')
         previousVideo = playingVideo;
         var cvl = document.getElementById(previousK).cloneNode(true);;
         cvl = document.getElementById(previousK).setAttribute('id',vk);

         $('#courses-video').append(cvl)
         $('#courses-tab').append(cvl)
         previousK = vk;

         $('#courses-explain-video').hide()
         $('#courses-video').show()

         video = video.split('/')
         video = video[4].split('?')[0]
         video02Player = null;
         video02Player = new Vimeo.Player(vk);

         video02Player.loadVideo(video).then(function(id) {
            $('iframe').focus();
            videoId = id
            video02Player.setCurrentTime(videos[id]['stop_time'])
         }).catch(function(error) {
            switch (error.name) {
               case 'TypeError':
                     // the id was not a number
                     break;

               case 'PasswordError':
                     // the video is password-protected and the viewer needs to enter the
                     // password first
                     break;

               case 'PrivacyError':
                     // the video is password-protected or private
                     break;

               default:
                     // some other error occurred
                     break;
            }
         });
      

         var elToScroll = $('.video-point-scroll').offset().top - $('#header').outerHeight();
         // console.log(elToScroll)
         $('html, body').animate({
               scrollTop: elToScroll
         }, 300);
      // return false;
      }
                     
      video02Player.on('pause', function(e) {
      
         @if(!$instructor)

         if(this.playVi){
           
               //this.playVi = false;
               videos[videoId]['stop_time'] = e['seconds'];
               videos[videoId]['percentMinutes'] = e['percent'];

               if(e['percent'] >= 0.8){
                     videos[videoId]['seen'] = 1;
               }

               video02Player.off('play');
               
               $.ajax({
                  type: 'PUT',
                  url: '/elearning/save',
                  data:{'videos':videos,'event_statistic':{{$event_statistic_id}},'lastVideoSeen':videoId,'event':{{$content->id}}},
                  success: function(data) {    
                     
                     if(!data['loged_in']){
                        notLogin(data)
                     }
                     checkForExam(data['exam_access'])
                  }
               });
         }
     
            
         

         @endif

      });


      video02Player.on('progress', function(e) {
         @if(!$instructor)
            if(e['percent'] >= 0.8){
               if(videos[videoId]['seen'] == 0){
                  
                  videos[videoId]['stop_time'] = e['seconds'];
                  videos[videoId]['percentMinutes'] = e['percent'];
                  videos[videoId]['seen'] = 1;
                  
                  document.getElementById('play-image-event'+videos[videoId]['lesson_id']).setAttribute('src',"{{cdn('/theme/assets/images/icons/check_lesson.svg')}}")
                  document.getElementById('play-image-event'+videos[videoId]['lesson_id']+'m').setAttribute('src',"{{cdn('/theme/assets/images/icons/check_lesson.svg')}}")
                  
               
                  $.ajax({
                        type: 'PUT',
                        url: '/elearning/save',
                        data:{'videos':videos,'event_statistic':{{$event_statistic_id}},'lastVideoSeen':videoId,'event':{{$content->id}}},
                        success: function(data) {    
                           if(!data['loged_in']){
                              notLogin(data)
                           }   
                           checkForExam(data['exam_access'])
                        }
                  });
            

               }
            }
         @endif
      });


      


   if(window.innerWidth > 980){
      $(window).on('load', function(){
         if({{$last_video_seen}} != -1){
            var $this = $('#'+videos[{{$last_video_seen}}]['tab']).parent().parent().children('h2')
            $this.click();
         }
      })
   }




   @if(!$instructor)
   

      window.onbeforeunload = function (ev) {

            
            video02Player.pause().then(function() {
            
            
            }).catch(function(error) {
               switch (error.name) {
                  case 'PasswordError':
                        // the video is password-protected and the viewer needs to enter the
                        // password first
                        break;

                  case 'PrivacyError':
                        // the video is private
                        break;

                  default:
                        // some other error occurred
                        break;
               }
            });

         



      };
      @endif

   </script>


<script>
   function dok(){
      if({{$last_video_seen}} != -1){
      var $this = $('#'+videos[{{$last_video_seen}}]['tab']+'m').parent().parent().children('h2')
      $this.click();
   }
   }
</script>

<script>

   function notLogin(data){
        
      let p = ''
      p = `<img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-success-alert.svg')}}" alt="Info Alert">` + data['message'];
      $('#message').append(p);
      var favDialog = document.getElementById('favDialog1');
      favDialog.style.display = "block";
      $("body").css("overflow-y", "hidden")

      setTimeout( function(){
            window.location.replace(data['redirect']);
      }, 3000 );

   }

</script>


<script>

   function checkForExam(examAccess){
     
      if(examAccess){

         var d = new Date();
         d.setTime(d.getTime() + (1*24*60*60*1000));
         var expires = "expires="+ d.toUTCString();
         document.cookie = 'examMessage-' + "{{$event_statistic_id}}" + "=" + 'showmessage' + ";" + expires + ";path=/";

         var favDialog = document.getElementById('examDialog');
         favDialog.style.display = "block";
         $("body").css("overflow-y", "hidden")

         video02Player.pause().then(function() {

         }).catch(function(error) {
            switch (error.name) {
               case 'PasswordError':
                     // the video is password-protected and the viewer needs to enter the
                     // password first
                     break;

               case 'PrivacyError':
                     // the video is private
                     break;

               default:
                     // some other error occurred
                     break;
            }
         });

      }
      
   }

</script>


<script>

   $("#close-exam-dialog").click(function(){

      var favDialog = document.getElementById('examDialog');
         favDialog.style.display = "none";
         $("body").css("overflow-y", "auto")

         video02Player.play().then(function() {
            // the video was paused
            }).catch(function(error) {
               switch (error.name) {
                  case 'PasswordError':
                        // the video is password-protected and the viewer needs to enter the
                        // password first
                        break;

                  case 'PrivacyError':
                        // the video is private
                        break;

                  default:
                        // some other error occurred
                        break;
               }
            });
   })


   $(".go-to-account").click(function(){
      window.location.replace('/myaccount');
   })




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