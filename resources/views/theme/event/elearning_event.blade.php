@extends('theme.layouts.master')

@section('metas')

   {!! $event->metable->getMetas() !!}

@endsection

@section('content')
<?php echo" <script>
   document.getElementById('header').classList.add('header-transparent');
   </script>"?>

<?php $estatus = $event->status ?>

<main id="main-area" class="with-hero" role="main">
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

   <section class="section-hero section-after-tabs-mob" style="background-image: url('{{cdn(get_image($event->mediable,'header-image'))}}');">
      <div class="overlay"></div>
      <div class="container">
         <div class="hero-message">
            <h1>{{ $event->title }}</h1>
            <h2>{{ $event->subtitle }}</h2>
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
                     @if(isset($sections['overview']) && $sections['overview']->first())<li><a href="#overview" class="active">{{$sections['overview']->first()->tab_title}}</a></li>@endif
                     @if($estatus == 0 || $estatus == 2 || $estatus == 5)

                        @if(isset($sections['benefits'][0]) && $sections['benefits']->first()->visible)<li><a href="#benefits">{{$sections['benefits']->first()->tab_title}}</a></li>@endif

                        @if(isset($sections['topics'][0]) && $sections['topics']->first()->visible)<li><a href="#topics">{{$sections['topics']->first()->tab_title}}</a></li>@endif
                        @if(isset($sections['instructors'][0]) && $sections['instructors']->first()->visible)<li><a href="#instructors">{{$sections['instructors']->first()->tab_title}}</a></li>@endif
                        @if(isset($sections['testimonials'][0]) && $sections['testimonials']->first()->visible)<li><a href="#testimonials">{{$sections['testimonials']->first()->tab_title}}</a></li>@endif
                        @if(isset($sections['location'][0]) && $sections['location']->first()->visible)<li><a href="#location">{{$sections['location']->first()->tab_title}}</a></li>@endif
                        @if(isset($sections['questions'][0]) && $sections['questions']->first()->visible)<li><a href="#faq">{{$sections['questions']->first()->tab_title}}</a></li>@endif
                     @elseif($estatus == 3 || $estatus == 1 )
                        @if(isset($sections['topics'][0]) && $sections['topics']->first()->visible)<li><a href="#topics">{{$sections['topics']->first()->tab_title}}</a></li>@endif
                        @if(isset($sections['instructors'][0]) && $sections['instructors']->first()->visible)<li><a href="#instructors">{{$sections['instructors']->first()->tab_title}}</a></li>@endif
                     @endif
                  </ul>

                  @if($estatus == 0 && !$is_event_paid)
                  <a href="#seats" class="btn btn--lg btn--primary go-to-href">ENROLL NOW</a>

                  @elseif($estatus == 5 && !$is_joined_waiting_list && !$is_event_paid)
                  <a href="{{ route('cart.add-item', [ $event->id,'waiting', 8 ]) }}" class="btn btn--lg btn--primary go-to-href elearning-free">JOIN WAITING LIST</a>

                  @elseif($estatus != 3 && $estatus != 5 && $estatus != 1 && !$is_event_paid)
                  <a href="#seats" class="btn btn--lg btn--primary go-to-href go-to-href soldout">SOLD OUT</a>
                  @endif
                  <!-- /.container -->
               </div>
               <!-- /.tab-controls -->
            </div>
            <div class="tabs-content">
               <div id="overview" class="tab-content-wrapper active-tab">
                  <div class="container">
                     @if($estatus == 0 || $estatus == 2)
                     {{--<div class="social-share">
                        <ul class="clearfix">
                           <li class="fb-icon"><a target="_blank" title="Share on facebook" href="http://www.facebook.com/sharer.php?u={{ Request::url() }}" onclick="javascript:window.open(this.href,
                              '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=300');return false;"><i class="icon-facebook"></i></a></li>
                           <li class="tw-icon "><a target="_blank" title="Share on Twitter" href="http://twitter.com/share?text={{ $event->title }}&amp;url={{ Request::url() }}&amp;via=Knowcrunch" onclick="javascript:window.open(this.href,
                              '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="icon-twitter"></i></a></li>
                           <li class="in-icon"><a target="_blank" title="Share on LinkedIn" href="https://www.linkedin.com/shareArticle?mini=true&amp;url={{ Request::url() }}&amp;title={{ $event->title }}
                              &amp;summary={{ $event->summary }}&amp;source=Knowcrunch" onclick="javascript:window.open(this.href,
                              '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="icon-linkedin"></i></a></li>
                        </ul>
                     </div>--}}

                     <div class="social-share">
                        <ul class="clearfix">
                           <a target="_blank" title="Share on facebook" href="http://www.facebook.com/sharer.php?u={{ Request::url() }}" onclick="javascript:window.open(this.href,
                              '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=300');return false;">
                              <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/events/Facebook.svg')}}" width="23" alt="Share on facebook"></a></li>

                           <a target="_blank" title="Share on Twitter" href="http://twitter.com/share?text={{ $event->title }}&amp;url={{ Request::url() }}&amp;via=Knowcrunch" onclick="javascript:window.open(this.href,
                              '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                              <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/events/Twitter.svg')}}" width="23" alt="Share on Twitter"></a></li>

                           <a target="_blank" title="Share on LinkedIn" href="https://www.linkedin.com/shareArticle?mini=true&amp;url={{ Request::url() }}&amp;title={{ $event->title }}
                              &amp;summary={{ $event->summary }}&amp;source=Knowcrunch" onclick="javascript:window.open(this.href,
                              '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                              <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/events/Linkedin.svg')}}" width="23" alt="Share on LinkedIn"></a></li>
                        </ul>
                     </div>

                     @endif
                     <?php
                        $title = '' ;
                        $body = '' ;


                        if(isset($sections['overview'])){
                              $title = $sections['overview']->first()->title;
                              $body = $sections['overview']->first()->description;
                           }

                        ?>

                     <?php switch ($estatus) {
                        case 0:
                        case 2:
                        case 5:
                     ?>

                     <div class="course-overview clearfix">
                        <div class="course-tab-text" itemprop="abstract">
                        <h2 class="tab-title">{{$title}}</h2>
                           {!! $event->body !!}
                           <div class="author-infos">
                              <div class="row">
                                 @if(count($syllabus) > 0)
                                 <div class="col6 col-xs-12">
                                    <div id="syll" class="ibox">
                                       <div class="author-img">
                                          <?php
                                             $alt='';
                                             $img = get_image($syllabus[0]['mediable'],'instructors-small'); //$event->mediable->original_name;


                                             ?>
                                          <a id="syllabus-link" href=""><img src="{{cdn($img)}}" alt="{{$alt}}"></a>
                                       </div>
                                       <div class="ibox-text">


                                          <p>Syllabus Manager<br></p>
                                          <p>
                                             <a href="{{$syllabus[0]['slugable']['slug']}}">{{ $syllabus[0]['title'] }} {!! $syllabus[0]['subtitle'] !!}</a>
                                          </p>
                                       </div>
                                    </div>
                                 </div>
                                 @endif
                                 @if(!empty($partners))
                                 <div class="col6 col-xs-12">
                                    <div class="ibox">
                                       @foreach($partners as $vkey => $partner)
                                       <?php
                                          $alt=$partner->name;
                                          $img = get_image($partner['mediable']);



                                       ?>
                                       <div class="ibox-img">
                                          <a href="javascript:void(0)" title="{{$alt}}" target="_blank"><img src="{{cdn($img)}}" alt="{{$alt}}"></a>
                                       </div>
                                       <div class="ibox-text">
                                          {!! $partner->name !!}
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

                           <span class="completed">The event is completed</span>
                           <p style="display:none">The best digital &amp; social media diploma with a long track record trusted by top executives, agencies, brands and corporations is completed.</p>
                           <p >Please check all our @if(isset($location->name)) <a href="{{ $location->slug }}">upcoming events in {{ $location->name }}</a> @else upcoming events in this city @endif.</p>
                           {{--<p style="display:none">Please check all our @if(isset($location->name)) <a href="{{ $location->slug }}">upcoming events in {{ $location->name }}</a> @else upcoming events in this city @endif.</p>--}}

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


                           if(isset($sections['benefits'])){
                              $title = $sections['benefits']->first()->title;
                              $body = $sections['benefits']->first()->description;
                           }

                           ?>
                        <h2 class="text-align-center text-xs-left tab-title">{!!$title!!}</h2>
                        <h3>{!!$body!!}</h3>
                        <div class="benefits-list">
                           <div class="row-flex row-flex-17">

                              @foreach($benefits as $key => $benefit)
                                 @if($benefit['name'] != '')
                                 <div class="col-3 col-sm-6 col-xs-12">
                                    <div class="benefit-box">
                                       <div class="box-icon">
                                          <img class="replace-with-svg" src="{{cdn(get_image($benefit['medias']))}}" width="40" alt="">
                                       </div>
                                       <h3>{{ $benefit['name'] }}</h3>
                                       {!! $benefit['description'] !!}
                                       <!-- /.benefit-box -->
                                    </div>
                                 </div>
                                 @endif

                              @endforeach
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
                                  $title = '';
                                  $body = '';
                           if(isset($sections['topics'])){
                              $title = $sections['topics']->first()->title;
                              $body = $sections['topics']->first()->description;
                           }

                           ?>
                        <h2 class="text-align-center text-xs-left tab-title"> {!! $title !!}</h2>
                        <div class="topic-text-area">
                           {!! $body !!}
                        </div>
                        @if(isset($topics))
                        <div class="tab-faq-wrapper topic-content">
                           <div class="accordion-wrapper accordion-big">
                              @foreach($topics as $key => $topic)
                              <div class="accordion-item">
                                 <h3 class="accordion-title title-blue-gradient scroll-to-top">{!! $key !!}</h3>
                                 <div class="accordion-content">

                                 @foreach($topic['lessons'] as $lkey => $lesson)

                                    <div class="topic-wrapper-big">
                                       <div class="topic-title-meta">
                                          <h4 class="@if(isset($lesson['bold']) && $lesson['bold']) bold-topic @endif">{!! $lesson['title'] !!}</h4>


                                          <div class="topic-meta">
                                             @if(isset($lesson['type'][0]['name']))<div class="category">{{$lesson['type'][0]['name']}}</div>@endif

                                             <span class="meta-item duration"><img src="{{cdn('/theme/assets/images/icons/Times.svg')}}" width="12" alt="" />{{$lesson['vimeo_duration']}}</span>

                                          </div>
                                          <!-- /.topic-title-meta -->
                                       </div>
                                       <div class="author-img">

                                          <?php

                                             $instructor = reset($instructors[$lesson['instructor_id']]);

                                          ?>
                                          @if($instructor['status'])
                                             <a href="{{ $instructor['slugable']['slug']}}">
                                             <span class="custom-tooltip">{{ $instructor['title'] }} {{$instructor['subtitle']}}</span>
                                             <img alt="{{ $instructor['title']}} {{$instructor['subtitle']}}" src="{{ cdn(get_image($instructor['mediable'],'instructors-small')) }}"/>
                                             </a>
                                          @else
                                             <a class="non-pointer" href="javascript:void(0)">
                                                <span class="custom-tooltip">{{ $instructor['title'] }} {{$instructor['subtitle']}}</span>
                                                <img alt="{{ $instructor['title']}} {{$instructor['subtitle']}}" src="{{ cdn('') }}"/>
                                             </a>
                                             @endif
                                       </div>
                                       <!-- /.topic-wrapper -->
                                    </div>
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
                                  $title = '';
                                  $body = '';
                           if(isset($sections['instructors'])){
                              $title = $sections['instructors']->first()->title;
                              $body = $sections['instructors']->first()->description;
                           }

                           ?>
                        <h2 class="text-align-center text-xs-left tab-title">{!!$title!!}</h2>
                        <h3>{!!$body!!}</h3>
                        <div class="instructors-wrapper row row-flex row-flex-23">
                           @foreach($instructors as $instructor)
                           @foreach($instructor as $inst)


                           <?php

                              $socialMedia = json_decode($inst['social_media'],true);
                              $fb = isset($socialMedia['facebook']) ? $socialMedia['facebook'] : '';
                              $twitter = isset($socialMedia['twitter']) ? $socialMedia['twitter'] : '';
                              $instagram = isset($socialMedia['instagram']) ? $socialMedia['instagram'] : '';
                              $linkedIn = isset($socialMedia['linkedin']) ? $socialMedia['linkedin']: '';
                              $yt = isset($socialMedia['youtube']) ? $socialMedia['youtube'] : '';

                              $field2 = $inst['company'];
                              /*if(isset($inst['ext_url'])){

                                 $field2 = $inst['ext_url'];
                                 $field2 = str_replace ( "https://www.", "", $field2 );
                                 $field2 = str_replace ( "https://.", "", $field2 );
                                 $field2 = str_replace ( "http://www.", "", $field2 );
                                 $field2 = str_replace ( "https:", "", $field2 );
                                 $field2 = str_replace ( "http:", "", $field2 );
                                 $field2 = str_replace ( "/", "", $field2 );

                              }*/

                              ?>
                              <div class="col-3 col-md-4 col-sm-6 col-xs-12">
                                 <div class="instructor-box">
                                    <div class="instructor-inner">

                                       <div class="profile-img">
                                         @if($inst['status'])
                                             <a href="{{$inst['slugable']['slug']}}"><img src="{{cdn(get_image($inst['mediable'],'instructors-testimonials'))}}"  title="{{$inst['title']}}" alt="{{$inst['title']}}"></a>
                                         @else
                                             <img src="{{cdn(get_image($inst['mediable'],'instructors-testimonials'))}}"  title="{{$inst['title']}}" alt="{{$inst['title']}}">
                                         @endif
                                       </div>
                                       @if($inst['status'])
                                          <h3><a href="{{$inst['slugable']['slug']}}">{{$inst['title']}} {{$inst['subtitle']}}</a></h3>
                                       @else
                                          <h3>{{$inst['title']}}</h3>
                                       @endif
                                       <p>{{$inst['header']}}, @if($inst['ext_url'] != '')<a target="_blank" title="{{$inst['header']}}"  href="{{$inst['ext_url']}}"  > {{$field2}}</a>.@endif</p>
                                       <ul class="social-wrapper">
                                          @if($fb != '')
                                          <li><a target="_blank" href="{{$fb}}"><img class="replace-with-svg"  src="/theme/assets/images/icons/social/Facebook.svg" width="16" alt="Visit"></a></li>
                                          @endif
                                          @if($instagram !='')
                                          <li><a target="_blank" href="{{$instagram}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Instagram.svg')}}" width="16" alt="Visit"></a></li>
                                          @endif
                                          @if($linkedIn !='')
                                          <li><a target="_blank" href="{{$linkedIn}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Linkedin.svg')}}" width="16" alt="Visit"></a></li>
                                          @endif
                                          @if($twitter !='')
                                          <li><a target="_blank" href="{{$twitter}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Twitter.svg')}}" width="16" alt="Visit"></a></li>
                                          @endif
                                          @if($yt !='')
                                          <li><a target="_blank" href="{{$yt}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Youtube.svg')}}" width="16" alt="Visit"></a></li>
                                          @endif

                                       </ul>
                                       <!-- /.instructor-inner -->
                                    </div>
                                    <!-- /.instructor-box -->
                                 </div>
                                 <!-- /.col-3.col-sm-12 -->
                              </div>
                           @endforeach
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

               @if(count($testimonials) > 0)
               <?php
                        $title = '';
                        $body = '';
                  if(isset($sections['testimonials'])){
                     $title = $sections['testimonials']->first()->title;
                     $body = $sections['testimonials']->first()->description;
                  }

                  ?>
               <div id="testimonials" class="tab-content-wrapper tab-no-pad">
                  <div class="container">
                     <div class="course-full-text full-w-pad">
                        <h2 class="text-align-center text-xs-left tab-title">{!!$title!!}</h2>
                        <h3 class="text-align-center text-xs-left tab-title"> {!!$body!!} </h3>
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
                        <!-- /.course-full-text -->
                     </div>
                  </div>
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
														    "name": "Knowcrunch"
														  }
														}
													</script>

                              <!-- /.slide -->
                           </div>
                           @endforeach

                        </div>
                     </div>
                  </div>
                  <!-- /.tab-content-wrapper -->
               </div>



               <div id="faq" class="tab-content-wrapper">

                  <?php
                  $title = '';
                  $body = '';
                     if(isset($sections['questions'])){
                        $title = $sections['questions']->first()->title;
                        $body = $sections['questions']->first()->description;
                     }

                     ?>
                  <div class="container">
                     <div class="course-full-text">
                        <h2 class="text-align-center text-xs-left tab-title">{!!$title!!}</h2>
                        <h3 class="text-align-center text-xs-left tab-title">{!!$body!!}</h3>

                        @if (count($faqs) > 0)

                        <?php $f=[] ?>
                        @foreach ($faqs as $key => $row)

                        <h3 class="tab-sub-title">{!! $key !!}</h3>
                        <div class="tab-faq-wrapper multiple-accordions">
                           <div class="accordion-wrapper">


                              @foreach ($row as $qkey => $qna)
                              <?php $questions = [];?>

                              <div class="accordion-item">
                                 <h4 class="accordion-title title-blue-gradient scroll-to-top">{!! $qna['question'] !!}</h4>
                                 <div class="accordion-content">
                                    <div class="shorten-content">
                                    {!! $qna['answer'] !!}
                                    </div>
                                 </div>
                                 <!-- /.accordion-item -->
                              </div>


                              <?php
                                 $qq = [];
                                 $title = $qna['question'];
                                 $quest = $qna['answer'];

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

               </div>
               @endif

               <!-- /.tabs-content -->
            </div>
            <!-- /.tabs-wrapper -->
         </div>
         <!-- /.content-wrapper -->
      </div>
      <!-- /.section-course-tabs -->
   </section>
   @if(($estatus == 0 || $estatus == 2) && !$is_event_paid)
      @include('theme.event.partials.seats')
   @endif
</main>
@endsection
@section('scripts')

<script>
   /// set link to syllabus manager image
   let syllabusLink = $('.ibox-text').find('a').attr('href');
   document.getElementById('syllabus-link').setAttribute('href',syllabusLink);
</script>

<script src="{{cdn('new_cart/js/cart.js')}}" type="text/javascript" charset="utf-8" async defer></script>
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
}(document, 'script', 'facebook-jssdk'));</script>
<!-- Your customer chat code -->
<div class="fb-customerchat"
  attribution=install_email
  page_id="486868751386439">
</div>
@endif--}}

@if(isset($tigran) && !env('APP_DEBUG'))
<script>
   $(document).ready(function(){
	dataLayer.push({'Event_ID':"{{$tigran['Event_ID']}}v", 'event': 'ViewContent', 'Product_id' : "{{$tigran['Product_id']}}", 'Price': "{{$tigran['Price']}}",
                     'ProductCategory':"{{$tigran['ProductCategory']}}","product":"product","ProductName":$.parseHTML("{{ $tigran['ProductName'] }}")[0].data});
   })
</script>

<script>

   $(document).ready(function(){
      dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
      dataLayer.push({
        'ecommerce': {
          'detail': {
            'products': [{
               'name': $.parseHTML("{{ $tigran['ProductName'] }}")[0].data,
               'id': "{{$tigran['Product_id']}}",
               'price': "{{$tigran['Price']}}",
               'brand': 'Knowcrunch',
               'category': "{{$tigran['ProductCategory']}}",
             }]
           }
         }
      });
   })




</script>

@endif
@stop
