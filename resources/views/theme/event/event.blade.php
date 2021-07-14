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
                     <li><a href="#overview" class="active">Overview</a></li>
                     @if($estatus == 0 || $estatus == 2)

                     @if(count($benefits) > 0)<li><a href="#benefits">Benefits</a></li>@endif

                     <li><a href="#topics">Topics</a></li>
                     <li><a href="#instructors">Instructors</a></li>
                     <li><a href="#testimonials">Testimonials</a></li>
                     @if(count($venues) > 0)<li><a href="#location">Location</a></li>@endif
                     <li><a href="#faq">FAQ</a></li>
                     @elseif($estatus == 3 || $estatus == 1 )
                     <li><a href="#topics">Topics</a></li>
                     <li><a href="#instructors">Instructors</a></li>
                     @endif
                  </ul>

                  @if($estatus == 0 )
                  <a href="#seats" class="btn btn--lg btn--primary go-to-href">ENROLL NOW</a>
                  @elseif($estatus != 3 && $estatus != 1 )
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
                        case 2: ?>
                        <h2 class="tab-title">{{$title}}</h2>
                     <h3 > {!!$body!!} </h3>
                     <div class="course-overview clearfix">
                        <div class="course-tab-text" itemprop="abstract">
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
                                 {{--@if(isset($section_organisers))
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
                                 @endif--}}
                              </div>
                           </div>
                           <!-- /.course-overview-text -->
                        </div>
                        <div class="course-tab-sidebar">
                           <div class="course-details @if(!isset($section_fullvideo)) non-video-height @endif">
                              <ul class="two-column-list">
                              
                              @foreach($summary as $sum)
                                 @if($sum['title'])
                                 <li>
                                    @if($sum['medias'])<img class="info-icon" class="replace-with-svg" src="{{cdn(get_image($sum['medias']))}}" width="30" />@endif
                                    <div class="info-text">

                                       <p>{{  $sum['title'] }}</br>
                                          {{  $sum['description'] }}
                                       </p>
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
                     {{--@if($estatus == 0 || $estatus == 2)
                     @if(isset($section_fullvideo) && $section_fullvideo->body != '')
                     <div class="video-wrapper">
                        <div class="responsive-fb-video">
                           {!! $section_fullvideo->body !!}
                        </div>
                     </div>
                     @endif
                     @endif--}}
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

                              <?php  $category = 'freepresentations';  ;

                                 if (isset($benefits[0]) && $benefits[0]['name'] != '') : ?>
                              <div class="col-3 col-sm-6 col-xs-12">
                                 <div class="benefit-box">
                                    <div class="box-icon">
                                       <img class="replace-with-svg" src="{{cdn('theme/assets/images/icons/Access-Files.svg')}}" width="40" alt="">
                                    </div>
                                    <h3>{{ $benefits[0]['name'] }}</h3>
                                    {!! $benefits[0]['description'] !!}
                                    <!-- /.benefit-box -->
                                 </div>
                              </div>
                              <?php endif ?>
                              <?php $category = 'e-learning';  ;
                                 if (isset($benefits[1]) && $benefits[1]['name'] != '') :  ?>
                              <div class="col-3 col-sm-6 col-xs-12">
                                 <div class="benefit-box">
                                    <div class="box-icon">
                                       <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/E-Learning.svg')}}" width="40" alt="">
                                    </div>
                                    <h3>{{ $benefits[1]['name'] }}</h3>
                                    {!! $benefits[1]['description'] !!}
                                    <!-- /.benefit-box -->
                                 </div>
                              </div>
                              <?php endif ?>
                              <?php $category = 'support group';
                                 if (isset($benefits[2]) && $benefits[2]['name'] != '') :  ?>
                              <div class="col-3 col-sm-6 col-xs-12">
                                 <div class="benefit-box">
                                    <div class="box-icon">
                                       <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Support-Group.svg')}}" width="40" alt="">
                                    </div>
                                    <h3>{{ $benefits[2]['name'] }}</h3>
                                    {!! $benefits[2]['description'] !!}
                                    <!-- /.benefit-box -->
                                 </div>
                              </div>
                              <?php endif ?>
                              <?php $category = 'jobs access';
                                 if (isset($benefits[3]) && $benefits[3]['name'] != '') :  ?>
                              <div class="col-3 col-sm-6 col-xs-12">
                                 <div class="benefit-box">
                                    <div class="box-icon">
                                       <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Jobs_access.svg')}}" width="40" alt="">
                                    </div>
                                    <h3>{{ $benefits[3]['name'] }}</h3>
                                    {!! $benefits[3]['description'] !!}
                                    <!-- /.benefit-box -->
                                 </div>
                              </div>
                              <?php endif ?>
                              <?php $category = 'events access';
                                 if (isset($benefits[4]) && $benefits[4]['name'] != '') :  ?>
                              <div class="col-3 col-sm-6 col-xs-12">
                                 <div class="benefit-box">
                                    <div class="box-icon">
                                       <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Events_Access.svg')}}" width="40" alt="">
                                    </div>
                                    <h3>{{ $benefits[4]['name'] }}</h3>
                                    {!! $benefits[4]['description'] !!}
                                    <!-- /.benefit-box -->
                                 </div>
                              </div>
                              <?php endif ?>
                              <?php $category = 'free recaps';
                                 if (isset($benefits[5]) && $benefits[5]['name'] != '') :  ?>
                              <div class="col-3 col-sm-6 col-xs-12">
                                 <div class="benefit-box">
                                    <div class="box-icon">
                                       <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Recap-Events.svg')}}" width="40" alt="">
                                    </div>
                                    <h3>{{ $benefits[5]['name'] }}</h3>
                                    {!! $benefits[5]['description'] !!}
                                    <!-- /.benefit-box -->
                                 </div>
                              </div>
                              <?php endif ?>

                              <?php $category = 'projects info';

                                 if (isset($benefits[6]) && $benefits[6]['name'] != '') :  ?>
                              <div class="col-3 col-sm-6 col-xs-12">
                                 <div class="benefit-box">
                                    <div class="box-icon">
                                       <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Customer_Access.svg')}}" width="40" alt="">
                                    </div>
                                    <h3>{{ $benefits[6]['name'] }}</h3>
                                    {!! $benefits[6]['description'] !!}
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
                                             @if(isset($lesson['pivot']['type']) && $lesson['pivot']['type'])<div class="category">{{$lesson['pivot']['type']}}</div>@endif
                                             <?php  
                                                $date = $lesson['pivot']['time_starts'] ? date('l d F Y',strtotime($lesson['pivot']['time_starts'])) : null;
                                                $time =  $lesson['pivot']['time_starts'] ? date('H:i',strtotime($lesson['pivot']['time_starts'])) : null;

                                             ?>
                                             @if($date)<span class="meta-item duration"><img src="{{cdn('/theme/assets/images/icons/icon-calendar.svg')}}" width="12" alt="" />{{$date}}</span>@endif
                                             @if($time)<span class="meta-item duration"><img src="{{cdn('/theme/assets/images/icons/Times.svg')}}" width="12" alt="" />{{$time}} ({{$lesson['pivot']['duration']}})</span>@endif
                                             <span class="meta-item duration"><img src="{{cdn('/theme/assets/images/icons/icon-marker.svg')}}" width="12" alt="" />{{$lesson['pivot']['room']}}</span>

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
                           <div class="col-3 col-md-4 col-sm-6 col-xs-12">
                              <div class="instructor-box">
                                 <div class="instructor-inner">

                                    <div class="profile-img">
                                      @if($inst['status'])
                                          <a href="{{$inst['slugable']['slug']}}"><img src="{{cdn(get_image($inst['mediable'],'instructors-testimonials'))}}"  title="{{$inst['title']}}" alt="{{$inst['title']}}"></a>
                                      @else
                                          <img src="{{cdn('$img')}}"  title="{{$inst['title']}}" alt="{{$inst['title']}}">
                                      @endif
                                    </div>
                                    @if($inst['status'])
                                       <h3><a href="{{$inst['slugable']['slug']}}">{{$inst['title']}}</a></h3>
                                    @else
                                       <h3>{{$inst['title']}}</h3>
                                    @endif
                                    <p>{{$inst['header']}}, <a target="_blank" title="{{$inst['header']}}" @if($inst['ext_url'] != '') href="{{$inst['ext_url']}}" @endif > test</a>.</p>
                                    {{--<ul class="social-wrapper">
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
                                    </ul>--}}
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
                                       <img src="{{ cdn(get_image($row['mediable'],'instructors-small')) }}" alt="{!! $row['name'] !!}">
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
                  <!-- /.tab-content-wrapper -->
               </div>
               @endif
               
               @if(count($venues))
               <div id="location" class="tab-content-wrapper ">
                  <div class="container">
                     <div class="course-full-text">
                        
                        <?php
                                  $title = '';
                                  $body = '';
                           if(isset($sections['location'])){
                              $title = $sections['location']->first()->title;
                              $body = $sections['location']->first()->description;
                           }

                           ?>
                        <h2 class="text-align-center text-xs-left tab-title">{!!$title!!}</h2>
                        <h3>{!!$body!!}</h3>
                        @foreach($venues as $vkey => $venue)
                        
                        <div class="location-text">
                           <h3>{!! $venue['name'] !!} </h3>
                           <p>{!! $venue['address'] !!}<br/><br/><br/>{!!$venue['direction_description']!!}</p>
                        </div>
                        <div class="location-map-wrapper">
                           <iframe  src="https://maps.google.com/maps?q={!! $venue['name'] !!}&z=17&output=embed" width="1144" height="556" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                        </div>
                        @endforeach
                     </div>
                  </div>
               </div>
               @endif
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


               <!-- /.tabs-content -->
            </div>
            <!-- /.tabs-wrapper -->
         </div>
         <!-- /.content-wrapper -->
      </div>
      <!-- /.section-course-tabs -->
   </section>

   @if($estatus == 0 || $estatus == 2)
   @include('theme.event.partials.seats')
   @endif
</main>
@endsection
@section('scripts')
{{--
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
@endif--}}
@stop