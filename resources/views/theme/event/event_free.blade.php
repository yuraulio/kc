@extends('theme.layouts.master')
@inject('frontHelp', 'Library\FrontendHelperLib')
@section('content')
<?php echo" <script>
   document.getElementById('header').classList.add('header-transparent');
   </script>"?>
@if(isset($content['c_fields']['dropdown_select_status']['value']))
<?php $estatus = $content['c_fields']['dropdown_select_status']['value']; ?>
@else
<?php $estatus = 0; ?>
@endif
<?php $benefitTAB = $content->checkForBenefitTab(); ?>
<main id="main-area" class="with-hero" role="main">
   <script type="application/ld+json">
      {
        "@context": "https://schema.org",
        "@type": "Course",
        "name": "{!!$content->title!!}",
        "description": "{{ $content->subtitle }}",
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
   <section class="section-hero section-after-tabs-mob" style="background-image: url('{{$img}}');">
      <div class="overlay"></div>
      <div class="container">
         <div class="hero-message">
            <h1>{{ $content->title }}</h1>
            <h2>{{ $content->subtitle }}</h2>
         </div>
      </div>
      <!-- /.section-hero -->
   </section>
   <section class="section-course-tabs">
      <div class="content-wrapper">
         <div class="tabs-wrapper fixed-tab-controls">
            <div class="tab-controls">
               <div class="container">
                  <a href="#" class="mobile-tabs-menu">Menu</a>
                  <ul class="clearfix tab-controls-list">
                     <li><a href="#overview" class="active">Overview</a></li>
                     @if($estatus == 0 || $estatus == 2)
                     @if($benefitTAB)
                     <li><a href="#benefits">Benefits</a></li>
                     @endif
                     <li><a href="#topics">Topics</a></li>
                     <li><a href="#instructors">Instructors</a></li>
                     @if(isset($section_testimonials)) <li><a href="#testimonials">Testimonials</a></li>@endif
                     @if(isset($linkedvenue))<li><a href="#location">Location</a></li>@endif
                     @if(isset($section_qnas))<li><a href="#faq">FAQ</a></li>@endif
                     @elseif($estatus == 3 || $estatus == 1 )
                     <li><a href="#topics">Topics</a></li>
                     <li><a href="#instructors">Instructors</a></li>
                     @endif
                  </ul>
                 
                  @if($is_event_paid==0 && !Sentinel::getUser())
                  <a href="{{ route('cart.add-item', [ $content->id,'free', 8 ]) }}" class="btn btn--lg btn--primary hidden-sm go-to-href">ENROLL FOR FREE</a>
                  @elseif($is_event_paid==0 && Sentinel::getUser())
                  <a href="{{ route('enrollForFree',  $content->id) }}" class="btn btn--lg btn--primary hidden-sm go-to-href">ENROLL FOR FREE</a>
                  @endif
                  <!-- /.container -->
               </div>
               <!-- /.tab-controls -->
            </div>
            <div class="tabs-content">
               <div id="overview" class="tab-content-wrapper active-tab">
                  <div class="container">
                     @if($estatus == 0 || $estatus == 2)
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
                     @endif
                     <?php
                        $title = '' ;
                        $body = '' ;
                        $cont = $content->titles()->where('category','overview');
                        
                        if($cont->first() && ($cont->first()->title!=null || $cont->first()->title!='')){
                              $title = $cont->first()->title;
                              $body = $cont->first()->body;
                           }
                        
                        ?>
                     
                     <?php switch ($estatus) {
                        case 0:
                        case 2: ?>
                        <h2 class="tab-title">{{$title}}</h2>
                     <h3 > {!!$body!!} </h3>
                     <div class="course-overview clearfix">
                        <div class="course-tab-text" itemprop="abstract">
                           {!! $content->body !!}
                           <div class="author-infos">
                              <div class="row">
                                 <div class="col6 col-xs-12">
                                    <div id="syll" class="ibox">
                                       <div class="author-img">
                                          <?php
                                             $alt='';
                                             $img ='';
                                             
                                             if (!empty($section_syllabus_manager['featured']) && isset($section_syllabus_manager['featured'][0]) && 
                                             isset($section_syllabus_manager['featured'][0]['media']) && !empty($section_syllabus_manager['featured'][0]['media'])){
                                               $alt = $frontHelp->pField($section_syllabus_manager, 'title') ;
                                               $img = $frontHelp->pImg($section_syllabus_manager, 'instructors-small');
                                             }
                                             
                                             ?>
                                          <a id="syllabus-link" href=""><img src="{{cdn($img)}}" alt="{{$alt}}"></a>
                                       </div>
                                       <div class="ibox-text">
                                          <p>{{ $section_syllabus_manager->title }}<br/>{!! $section_syllabus_manager->body !!}</p>
                                       </div>
                                    </div>
                                 </div>
                                 @if(isset($section_organisers)) 
                                 <div class="col6 col-xs-12">
                                    <div class="ibox">
                                       @foreach($evorg as $vkey => $vvalue)
                                       <?php
                                          $alt='';
                                          $img ='';
                                          
                                          if (isset($evorg)){
                                            $media = PostRider\Media::select('id','path','name','ext')->findOrFail($evorg[0]->allMedia[0]['media_id'])->toArray();
                                            $alt = $evorg[0]->name;
                                            $img = "/uploads/originals/".$media['path'] . '/' . $media['name'] . $media['ext'];
                                          }
                                          
                                          ?>
                                       <div class="ibox-img">
                                          <a href="{{$evorg[0]->abbr}}" title="{{$alt}}" target="_blank"><img src="{{cdn($img)}}" alt="{{$alt}}"></a>
                                       </div>
                                       <div class="ibox-text">
                                          {!! $section_organisers->htmlTitle !!}
                                       </div>
                                       @endforeach
                                    </div>
                                 </div>
                                 @endif
                              </div>
                           </div>
                           <!-- /.course-overview-text -->
                        </div>
                        <div class="course-tab-sidebar">
                           <div class="course-details @if(!isset($section_fullvideo)) non-video-height @endif">
                              <ul class="two-column-list">

                              <?php if (isset($content['c_fields']['simple_text'][0]) && $content['c_fields']['simple_text'][0]['value'] != '') : ?>
                                 <li>
                                    <img class="info-icon" class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Duration_Hours.svg')}}" width="30" />
                                    <div class="info-text">
                                       <p>{{ $content['c_fields']['simple_text'][0]['value'] }}</br>
                                          {{ $content['c_fields']['simple_text'][1]['value'] }}
                                       </p>
                                    </div>
                                 </li>
                                 <?php endif ?>

                                 <?php if (isset($content['c_fields']['simple_text'][10]) && $content['c_fields']['simple_text'][10]['value'] != '') : ?>
                                 <li>
                                    <img class="info-icon" class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Days-Week.svg')}}"  width="30" />
                                    <div class="info-text">
                                       <p>{{ $content['c_fields']['simple_text'][10]['value'] }}</br>
                                          {{ $content['c_fields']['simple_text'][11]['value'] }}
                                    </div>
                                 </li>
                                 <?php endif ?>

                                 <?php if (isset($content['c_fields']['simple_text'][2]) && $content['c_fields']['simple_text'][2]['value'] != '') : ?>
                                 <li>
                                    <img class="info-icon" class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Times.svg')}}" width="30" />
                                    <div class="info-text">
                                       <p>{{ $content['c_fields']['simple_text'][2]['value'] }}</br>
                                          {{ $content['c_fields']['simple_text'][3]['value'] }}
                                       </p>
                                    </div>
                                 </li>
                                 <?php endif ?>

                                 <?php if (isset($content['c_fields']['simple_text'][6]) && $content['c_fields']['simple_text'][6]['value'] != '') : ?>
                                 <li>
                                    <img class="info-icon" class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Language.svg')}}"  width="30" />
                                    <div class="info-text">
                                       <p>{{ $content['c_fields']['simple_text'][6]['value'] }}</br>
                                          {{ $content['c_fields']['simple_text'][7]['value'] }}
                                    </div>
                                 </li>
                                 <?php endif ?>

                               
                                
                                 <?php if (isset($content['c_fields']['simple_text'][4]) && $content['c_fields']['simple_text'][4]['value'] != '') : ?>
                                 <li>
                                    <img class="info-icon" class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Level.svg')}}"  width="30" />
                                    <div class="info-text">
                                       <p>{{ $content['c_fields']['simple_text'][4]['value'] }}</br>
                                          {{ $content['c_fields']['simple_text'][5]['value'] }}
                                       </p>
                                    </div>
                                 </li>
                                 <?php endif ?>

                                 <?php if (isset($content['c_fields']['simple_text'][8]) && $content['c_fields']['simple_text'][8]['value'] != '') : ?>
                                 <li>
                                    <img class="info-icon" class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}" width="30" />
                                    <div class="info-text">
                                       <p>{{ $content['c_fields']['simple_text'][8]['value'] }}</br>
                                          {{ $content['c_fields']['simple_text'][9]['value'] }}
                                       </p>
                                    </div>
                                 </li>
                                 <?php endif ?>
                                 <?php if (isset($content['c_fields']['simple_text'][13]) && $content['c_fields']['simple_text'][13]['value'] != '') : ?>
                                 <li>
                                    <img class="info-icon" class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/messages-warning-information.svg')}}" width="30" />
                                    <div class="info-text">
                                       <p>{{ $content['c_fields']['simple_text'][13]['value'] }}</br>
                                          {{ $content['c_fields']['simple_text'][14]['value'] }}
                                       </p>
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
                                
                                
                              </ul>
                              <!-- /.course-details -->
                           </div>
                           <!-- /.course-overview-sidebar -->
                        </div>
                        <!-- /.course-overview.clearfix -->
                     </div>
                     <?php break;
                        case 1:
                        case 3: ?>
                     
                     <div  class="course-overview clearfix padd-bottom">
                     <div class="course-tab-text" itemprop="abstract">
                     @if(isset($section_about_completed))
                        <span class="completed">{{ $section_about_completed->title }}</span>
                        {!! $section_about_completed->body !!}
                        <p>Please check all our @if(isset($location->name)) <a href="{{ $location->slug }}">upcoming events in {{ $location->name }}</a> @else upcoming events in this city @endif.</p>
                        @else
                        <span class="completed">The event is completed</span>
                        <p style="display:none">The best digital &amp; social media diploma with a long track record trusted by top executives, agencies, brands and corporations is completed.</p>
                        <p >Please check all our @if(isset($location->name)) <a href="{{ $location->slug }}">upcoming events in {{ $location->name }}</a> @else upcoming events in this city @endif.</p>
                        {{--<p style="display:none">Please check all our @if(isset($location->name)) <a href="{{ $location->slug }}">upcoming events in {{ $location->name }}</a> @else upcoming events in this city @endif.</p>--}}
                        @endif
                        </div>
                     </div>
                     <?php break;
                        } ?>
                     @if($estatus == 0 || $estatus == 2)
                     @if(isset($section_fullvideo) && $section_fullvideo->body != '')
                     <div class="video-wrapper">
                        <div class="responsive-fb-video">
                           {!! $section_fullvideo->body !!}
                        </div>
                     </div>
                     @endif
                     @endif
                     <!-- /.container -->
                  </div>
                  <!-- /.tab-content-wrapper -->
               </div>
               <div id="benefits" class="tab-content-wrapper">
                  <div class="course-benefits-text">
                     <div class="container">
                        <?php
                           $title = '' ;
                           $body = '' ;
                           $cont = $content->titles()->where('category','benefits');
                           
                           if($cont->first() && ($cont->first()->title!=null || $cont->first()->title!='')){
                                 $title = $cont->first()->title;
                                 $body = $cont->first()->body;
                              }
                           
                           ?>
                        <h2 class="text-align-center text-xs-left tab-title">{!!$title!!}</h2>
                        <h3>{!!$body!!}</h3>
                        <div class="benefits-list">
                           <div class="row-flex row-flex-17">
                              <?php $category = 'freepresentations';  $benefit = $content->benefit()->where('category',$category)->first();
                                 if (isset($benefit) && $benefit->title != '') : ?>
                              <div class="col-3 col-sm-6 col-xs-12">
                                 <div class="benefit-box">
                                    <div class="box-icon">
                                       <img class="replace-with-svg" src="{{cdn('theme/assets/images/icons/Access-Files.svg')}}" width="40" alt="">
                                    </div>
                                    <h3>{{ $benefit->title }}</h3>
                                    {!! $benefit->description !!}
                                    <!-- /.benefit-box -->
                                 </div>
                              </div>
                              <?php endif ?>
                              <?php $category = 'e-learning';  $benefit = $content->benefit()->where('category',$category)->first();
                                 if (isset($benefit) && $benefit->title != '') : ?>
                              <div class="col-3 col-sm-6 col-xs-12">
                                 <div class="benefit-box">
                                    <div class="box-icon">
                                       <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/E-Learning.svg')}}" width="40" alt="">
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
                              <div class="col-3 col-sm-6 col-xs-12">
                                 <div class="benefit-box">
                                    <div class="box-icon">
                                       <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Jobs_access.svg')}}" width="40" alt="">
                                    </div>
                                    <h3>{{ $benefit->title }}</h3>
                                    {!! $benefit->description !!}
                                    <!-- /.benefit-box -->
                                 </div>
                              </div>
                              <?php endif ?>
                              <?php $category = 'events access';  $benefit = $content->benefit()->where('category',$category)->first();
                                 if (isset($benefit) && $benefit->title != '') : ?>
                              <div class="col-3 col-sm-6 col-xs-12">
                                 <div class="benefit-box">
                                    <div class="box-icon">
                                       <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Events_Access.svg')}}" width="40" alt="">
                                    </div>
                                    <h3>{{ $benefit->title }}</h3>
                                    {!! $benefit->description !!}
                                    <!-- /.benefit-box -->
                                 </div>
                              </div>
                              <?php endif ?>
                              <?php $category = 'free recaps';  $benefit = $content->benefit()->where('category',$category)->first();
                                 if (isset($benefit) && $benefit->title != '') : ?>
                              <div class="col-3 col-sm-6 col-xs-12">
                                 <div class="benefit-box">
                                    <div class="box-icon">
                                       <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Recap-Events.svg')}}" width="40" alt="">
                                    </div>
                                    <h3>{{ $benefit->title }}</h3>
                                    {!! $benefit->description !!}
                                    <!-- /.benefit-box -->
                                 </div>
                              </div>
                              <?php endif ?>
                              
                              <?php $category = 'projects info';  $benefit = $content->benefit()->where('category',$category)->first();
                                 if (isset($benefit) && $benefit->title != '') : ?>
                              <div class="col-3 col-sm-6 col-xs-12">
                                 <div class="benefit-box">
                                    <div class="box-icon">
                                       <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Customer_Access.svg')}}" width="40" alt="">
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
                  </div>
                  <!-- /.tab-content-wrapper -->
               </div>
             
               <div id="topics" class="tab-content-wrapper">
                  <div class="container">
                     <div class="course-full-text">
                        <?php
                           //$title = $section_topics->title ;
                           //$body = $section_topics->body ;
                           $cont = $content->titles()->where('category','topics');
                           
                           if($cont->first() && ($cont->first()->title!=null || $cont->first()->title!='')){
                                 $title = $cont->first()->title;
                                 $body = $cont->first()->body;
                              }
                           
                           ?>
                        <h2 class="text-align-center text-xs-left tab-title"> {!! $title !!}</h2>
                        <div class="topic-text-area">
                           {!! $body !!}
                        </div>
                        @if(isset($topics))
                        <div class="tab-faq-wrapper topic-content">
                           <div class="accordion-wrapper accordion-big">
                              @foreach($topicss as $key => $value)
                              <div class="accordion-item">
                                 <h3 class="accordion-title title-blue-gradient scroll-to-top">{!! $key !!}</h3>
                                 <div class="accordion-content">
                                 @foreach($value as $lke => $lvalu)
                                 @foreach($lvalu as $lkey => $lvalue)
                                    <div class="topic-wrapper-big">
                                       <div class="topic-title-meta">
                                          <h4>{!! $lkey !!}</h4>
                                        
                                          
                                          <div class="topic-meta">
                                             @if($lvalue['type'])<div class="category">{{$lvalue['type']}}</div>@endif
                                             <span class="meta-item duration"><img src="{{cdn('/theme/assets/images/icons/icon-calendar.svg')}}" width="12" alt="" />{{$lvalue['eldate']}}</span>
                                             <span class="meta-item duration"><img src="{{cdn('/theme/assets/images/icons/Times.svg')}}" width="12" alt="" />{{$lvalue['eltime']}} ({{$lvalue['inclass_duration']}})</span>
                                             <span class="meta-item duration"><img src="{{cdn('/theme/assets/images/icons/icon-marker.svg')}}" width="12" alt="" />{{$lvalue['room']}}</span>
                                          </div>
                                          <!-- /.topic-title-meta -->
                                       </div>
                                       <div class="author-img">
                                          @if($lvalue['status'])<a href="{{ $lvalue['slug']}}">
                                          <span class="custom-tooltip">{{ $lvalue['inst'] }}</span>
                                          <img alt="{{ $lvalue['inst'] }}" src="{{ cdn($lvalue['inst_photo']) }}"/>
                                          </a>
                                          @else
                                          <a class="non-pointer" href="javascript:void(0)">
                                          <span class="custom-tooltip">{{ $lvalue['inst'] }}</span>
                                          <img alt="{{ $lvalue['inst'] }}" src="{{ cdn($lvalue['inst_photo']) }}"/>
                                          </a>
                                          @endif
                                       </div>
                                       <!-- /.topic-wrapper -->
                                    </div>
                                    @endforeach
                              @endforeach
                                    <!-- /.accordion-content -->
                                 </div>
                                 <!-- /.accordion-item -->
                              </div>
                              <!-- /.accordion-wrapper -->
                              @endforeach
                           </div>
                           <!-- /.tab-faq-wrapper -->
                        </div>
                        @endif
                        <!-- /.course-full-text -->
                     </div>
                  </div>
                  <!-- /.tab-content-wrapper -->
               </div>
               
               <div id="instructors" class="tab-content-wrapper tab-blue-gradient">
                  <div class="container">
                     <div class="course-full-text">
                        @if(isset($instructors))
                        <?php
                           //$title = $section_instructors->title ;
                           //$body = $section_instructors->body ;
                           $cont = $content->titles()->where('category','instructors');
                           
                           if($cont->first() && ($cont->first()->title!=null || $cont->first()->title!='')){
                                 $title = $cont->first()->title;
                                 $body = $cont->first()->body;
                              }
                           
                           ?>
                        <h2 class="text-align-center text-xs-left tab-title">{!!$title!!}</h2>
                        <h3>{!!$body!!}</h3>
                        <div class="instructors-wrapper row row-flex row-flex-23">
                           @foreach($instructors as $lkey => $lvalue)
                           <div class="col-3 col-md-4 col-sm-6 col-xs-12">
                              <div class="instructor-box">
                                 <div class="instructor-inner">
                                    <?php 

                                       $img = '';
                                       $inst_url = $lvalue->slug;
                                       $ext_url = $lvalue['ext_url'];
                                       $fb = '';
                                       $inst = '';
                                       $twitter = '';
                                       $med = '';
                                       $pint = '';
                                       $linkedIn = '';
                                       $yt = '';;
                                       $name = $lvalue['title'] . ' ' . $lvalue['subtitle'];
                                       $field1 = '';
                                       $field2 ='';
                                       
                                       if(isset($lvalue['featured'][0]['media'])){
                                         $img =  $frontHelp->pImg($lvalue, 'instructors-testimonials');
                                       }
                                       
                                       if(isset($lvalue['header'])){
                                         $field1 =  $lvalue['header'];
                                       }
                                       
                                       if(isset($lvalue['header'])){
                                         $field2 = $lvalue['c_fields']['simple_text'][1]['value'];
                                       }
                                       
                                       if(isset($lvalue['c_fields']['simple_text'][2]) && $lvalue['c_fields']['simple_text'][2]['value'] != ''){
                                          $fb = $lvalue['c_fields']['simple_text'][2]['value'];
                                        }
                                        
                                        if(isset($lvalue['c_fields']['simple_text'][3]) && $lvalue['c_fields']['simple_text'][3]['value'] != ''){
                                        
                                          $twitter = $lvalue['c_fields']['simple_text'][3]['value'];
                                        
                                        }
                                        
                                        if(isset($lvalue['c_fields']['simple_text'][4]) && $lvalue['c_fields']['simple_text'][4]['value'] != ''){
                                        
                                          $inst = $lvalue['c_fields']['simple_text'][4]['value'];
                                        }
                                        if(isset($lvalue['c_fields']['simple_text'][5]) && $lvalue['c_fields']['simple_text'][5]['value'] != ''){
                            
                                           $linkedIn = $lvalue['c_fields']['simple_text'][5]['value'];
                                        
                                        }
                                        if(isset($lvalue['c_fields']['simple_text'][6]) && $lvalue['c_fields']['simple_text'][6]['value'] != ''){
                                           
                                           $yt = $lvalue['c_fields']['simple_text'][6]['value'];
                                        
                                        }
                                       
                                       
                                       ?>
                                    <div class="profile-img">
                                      @if($lvalue->status) 
                                          <a href="{{$inst_url}}"><img src="{{cdn($img)}}"  title="{{$name}}" alt="{{$name}}"></a>
                                      @else
                                          <img src="{{cdn($img)}}"  title="{{$name}}" alt="{{$name}}">
                                      @endif
                                    </div>
                                    @if($lvalue->status) 
                                       <h3><a href="{{$inst_url}}">{{$name}}</a></h3>
                                    @else
                                       <h3>{{$name}}</h3>
                                    @endif
                                    <p>{{$field1}}, <a target="_blank" title="{{$field1}}" @if($ext_url!='') href="{{$ext_url}}"@endif>{{$field2}}</a>.</p>
                                    <ul class="social-wrapper">
                                       @if($fb != '')	
                                       <li><a target="_blank" href="{{$fb}}"><img class="replace-with-svg"  src="/theme/assets/images/icons/social/Facebook.svg" width="16" alt="Visit"></a></li>
                                       @endif
                                       @if($inst !='')	
                                       <li><a target="_blank" href="{{$inst}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Instagram.svg')}}" width="16" alt="Visit"></a></li>
                                       @endif
                                       @if($linkedIn !='')	
                                       <li><a target="_blank" href="{{$linkedIn}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Linkedin.svg')}}" width="16" alt="Visit"></a></li>
                                       @endif
                                       @if($pint !='')	
                                       <li><a target="_blank" href="{{$pint}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Pinterest.svg')}}" width="16" alt="Visit"></a></li>
                                       @endif
                                       @if($twitter !='')	
                                       <li><a target="_blank" href="{{$twitter}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Twitter.svg')}}" width="16" alt="Visit"></a></li>
                                       @endif
                                       @if($yt !='')	
                                       <li><a target="_blank" href="{{$yt}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Youtube.svg')}}" width="16" alt="Visit"></a></li>
                                       @endif
                                       @if($med !='')	
                                       <li><a target="_blank" href="#"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Medium.svg')}}" width="16" alt="Visit"></a></li>
                                       @endif
                                    </ul>
                                    <!-- /.instructor-inner -->
                                 </div>
                                 <!-- /.instructor-box -->
                              </div>
                              <!-- /.col-3.col-sm-12 -->
                           </div>
                           @endforeach
                           @endif
                           <!-- /.row.row-flex -->
                        </div>
                        <!-- /.course-full-text -->
                     </div>
                     <!-- /.container -->
                  </div>
                  <!-- /.tab-content-wrapper -->
               </div>
               
               @if(isset($section_testimonials))
               <?php
                  $title = $section_testimonials->title ;
                  $body = $section_testimonials->body ;
                  $cont = $content->titles()->where('category','testimonials');
                  
                  if($cont->first() && ($cont->first()->title!=null || $cont->first()->title!='')){
                        $title = $cont->first()->title;
                        $body = $cont->first()->body;
                     }
                  
                  ?>
               <div id="testimonials" class="tab-content-wrapper tab-no-pad">
                  <div class="container">
                     <div class="course-full-text full-w-pad">
                        <h2 class="text-align-center text-xs-left tab-title">{!!$title!!}</h2>
                        <h3 class="text-align-center text-xs-left tab-title"> {!!$body!!} </h3>
                        <div class="testimonial-carousel-wrapper hidden-xs">
                           @if(isset($section_videos['c_fields']['simple_textarea']))
                           <?php $lines = preg_split("/(\r\n|\n|\r)/", $section_videos['c_fields']['simple_textarea']['value']); ?>
                           @else
                           <?php $lines = []; ?>
                           <p>There are no videos right now</p>
                           @endif
                           <div class="video-carousel-big owl-carousel">
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
                                 <a data-fancybox href="{{ $video }}"><img src="{{ $thumbURL }}" alt=""/></a>
                              </div>
                              @endforeach
                           </div>
                           <!-- /.testimonial-carousel-wrapper -->
                        </div>
                        <!-- /.course-full-text -->
                     </div>
                  </div>
                  <div class="user-testimonial-wrapper">
                     <div class="container">
                        <div class="user-testimonial-big owl-carousel">
                           @if (!empty($testimonials))
                           @foreach ($testimonials as $key => $row)
                           <div class="slide">
                              <div class="testimonial-box">
                                 <div class="author-infos">
                                    <div class="author-img">
                                       <img src="{{ cdn($frontHelp->pImg($row, 'users')) }}" alt="{!! $row->title !!}">
                                    </div>
                                    <span class="author-name">
                                    {{ $frontHelp->pField($row, 'title') }}</span>
                                    <span class="author-job">{{ $frontHelp->pField($row, 'header') }}</span>
                                 </div>
                                 <div class="testimonial-text">
                                       <?php 
                                          $rev = $frontHelp->pField($row, 'excerpt');   
                                          $rev = str_replace('"','',$rev);
                                       ?>
                                    {!! $frontHelp->pField($row, 'excerpt') !!}
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
                                                            "description": "{!! $content->subtitle !!}"
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
														    "name": "KnowCrunch"
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
                  <!-- /.tab-content-wrapper -->
               </div>
               @endif
               @if(isset($section_location))
               <?php
                  $title = $section_location->title ;
                  $body = $section_location->body ;
                  $cont = $content->titles()->where('category','location');
                  
                  if($cont->first() && ($cont->first()->title!=null || $cont->first()->title!='')){
                        $title = $cont->first()->title;
                        $body = $cont->first()->body;
                     }
                  
                  ?>
               @if(isset($linkedvenue))
             
               <div id="location" class="tab-content-wrapper">
              
                  <div class="container">
                     <div class="course-full-text">
                        <h2 class="text-align-center text-xs-left tab-title">{!!$title!!}</h2>
                        <h3> {!!$body!!} </h3>
                        @foreach($linkedvenue as $vkey => $venue)
                        <div class="location-text">
                           <h3>{!! $venue['title'] !!} </h3>
                           <p>{!! $venue['subtitle'] !!} {!! $venue['short_title'] !!}<br/><br/><br/>{!!$venue['body']!!}</p>
                        </div>
                        <div class="location-map-wrapper">
                           <iframe  src="https://maps.google.com/maps?q={!! $venue['title'] !!}&z=17&output=embed" width="1144" height="556" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                        </div>
                        @endforeach
                        <!-- /.course-full-text -->
                     </div>
                     <!-- /.container -->
                  </div>
                  <!-- /.tab-content-wrapper -->
               </div>
               @endif
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
                  <div class="container">
                     <div class="course-full-text">
                        <h2 class="text-align-center text-xs-left tab-title">{!!$title!!}</h2>
                        <h3 class="text-align-center text-xs-left tab-title">{!!$body!!}</h3>
                        @if (!empty($qnas_categories))
                        <?php $f=[] ?>
                        @foreach ($qnas_categories as $key => $row)
                        @if(in_array($row->id, $categoryQuestions))
                        <h3 class="tab-sub-title">{!! $row->name !!}</h3>
                        <div class="tab-faq-wrapper multiple-accordions">
                           <div class="accordion-wrapper">
                              @if (!empty($qnas))
                              
                              @foreach ($qnas as $qkey => $qna)
                              <?php $questions = [];?>
                              @if($row->id == $categoryQuestions[$qna->id])
                              <div class="accordion-item">
                                 <h4 class="accordion-title title-blue-gradient scroll-to-top">{!! $frontHelp->pField($qna, 'title') !!}</h4>
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
                              
                             
                              @endif

                             

                              <!-- /.accordion-wrapper -->
                           </div>
                           <!-- /.tab-faq-wrapper -->
                        </div>
                  
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
                 
                  </div>
                  <!-- /.tab-content-wrapper -->
                  @endif
               </div>
               <!-- /.tabs-content -->
            </div>
            <!-- /.tabs-wrapper -->
         </div>
         <!-- /.content-wrapper -->
      </div>
      <!-- /.section-course-tabs -->
   </section>
  
</main>
@endsection
@section('scripts')

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


<script>
   /// set link to syllabus manager image
   let syllabusLink = $('.ibox-text').find('a').attr('href');
   document.getElementById('syllabus-link').setAttribute('href',syllabusLink);
</script>

<script src="{{ cdn('theme/assets/js/cart.js') }}"></script>
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