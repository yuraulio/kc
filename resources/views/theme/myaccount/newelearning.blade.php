<?php
   use Illuminate\Support\Str;
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <base href="{!! URL::to('/') !!}/" target="_self" />
      <meta charset="UTF-8" />
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      {{--
      <link href="{{ cdn(elixir('theme/assets/css/style_ver.css')) }}" rel="stylesheet" media="all" />
      --}}
      {{--
      <link rel="stylesheet" href="{{ cdn('theme/assets/css/old.css') }}" />
      --}}
      {{--
      <link rel="stylesheet" href="https://use.typekit.net/pfo3bjs.css">
      --}}
      <link rel="stylesheet" href="{{ cdn(mix('theme/assets/css/new/pop_up.css')) }}" />
      <link rel="stylesheet" href="{{ cdn(mix('theme/assets/css/new/burger.css')) }}" />
      <link rel="stylesheet" href="{{ cdn(mix('theme/assets/css/new/normalize.css')) }}" />
      <link rel="stylesheet" href="{{ cdn(mix('theme/assets/css/new/core.css')) }}" />
      <title>{{$course}}</title>

      @include('theme.layouts.favicons')

      <script src="https://kit.fontawesome.com/84bbd74d3a.js" crossorigin="anonymous"></script>

      @if(!env('APP_DEBUG'))
         {{-- Google Tag Manager --}}
         <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
         new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
         j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
         'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
         })(window,document,'script','dataLayer','GTM-ML7649C');
         </script>
         {{-- End Google Tag Manager --}}
      @elseif(config('app.env') == "development")
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
          new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
          j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
          'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
          })(window,document,'script','dataLayer','GTM-MLLXRGTK');</script>
      @endif
   </head>
   <?php

      $notesss = json_decode($notes,true);
      //dd($notesss);
      //$notesss = (array) $notesss;
      //dd($lastVideoSeen);

      ?>
   <?php $bonusFiles = ['_Bonus', 'Bonus', 'Bonus Files', 'Βonus', '_Βonus', 'Βonus', 'Βonus Files'] ?>
   <body onload="tabclick('{{ $videos }}', '{{$details['id']}}', '{{$lastVideoSeen}}', '{{$event_statistic_id}}','{{$course}}', '{{$videos_progress}}')">
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
               <p>Exams for this course are now active. You can take your exams anytime. We suggest you do so after watching all the videos.</p>
               <a id="close-exam-dialog" href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
            </div>
            <button class="btn btn-del btn--sm go-to-account">Go to my account </button>
            <!-- /.alert-outer -->
         </div>
      </div>
      <div class="container">
         <div class="content-wrapper">
            <div class="sidebar-wrapper open jsSidebar">
               <header class="sidebar-header">
                  <button title="Close menu" class="hamburger hamburger--spin is-activee" type="button">
                  <span class="hamburger-box">
                  <span class="hamburger-inner"></span>
                  </span>
                  </button>
                  <a tabindex="0" href="/myaccount" title="Back to my courses">
                  <img
                     loading="lazy"
                     id="logo"
                     src="/theme/assets/img/new/logo-knowcrunch-seminars.svg"
                     alt="knowcruch logo"
                     title="knowcruch logo"
                     width="120"
                     height="40"
                     /></a>

               </header>
               <!-- ./sidebar-header -->
               <div class="searchbar-wrapper">
                  <form class="searchbar" action="javascript:void(0);">
                     <label for="search">Search: </label>
                     <input
                        class="jsSearchInput"
                        type="text"
                        id="search"
                        placeholder="SEARCH"
                        name="search"
                        />
                     <img loading="lazy" id="jsSearchButton" src="theme/assets/img/new/search-icon.svg" width="25" height="25" alt="search" title="Search" />
                  </form>
               </div>
               <!-- ./searchbar-wrapper -->
               <div class="sidebar-content">
                  <nav>
                     <ul id="{{$details['id']}}" class="topics-list">
                        <?php
                           $catId = -1;
                           $firstTab =true;
                           $firstLesson =true;
                           $count = 1;
                           ?>
                        <?php $count1 = 0; ?>
                        <?php
                           $video_seen = json_decode($videos, true);
                           //$notes = json_decode($notes, true);
                           //dd($video_seen);
                           //dd($video_seen);

                           $videoss = json_decode($videos,true);

                           ?>
                        @foreach($topics['topics'] as $keyTopic => $topic)
                        <?php
                           if($count == 1)
                              // $count = 2;
                           ?>
                        <li class="topic @if($topic['topic_seen']) seen @endif" data-count="{{$count}}">
                           <?php $count++; ?>
                           <a href="javascript:void(0)" tabindex="0" class="topic-header">

                              <div class="topic-info">

                                 <h3
                                    class="topic-info_title"
                                    data-title="{!! $keyTopic !!}"
                                    data-topic-slug = "{{\Illuminate\Support\Str::slug(preg_replace('/[0-9]+/', '', $keyTopic))}}"
                                    >

                                    {!! $keyTopic !!}
                                 </h3>

                                 <span class="topic-info_duration">
                                 <?php
                                    $m = isset($topic['topic_duration']) ?  floor(($topic['topic_duration'] / 60) % 60) : 0;
                                    $h =isset($topic['topic_duration']) ? $hours = floor($topic['topic_duration'] / 3600) : 0;

                                    echo intval($h) . 'h ' . $m . 'm';
                                    ?>

                                 </span>
                                 @if($topic['topic_seen'])
                                    <span class="white-separator"> |<span>
                                       <img loading="lazy" class="topic-progress" src="{{cdn('/theme/assets/img/new/completed_lesson_icon.svg')}}" title="completed_lesson_icon" alt="completed_lesson_icon">

                                 @endif
                              </div>
                              <!-- ./topic-info -->
                              <img
                                 loading="lazy"
                                 class="topic-open jsTopicOpen"
                                 src="theme/assets/img/new/arrow-down.svg"
                                 alt="open topic"
                                 width="32"
                                 height="32"
                                 title="open topic"
                                 />
                              <img
                                 loading="lazy"
                                 class="topic-close jsTopicClose"
                                 src="theme/assets/img/new/arrow-up.svg"
                                 alt="close topic"
                                 width="32"
                                 height="32"
                                 title="clos topic"
                                 />
                           </a>
                           <?php
                              $frame = $course;
                              $frame = str_replace(' ','_',$frame);
                              $frame = str_replace('-','',$frame);
                              $frame = str_replace('&','',$frame);
                              $frame = str_replace('_','',$frame);
                              $frame = str_replace('(','',$frame);
                              $frame = str_replace(')','',$frame);

                              $frame2 = '{'.$frame.'}';
                              $frame = $frame;



                              ?>
                           <!-- ./topic-header -->
                           <ul class="lessons-list">

                              @foreach($topic['lessons'] as $keyLesso => $lesson)
                              <?php
                                  $vimeoVideo = explode('https://vimeo.com/', $lesson['vimeo_video']);
                                  if(!isset($vimeoVideo[1])){
                                    continue;
                                  }

                                  $frame1 = '';
                                  $path = $lesson['vimeo_video'];
                                  $path = str_replace('https://vimeo.com/','https://player.vimeo.com/video/',$path);

                                  if(isset($vimeoVideo[1])&& isset($videoss[$vimeoVideo[1]])){
                                    $frame1 = $videoss[$vimeoVideo[1]]['tab'];
                                  }

                                  if(isset($vimeoVideo[1])){
                                    $vimeoId = str_replace('?title=false','',$path);
                                    $vimeoId = str_replace('https://player.vimeo.com/video/','',$vimeoVideo[1]);
                                  }

                                ?>

                              <li class="lesson {{$vimeoVideo[1]}} {{$lesson['id']}}" data-vimeoid="{{$vimeoVideo[1]}}" data-completed="{{isset($video_seen[$vimeoVideo[1]]) ? $video_seen[$vimeoVideo[1]]['seen'] : 0}}" data-link="{{$lesson['links']}}" id="{{$frame1}}">

                                <a style="flex-wrap:wrap;" class="" href="javascript:void(0)" onclick="play_video('{{$path}}','{{$frame1}}','{video{{$lesson['id']}}}', '{{$lesson['id']}}')" tabindex="0">

                                 <!-- Left wapper -->
                                  <div style="flex: 70%;" class="lesson-info">
                                    <h3
                                       class="lesson-info_title @if($lesson['bold'])bold-topic @endif"
                                       data-title="{!! $lesson['title'] !!}"
                                       >
                                       {!! $lesson['title'] !!}
                                    </h3>



                                  </div>

                                  <!-- right wrapper -->
                                 <div style="flex:0; margin:auto;" class="lesson-teacher-wrapper">
                                       <?php
                                        $instructor = $topics['instructors'][$lesson['instructor_id']][0];
                                        $imageDetails = get_image_version_details('instructors-small');
                                       ?>
                                       <img
                                          loading="lazy"
                                          class="lesson-teacher"
                                          src="{{cdn(get_image($instructor['mediable'],'instructors-small'))}}"
                                          alt="{{$instructor['title']}} {{$instructor['subtitle']}}"
                                          title="{{$instructor['title']}} {{$instructor['subtitle']}}"
                                          data-slug="{{$instructor['slugable']['slug']}}"
                                          width="{{ $imageDetails['w'] }}"
                                          height="{{ $imageDetails['h'] }}"
                                          />
                                 </div>


                                    <div class="lesson-info 1">

                                       <span class="lesson-info_duration">{{$lesson['vimeo_duration']}}</span>
                                       <span class="white-separator"> | </span>
                                       @if(isset($lesson['type'][0]['name']))<span class="lesson-info_topic-type">{{$lesson['type'][0]['name']}}</span><span class="white-separator"> | </span>@endif

                                       <span>
                                       <img
                                       class="lesson-progress"
                                       src="
                                       <?php
                                          if($video_seen[$vimeoVideo[1]]['seen'] == 1){
                                            echo 'theme/assets/img/new/completed_lesson_icon.svg';
                                          }else{
                                            echo 'theme/assets/img/new/lesson_icon.svg';
                                          }
                                          ?>
                                       "
                                       alt="lesson progress"
                                       />
                                       </span>


                                       <?php
                                          if($video_seen[$vimeoVideo[1]]['is_new'] == 1)
                                          {
                                             echo '<span class="newLesson notification-lesson-inside"><img src="/theme/assets/images/notification-dot.png"></img></span>';


                                             }
                                          ?>


                                    </div>
                                    <!-- ./lesson-info -->

                                    <!-- ./lesson-teacher-wrapper -->
                                 </a>
                              </li>
                              @endforeach
                           </ul>
                        </li>
                        @endforeach
                     </ul>
                  </nav>
               </div>
               <!-- ./sidebar-content -->
            </div>
            <!-- ./sidebar-wrapper -->
            <div class="lesson-wrapper jsLesson">
               <header class="lesson-header">
                  <button title="Open menu" class="show-sidebar hamburger hamburger--spin" type="button">
                     <span class="hamburger-box">
                     <span class="hamburger-inner"></span>
                     </span>
                  </button>

                  {{--

                     <a tabindex="0" class="show-sidebar jsShowSidebar" href="#">
                        <img
                           class="jsGreenOnHover"
                           src="/theme/assets/img/new/arrows.svg"
                           alt="show sidebar"
                           />
                     </a>

                  --}}

                  <img
                     loading="lazy"
                     id="second-logo"
                     src="/theme/assets/img/new/logo-knowcrunch-seminars.svg"
                     alt="knowcruch logo"
                     title="Back to my courses"
                     width="120"
                     height="40"
                     />

                  <h1 class="lesson-header-title">
                     {{$course}}
                  </h1>
                  <div class="progress-bar-wrapper">
                     <div class="progress-bar"></div>
                     <!-- ./progress-bar -->
                  </div>
                  <!-- ./progress-bar-wrapper -->
                  <div class="section-account-tabs">
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
                  </div>
               </header>
               <!-- ./lesson-header -->
               <div id="{{$lesson['event_id']}}" class="video-wrapper">
                  <div id='{{$frame2}}'>
                     <iframe
                        aria-label="lesson video"
                        src='https://player.vimeo.com/video/{{$lastVideoSeen}}?color=efc900&title=0&byline=0&autoplay=1&loop=0&autopause=0'
                        style="
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        "
                        allow="fullscreen; picture-in-picture"
                        allowfullscreen
                        ></iframe>
                  </div>
               </div>
               <!-- ./video-wrapper -->
               <div class="lesson-main">
                  <div class="lesson-main-title-wrapper">
                     <h2 class="lesson-main-title">{!! $lesson['title'] !!}</h2>
                     <!--<div class="lesson-main-info">
                        <img src="theme/assets/img/new/info-information.svg" alt="lesson information" />
                        <span>Live Tutorial</span>
                        </div>-->
                     <!-- ./lesson-main-info -->
                  </div>
                  <!-- ./lesson-main-title-wrapper -->
                  <div class="lesson-controls">
                     <div class="lesson-controls-left">
                        <a
                           tabindex="0"
                           href="#"
                           class="tab-button resources-button jsOpenResourcesTab active"
                           >
                           <div tabindex="-1" class="inner-tab-button">
                              <img loading="lazy" src="theme/assets/img/new/resources-icon.svg" alt="resources" width="27" height="27" title="resources" />
                              <span>Resources</span>
                           </div>
                        </a>
                        <!-- ./resources-button -->
                        <a
                           tabindex="0"
                           href="#"
                           class="tab-button notes-button jsOpenNotesTab"
                           >
                           <div tabindex="-1" class="inner-tab-button">
                              <img loading="lazy" src="theme/assets/img/new/notes-icon.svg" alt="notes" width="27" height="27" title="notes" />
                              <span>Notes</span>
                           </div>
                        </a>
                        <!-- ./notes-button -->
                     </div>
                     <!-- ./lesson-controls-left -->
                     <div class="lesson-controls-right">
                        <a
                           onclick="prevVideo()"
                           href="javascript:void(0)"
                           tabindex="0"
                           class="change-lesson-button previous-video-button"
                           >
                           <div tabindex="-1" class="inner-change-lesson-button">
                              <img loading="lazy" src="theme/assets/img/new/previous_video_icon.svg" alt="previous video" width="40" height="40" title="previous video"/>
                              <span>Previous video</span>
                           </div>
                        </a>
                        <!-- ./previous-video-button -->
                        <a
                           onclick="nextVideo()"
                           href="javascript:void(0)"
                           tabindex="0"
                           class="change-lesson-button next-video-button"
                           >
                           <div tabindex="-1" class="inner-change-lesson-button">
                              <span>Next video</span>
                              <img loading="lazy" src="theme/assets/img/new/next_video_icon.svg" alt="next video" width="40" height="40" title="next video"/>
                           </div>
                        </a>
                        <!-- ./next-video-button -->
                        <div class="share-container">
                           <a href="javascript:void(0)" tabindex="0" class="share-lesson-button">
                           <img src="theme/assets/img/new/share-arrow-square.svg" alt="share lesson" />
                           </a>
                           <div class="share-options">
                              <ul>
                                 <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= URL('/').'/'. $details['slug'] .'?utm_source=Facebook&utm_medium=User_Share&utm_campaign=DIGITAL_DIPLOMA_ELEARNING_GR'; ?>&quote=<?= 'Παρακολουθώ το '. urlencode($course);?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on Facebook">
                                    <img tabindex="0" src="theme/assets/img/new/facebook1.svg" alt="facebook"
                                       />
                                    </a>
                                    <!-- <a href="#"
                                       ><img tabindex="0" src="theme/assets/img/new/facebook.svg" alt="facebook"
                                       /></a> -->
                                 </li>
                                 <li>
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?=URL('/').'/?utm_source=LinkedIn&utm_medium=User_Share&utm_campaign=DIGITAL_DIPLOMA_ELEARNING_GR'; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on Linkedin"><img tabindex="0" src="theme/assets/img/new/linkedin1.svg" alt="linkedin"
                                       /></a>
                                 </li>
                                 <li>
                                    <a href="https://twitter.com/share?url=<?= URL('/').'/'. $details['slug'].'?utm_source=Twitter&utm_medium=User_Share&utm_campaign=DIGITAL_DIPLOMA_ELEARNING_GR'; ?>&text=<?= 'Παρακολουθώ το '. urlencode($course);?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on Twitter"><img tabindex="0" src="theme/assets/img/new/twitter1.svg" alt="twitter"
                                       /></a>
                                 </li>
                              </ul>
                           </div>
                           <!-- ./share-options -->
                        </div>
                        <!-- ./share-container -->
                     </div>
                     <!-- ./lesson-controls-right -->
                  </div>
                  <!-- ./lesson-controls -->
                  <div class="lesson-misc-wrapper">
                     <div class="lesson-misc resources active">
                        <!--<h3 class="lesson-misc-title">Lesson resources</h3>-->
                        <div class="lesson-resources">

                           @if(isset($files) && count($files) > 0 && isset($files['folders'][0]))
                           <?php
                                if(isset($files['pivot']) && isset($files['pivot']['selectedFolders'])){
                                    $selectedFiles = $files['pivot']['selectedFolders'];
                                    $selectedFiles = json_decode($selectedFiles, true);
                                }

                              $folders = $files['folders'][0];
                              if(isset($files['folders'][1])){
                                  $folders_bonus = $files['folders'][1];
                              }

                              if(isset($files['files'][2])){
                                 $files_bonus = $files['files'][2];
                              }
                              $files = $files['files'][1];



                           ?>
                           <div class="lesson-downloads">
                              <!--<h4 class="resource-list-title">Downloads</h4>-->
                              <?php //dd($folders) ?>
                              <ul class="resource-list">
                                 <?php $topicNames = []; ?>
                                 @if(isset($selectedFiles))
                                 @foreach($folders as $key => $folder)
                                    <?php
                                        $folderIsSelected = false;

                                        if($selectedFiles['selectedAllFolders']){
                                            $folderIsSelected = true;
                                        }else{
                                            foreach($selectedFiles['selectedFolders'] as $key10 => $selectedFile){
                                                if($folder['dirname'] == $selectedFile){
                                                    $folderIsSelected = true;
                                                }
                                            }
                                        }

                                       $topic_name = preg_replace('/[0-9]+/', '', $folder['foldername']);
                                       $topic_name = Str::slug($topic_name, '-');
                                       //dd($topic_name);

                                       $id = explode('/',$folder['dirname']);
                                       //dd($id[2]);
                                       $str=substr($id[2], 0, strrpos($id[2], '-'));
                                       $str = intval($str);
                                       //dd($str);

                                       $topicNames[$folder['id']] = $topic_name;
                                    ?>
                                    @foreach($files as $key11 => $file)


                                       @if($folder['id'] == $file['fid'])
                                            @if($folderIsSelected)
                                                <li id="{{$folder['dirname']}}" data-folder-id="{{$topic_name}}" class="resource hidden">
                                                    <a class="download-file getdropboxlink"  data-dirname="{{ $file['dirname'] }}" data-filename="{{ $file['filename'] }}" href="javascript:void(0)" ><img
                                                        loading="lazy"
                                                        src="/theme/assets/images/icons/Access-Files.svg"
                                                        alt="download resource"
                                                        width="20"
                                                        height="20"
                                                        title="download resource" />{{ $file['filename'] }}</a
                                                        >
                                                    <span class="last-modified">Last modified:  {{$file['last_mod']}}</span>
                                                </li>
                                            @else
                                                @foreach($selectedFiles['selectedFolders'] as $key10 => $selectedFile)
                                                    @if($file['dirname'] == $selectedFile)
                                                        <li id="{{$folder['dirname']}}" data-folder-id="{{$topic_name}}" class="resource hidden">
                                                            <a class="download-file getdropboxlink"  data-dirname="{{ $file['dirname'] }}" data-filename="{{ $file['filename'] }}" href="javascript:void(0)" ><img
                                                                loading="lazy"
                                                                src="/theme/assets/images/icons/Access-Files.svg"
                                                                alt="download resource"
                                                                width="20"
                                                                height="20"
                                                                title="download resource" />{{ $file['filename'] }}</a
                                                                >
                                                            <span class="last-modified">Last modified:  {{$file['last_mod']}}</span>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @endif
                                       @endif
                                    @endforeach
                                 @endforeach
                                 @endif
                                 @if(isset($selectedFiles))
                                 @if(isset($files_bonus) && count($files_bonus) > 0 && isset($files) && count($files) > 0)

                                    @foreach($folders as $folder)

                                       @foreach($folders_bonus as $folder_bonus)


                                          @if($folder_bonus['parent'] == $folder['id'])


                                            <?php $folderIsSelected = false; ?>

                                            @if($selectedFiles['selectedAllFolders'])
                                                <?php $folderIsSelected = true; ?>
                                            @else
                                                @foreach($selectedFiles['selectedFolders'] as $key10 => $selectedFile)
                                                    @if($folder_bonus['dirname'] == $selectedFile)
                                                        <?php $folderIsSelected = true; ?>
                                                    @endif
                                                @endforeach
                                            @endif

                                             @foreach($files_bonus as $file_bonus)

                                                @if($file_bonus['fid'] == $folder_bonus['id']  && $file_bonus['parent'] == $folder['id'])

                                                    @if($folderIsSelected)
                                                        <li id="{{$folder_bonus['dirname']}}" data-folder-id="{{$topicNames[$folder_bonus['parent']]}}" class="resource bonus-files hidden">
                                                            <a class="download-file getdropboxlink"  data-dirname="{{ $file_bonus['dirname'] }}" data-filename="{{ $file_bonus['filename'] }}" href="javascript:void(0)" ><img
                                                                loading="lazy"
                                                                src="/theme/assets/images/icons/Access-Files.svg"
                                                                alt="download resource"
                                                                width="20"
                                                                height="20"
                                                                title="download resource" />{{ $file_bonus['filename'] }}</a>
                                                            <span class="last-modified">Last modified:  {{$file_bonus['last_mod']}}</span>
                                                        </li>
                                                    @else

                                                        @foreach($selectedFiles['selectedFolders'] as $key10 => $selectedFile)

                                                            @if($file_bonus['dirname'] == $selectedFile)

                                                                <li id="{{$folder_bonus['dirname']}}" data-folder-id="{{$topicNames[$folder_bonus['parent']]}}" class="resource bonus-files hidden">
                                                                    <a class="download-file getdropboxlink"  data-dirname="{{ $file_bonus['dirname'] }}" data-filename="{{ $file_bonus['filename'] }}" href="javascript:void(0)" ><img
                                                                        loading="lazy"
                                                                        src="/theme/assets/images/icons/Access-Files.svg"
                                                                        alt="download resource"
                                                                        width="20"
                                                                        height="20"
                                                                        title="download resource" />{{ $file_bonus['filename'] }}</a>
                                                                    <span class="last-modified">Last modified:  {{$file_bonus['last_mod']}}</span>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endif
                                             @endforeach
                                          @endif
                                       @endforeach

                                    @endforeach
                                 @endif
                                 @endif
                              </ul>
                           </div>
                           @endif

                           <!-- ./lesson-downloads -->
                           <div class="lesson-links">
                              <!--<h4 class="resource-list-title">Links</h4>!-->
                              <ul id="links" class="resource-list">
                              </ul>
                           </div>
                           <!-- ./lesson-links -->
                        </div>
                        <!-- ./lesson-resources -->
                     </div>
                     <!-- ./lesson-misc -->


                     <div class="lesson-misc notes">
                        <!-- <h3 class="lesson-misc-title">Lesson notes</h3> -->
                        <div class="lesson-notes">
                           <label for="notes">Your notes: </label>

                           <textarea
                            data-vimeoid=""
                              name="notes"
                              id="notes"
                              placeholder="My notes for this lesson"
                              rows="10"
                              ></textarea>
                           <div class="lesson-notes_status">
                              {{--<a
                                 tabindex="0"
                                 href="javascript:void(0)"
                                 class="tab-button save-button jsOpenNotesTab"
                                 >
                                 <div tabindex="-1" style="text-align: end;" class="inner-tab-button">
                                    <img loading="lazy" src="theme/assets/img/new/floppy-save-new.svg" width="30" height="20" alt="notes" title="notes"/>
                                    <!-- <span>Notes</span> -->
                                 </div>
                              </a>
                              --}}
                              <div class="status saveDone">Your notes are <span>saved.</span></div>
                           </div>
                        </div>
                        <!-- ./lesson-notes -->
                     </div>
                     <!-- ./lesson-misc -->
                  </div>
                  <!-- ./lesson-misc -->
               </div>
               <!-- ./lesson-main -->
               <footer class="lesson-footer">
                  <div class="lesson-footer-links">
                     <a href="/">Home</a>
                     <a href="/myaccount">Account</a>
                     {{--<a href="/">Video e-learning courses</a>--}}
                  </div>
                  <!-- ./lesson-footer-links -->
                  <span class="copyright">Knowcrunch Inc. © {{date('Y')}}</span>
               </footer>
               <!-- ./lesson-footer -->
            </div>
            <!-- ./lesson-wrapper -->
         </div>
         <!-- ./content-wrapper -->
      </div>
      <!-- ./container -->
      <script
         src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
         integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
         crossorigin="anonymous"
         ></script>
      <script
         src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
         integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
         crossorigin="anonymous"
         ></script>
      <script src="{{ cdn(mix('theme/assets/js/new_js1/app1.js')) }}"></script>
      <script src="{{ cdn(mix('theme/assets/js/new_js1/burger.js')) }}"></script>
      <script src="https://player.vimeo.com/api/player.js"></script>
      <script>
         $( ".lesson-info_title" ).click(function() {
           let title = $(this).text();
           let vimeoId = $(this).parent().parent().parent()[0]
           vimeoId = $(vimeoId).data('vimeoid')
           //console.log(vimeoId);
           $('.lesson-main-title').text(title);
           $('#notes').attr('data-vimeoid', videoId)
         });



      </script>
      <script></script>
      <script>
         var prev_topicId = [];

         let notes = @json($notesss);

         var note;

         var noteFocus = false;

          var event = false;
          var videos = false;
          var videosSeen = [];
          var lastVideoSeen = -1
          var previousVideo = false;
          var previousK=false;
          var videoId;
          var eventStatistic;
          var playVi = false;
          var frame = false;
          var frameVi = [];
          var videoPlayers = [];
          var tabWatching = false;
          var previousFrame = false;
          var videosPlayed = [];
          var nextWatchingVideo;
          var currentWatchingVideo;
          let courseName;
          let timeoutID = null;

          function saveNoteAjax(){

            let event = $('.topics-list').attr('id');

            $.ajax({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                type: 'PUT',
                url: '/elearning/saveNote',
                data:{'text':JSON.stringify(notes), 'event':event},
                success: function(data) {
                    if(data){
                        $('.saveDone').fadeIn();


                        setTimeout(function() {
                            $('.saveDone').fadeOut(4000);
                        }, 1000); // <-- time in milliseconds
                    }else{
                    }
                    //playVi = true;

                }
            });
          }

          $('#notes').bind( "input", function(e) {

            notes[$(this).attr('data-vimeoid')] = $('#notes').val()

            if(e){
                clearTimeout(timeoutID);
                timeoutID = setTimeout(() => {

                    saveNoteAjax()

                }, 2000);
            }

            });

          function prevVideo(){
            let currTopic;
            let prev;

            this.currentWatchingVideo = $( ".isWatching" ).attr('id');
            prev = $('#'+ this.currentWatchingVideo).prev().attr('id');

            $("#" + prev +" a:first-child").trigger("click");
            prevTitle = $('#'+ this.currentWatchingVideo).prev().find('.lesson-info_title').text();
            $('.lesson-main-title').text(prevTitle);

            if(typeof prev === 'undefined'){
               currTopic = $('#'+ this.currentWatchingVideo).parent().parent();
               let prevTopic = currTopic.prev();
               prev = $(prevTopic).find('li:last-child');
               prev = $(prev).find('a:first-child');
               prevTitle = $(prevTopic).find('li:last-child').find('.lesson-info_title').text();
               $('.lesson-main-title').text(prevTitle);
                   $(prev[0]).trigger("click");
               }
               //TES
               if($( ".isWatching" ).hasClass('watched')){
                 $('.isWatching').find('.lesson-progress').attr('src','theme/assets/img/new/completed_lesson_icon.svg')
               }
          }


          function nextVideo(){
             let next;
             let nextTitle;

             this.currentWatchingVideo = $( ".isWatching" ).attr('id');

             next = $( "#" + this.currentWatchingVideo ).next().attr('id');
             nextTitle = $( "#" + this.currentWatchingVideo ).next().find('.lesson-info_title').text();
             //update title
             $('.lesson-main-title').text(nextTitle);



            if(typeof next === 'undefined'){
               let currTopic = $('#'+ this.currentWatchingVideo).parent().parent();
               let nextTopic = currTopic.next();

               if($(nextTopic).next()['length'] == 0){
                 curTitle = $("#"+this.currentWatchingVideo).find('.lesson-info_title').text();
                 $('.lesson-main-title').text(curTitle);
               }else{
                 next = $(nextTopic).find('li')[0]['id'];
                 nextTitle = $(nextTopic).find('li')[0];
                 nextTitle = $(nextTitle).find('.lesson-info_title').text();
                 //update title
                 $('.lesson-main-title').text(nextTitle);
               }
            }
            $("#" + next +" a:first-child").trigger("click");
          }

           function viewDownloads(){
               $('.lesson-downloads ul li').addClass('hidden')
               topicId = $('.isWatching').parent().parent().data('count') - 1
               $.each($('.lesson-downloads li'), function(key,value){

                   if($(value).data('count') == topicId){
                       $(value).removeClass('hidden')
                   }
               })
           }

          function tabclick(videos,event,seen,statisticId,frame,progress){



            $('.progress-bar').css('width', progress + '%')

               //notes = JSON.parse(notes)

             videos = JSON.parse(videos)

             frame = frame.replace(/\s/g, '')
             frame = frame.replace(/-/g, '')
             frame = frame.replace(/&/g, '')
             frame = frame.replace(/\(/g, '')
             frame = frame.replace(/\)/g, '')
             frame = '{'+frame+'}'

             if(previousFrame){

                if(playVi){
                   videoPlayers[previousFrame].pause().then(function() {


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

             }else{
                playVi = false;
             }

             this.event = event;
             this.lastVideoSeen = seen
             this.eventStatistic = statisticId
             this.frame = frame
             previousFrame = frame;


             if(frame in this.videosSeen == false){
                this.videos = videos;
             }else{

               videos = videosSeen[frame];
             }

             if(frame in this.frameVi == false){
                this.frameVi[frame] = frame;
                this.videoPlayers[frame] = '';
                this.videosPlayed[frame] = [];
             }else{
              //  this.videoPlayers[frame].on('play');
             }


             if(!this.previousK){

                this.previousK = frame
                this.previousK = this.previousK.replace('{','');
                this.previousK = this.previousK.replace('}','');

             }



             if(lastVideoSeen!=-1){


                $(".active-tab").removeClass("active-tab");


             }



             if(seen != -1){

                if(tabWatching != false){
                    $('.isWatching').removeClass('isWatching')
                   document.getElementById(tabWatching).classList.remove('isWatching')
                }

                if(!videos[seen]){
                  seen = Object.keys(videos)[0]
                }

               previousVideo = videos[seen]['tab'];


                tabWatching = videos[seen]['tab'];




                document.getElementById(previousVideo).classList.add('isWatching')
                var watchingTab = $( ".isWatching" ).parent().parent().addClass('open');
                this.currentWatchingVideo = $( ".isWatching" ).attr('id');
                this.nextWatchingVideo = $( ".isWatching" ).next().attr('id');



                let vimeoID ='"{ video'+videos[seen]['lesson_id'] + frame + '}"';
                var cvl = document.getElementById(this.frameVi[this.frame]).cloneNode(true);;
                cvl = document.getElementById(this.frameVi[this.frame]).setAttribute('id',vimeoID);

                $('#courses-video').append(cvl)

                this.previousK = vimeoID;

                this.frameVi[this.frame] = this.previousK;





                   videoPlayers[frame] = new Vimeo.Player(vimeoID);



                videoPlayers[frame].loadVideo(seen).then(function(id) {
                   videoId = id

                   $('#notes').attr('data-vimeoid', videoId)
                   //when load video load NOTES
                   let videoNote = notes[videoId];
                   videoNote = videoNote.replace("||", "\n");
                   $('#notes').val(videoNote)

                   array.push(id)

                   prev_topicId.push($('.topic.open .topic-info_title').data('topic-slug'))

                   $('.isWatching').find('a').addClass('current-lesson')
                   //$('#links').empty();

                   let video_link = $('.isWatching').data('link')


                $.each(video_link,function(key, value) {


                //let strArray = e.split("|")
                 $('#links').append( `<li class="resource linkitem">
                                         <a target="_blank" href="${value.link}">
                                           <img
                                             src="theme/assets/img/new/link.svg"
                                             alt="external resource link" />${value.name}</a>
                                       </li>`
                                     )

                });

                   let prog = $('.isWatching').find('.lesson-progress').attr('src','theme/assets/img/new/current_lesson_icon.svg')

                   videoNote = videoNote.replaceAll('||','\n');
                   $('#notes').val(videoNote);
                   videoPlayers[frame].setCurrentTime(videos[id]['stop_time'])
                   // videoPlayers[frame].setLoop(false)
                   $('.status').addClass('saveDone');


                   setTimeout(() => {
                        checkIsNewVideo(videoId)
                    }, "5000")



                }).catch(function(error) {

                   switch (error.name) {
                      case 'TypeError':
                            // the id was not a number
                         //   console.log('edww');
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

             courseName = $('#'+ this.currentWatchingVideo).find('.lesson-info_title');
             courseName = $(courseName).text();
             $('.lesson-main-title').text(courseName);

             this.videoPlayers[this.frame].on('play', function(e) {

                if(!playVi){

                   videoPlayers[frame].setCurrentTime(videos[videoId]['stop_time'])
                   videoPlayers[frame].setLoop(false)
                }
                playVi = true;

             });

             this.videoPlayers[this.frame].on('pause', function(e) {

                @if(!$instructor_topics)

                   if(playVi){
                      playVi = false;
                      videos[videoId]['is_new'] = 0
                      videos[videoId]['stop_time'] = e['seconds'];
                      videos[videoId]['percentMinutes'] = e['percent'];

                      if(e['percent'] >= 0.8){
                            videos[videoId]['seen'] = 1;
                            $('.isWatching.watched').attr("data-completed", '1')
                      }

                      $.ajax({
                       headers: {
                               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                             },
                         type: 'PUT',
                         url: '/elearning/save',
                         data:{'videos':videos,'event_statistic':eventStatistic,'lastVideoSeen':videoId,'event':event},
                         success: function(data) {
                                if(!data['loged_in']){
                                   notLogin(data)
                                }else{

                                   videosSeen[frame] = data['videos'];
                                   //$('.progress-bar').css('width', data['progress'] + '%')
                                   checkForExam(data['exam_access'])
                                }
                               //playVi = true;

                         }
                      });
                   }

                @endif

             });
             // this.videoPlayers[this.frame].on('play', function(e) {
             //   console.log(e['percent'])
             // });



             this.videoPlayers[frame].on('ended', function(ended) {
             if(ended['percent'] == 1){
                saveNoteAjax()
               nextVideo();
               closeTopic()
               $('.isWatching').find('.lesson-progress').attr('src','/theme/assets/img/new/completed_lesson_icon.svg')
             }

           });


             this.videoPlayers[this.frame].on('progress', function(e) {


                @if(!$instructor_topics)

                   if(e['percent'] >= 0.8){
                      if(videos[videoId]['seen'] == 0){

                         videos[videoId]['stop_time'] = e['seconds'];
                         videos[videoId]['percentMinutes'] = e['percent'];
                         videos[videoId]['is_new'] = 0
                         videos[videoId]['seen'] = 1;
                         $('.isWatching').find('.lesson-progress').attr('src','/theme/assets/img/new/completed_lesson_icon.svg')

                         $('.isWatching').attr("data-completed", '1')

                         document.getElementById(previousVideo).classList.add('watched')
                         document.getElementById('play-image-account'+videos[videoId]['lesson_id']).setAttribute('src',"{{cdn('/theme/assets/images/icons/check_lesson.svg')}}")


                         $.ajax({
                             headers: {
                               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                             },
                            type: 'PUT',
                            url: '/elearning/save',
                            data:{'videos':videos,'event_statistic':eventStatistic,'lastVideoSeen':videoId,'event':event},
                            success: function(data) {
                               if(!data['loged_in']){
                                  notLogin(data)
                               }else{

                                 $('.progress-bar').css('width', data['progress'] + '%')
                                  videosSeen[frame] = data['videos'];
                                  checkForExam(data['exam_access'])

                               }

                            }
                         });


                      }
                   }

                @endif


             });

             this.videoPlayers[this.frame].on('fullscreenchange', function(e) {
                window.focus()
             });

          }
          var i = 0;
          var prevId = 0;
          let array = [];

          function current_open_topic(){

            let topicId = $('.isWatching').parent().parent().data('count')
            //alert(topicId)

            let watchingTopic = $('.isWatching').closest('.topic')[0]
            watchingTopic = $(watchingTopic).find('.topic-info_title')[0]

            let topicTitle = $(watchingTopic).data('topic-slug');
            let last = prev_topicId[prev_topicId.length - 1]

            if(topicTitle != last){
            $('*[data-folder-id='+last+']').addClass('hidden')

            }

            $('*[data-folder-id='+topicTitle+']').removeClass('hidden')

            prev_topicId.push(topicTitle)
            $('.open').children('.lessons-list').css('display','block')
          }

          // is_new video is lesson has update with new link video
          function checkIsNewVideo(elem){

            elem = $('.'+elem)[0]
            let elemHasNew = $(elem).find('.newLesson')

            if(elemHasNew.length != 0){

                elemHasNew = elemHasNew[0];
                elemHasNew.remove()
            }

            let topic = $(elem).parent();
            //console.log('curr: ', topic)
            let lessons = $(topic).find('li');

            let findHasNewLesson = false;
            $.each(lessons, function(index, value) {
                if($(value).find('.newLesson').length != 0){
                    findHasNewLesson = true;
                    return false;
                }

            })

            if(!findHasNewLesson){
                //console.log('TOPIC: ', topic)
                topic = topic.parent()
                let newLessonElem = $(topic).find('.newLesson')
                if(newLessonElem.length != 0){

                    newLessonElem.remove();
                }

            }

            //console.log(lesson)
          }

          function play_video(video,playingVideo,vk,lesson){

            video = video + '?title=false'

            if(previousVideo !==false){
               $('.isWatching').removeClass('isWatching')
               document.getElementById(previousVideo).classList.remove('isWatching')
            }

            /*if(previousVideo != playingVideo){
               document.getElementById(playingVideo).classList.add('isWatching')
            }else{*/

               $('.' + lesson).addClass('isWatching')
            //}


             tabWatching = playingVideo;
             previousVideo = playingVideo;

             vk = vk.replace('{','');
             vk = vk.replace('}','');
             let vimeoID ='"{'+ vk + this.frame + '}"';


             var cvl = document.getElementById(previousK).cloneNode(true);
             cvl = document.getElementById(previousK).setAttribute('id',vimeoID);

             $('#courses-video').append(cvl)
             this.previousK = vimeoID;
             this.frameVi[this.frame] = this.previousK;

             video = video.split('/')
             video = video[4].split('?')[0]

             this.videoPlayers[frame] = new Vimeo.Player(vimeoID);



             current_open_topic()

             videoPlayers[frame].loadVideo(video).then(function(id) {


                $('#notes').attr('data-vimeoid', id)

                let videoNote = notes[id];
                videoNote = videoNote.replace("||", "\n");
                $('#notes').val(videoNote)

                let video_link = $('.isWatching').data('link')

                $('#links').empty()

                $.each(video_link,function(key, value) {


                    //let strArray = e.split("|")
                    $('#links').append( `<li class="resource linkitem">
                                            <a target="_blank" href="${value.link}">
                                            <img
                                                src="theme/assets/img/new/link.svg"
                                                alt="external resource link" />${value.name}</a>
                                        </li>`
                                        )

                });
                //viewDownloads()

               $( ".isWatching" ).parent().parent().addClass('open');
               $( ".isWatching" ).parent().css('display', 'block')

                // let videoNote = $('.'+id).attr('data-note')
                // videoNote = videoNote.replaceAll('||','\n');
                // //videoNote = videoNote.replace("||", "\n");
                // $('#notes').val(videoNote)

                $('.status').addClass('saveDone');

               if($('.isWatching').data("completed") == 1){
                 $('.isWatching').find('.lesson-progress').attr('src','theme/assets/img/new/completed_lesson_icon.svg')

               }else if($('.isWatching').data("completed") != 1 && !$('.isWatching').hasClass('watched')){
                 $('.isWatching').find('.lesson-progress').attr('src','theme/assets/img/new/current_lesson_icon.svg')
               }


               var container = $('.sidebar-wrapper'),
                   scrollTo = $('.isWatching');

               container.animate({
                   scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop()
               });
                this.videoId = id
                this.videoPlayers[this.frame].setCurrentTime(videos[id]['stop_time'])
                this.videoPlayers[this.frame].setLoop(false)

                checkIsNewVideo(this.videoId)

                array.push(id)

                 let prev_vid_id = array[array.length - 2]
                $('.'+prev_vid_id).find('a').removeClass('current-lesson')

                $('.isWatching').find('a').addClass('current-lesson')


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

          @if(!$instructor_topics)

             window.onbeforeunload = function (ev) {

                this.videoPlayers[this.frame].pause().then(function() {


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

         // }
          @endif

          document.body.onkeydown= function(e){

             if(e.keyCode == 32 && !noteFocus){


                if(this.playVi){

                   this.playVi = false;

                   videoPlayers[frame].pause().then(function() {

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

                   videoPlayers[frame].play().then(function() {
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



                e.preventDefault();

                }

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
         $('.getdropboxlink').click(function() {

               var dir = $(this).attr('data-dirname');
               var fname = $(this).attr('data-filename');

               $.ajax({ url: '/getdropbox', type: "post",
                 headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 data: {dir: dir, fname:fname},

                 success: function(data) {

                   window.location.href = data;
                 }
               });

            });
      </script>
      <script>
         function checkForExam(examAccess){

            if(examAccess){

               var d = new Date();
               d.setTime(d.getTime() + (1*24*60*60*1000));
               var expires = "expires="+ d.toUTCString();
               document.cookie = 'examMessage-' + eventStatistic + "=" + 'showmessage' + ";" + expires + ";path=/";

               var favDialog = document.getElementById('examDialog');
               favDialog.style.display = "block";
               $("body").css("overflow-y", "hidden")

               videoPlayers[frame].pause().then(function() {

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

            videoPlayers[frame].play().then(function() {
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

         $( document ).ready(function() {

            //let topicTitle = $('.topic.open .topic-info_title').data('topic-slug');
            //$('*[data-folder-id='+topicTitle+']').removeClass('hidden')

            // Handler for .ready() called.
            setTimeout( function(){

                    $('.topic.open').children('.lessons-list').css('display','block')
                    scrollIsWatchingClass()

            }, 3000 );


            $(window).resize(function(){
              $("span").text(x += 1);
            });

          });


            $('h3').click(function(e) {

                $('.topic.open').removeClass('.open')

                let a = $('.isWatching').parent().parent()
                $(a).children('.lessons-list').css('display','none')

                if($('.'+ array[array.length - 1]).attr("data-completed") != 1){
                      $('.'+array[array.length - 1]).find('.lesson-progress').attr('src','theme/assets/img/new/lesson_icon.svg')
                }
                window.scrollTo(0, 0);

            });

            $('.change-lesson-button.next-video-button').click(function(e) {
                closeTopic()
              $('.isWatching').parent().css('display', 'block')
              if($('.'+ array[array.length - 1]).attr("data-completed") != 1){
                $('.'+array[array.length - 1]).find('.lesson-progress').attr('src','theme/assets/img/new/lesson_icon.svg')
               }
            })

            $('.lesson-teacher-wrapper').click(function(e) {
              window.open($(this).children().data('slug'))
            })

            function scrollIsWatchingClass(){
                setTimeout( function(){
                  if(document.querySelector('.isWatching')){
                    document.querySelector('.isWatching').scrollIntoView({
                        behavior: 'smooth'
                    });
                  }
                }, 50)

            }

            function closeTopic(){
                scrollIsWatchingClass()
                let topics = $('.topic')

                $.each(topics, function(index, value) {
                    let hasIsWatching = $(value).find('.isWatching')
                    if(hasIsWatching.length == 0){
                        $(value).removeClass('open')
                        let lessonList = $(value).children()[1]
                        $(lessonList).css('display', 'none')
                    }
                })



            }

            $('.change-lesson-button.previous-video-button').click(function(e) {
                //close accordion if next or previous lesson topic
                closeTopic()

                //open accordion
              $('.isWatching').parent().css('display', 'block')

              if($('.'+ array[array.length - 1]).attr("data-completed") != 1){
                $('.'+array[array.length - 1]).find('.lesson-progress').attr('src','theme/assets/img/new/lesson_icon.svg')
               }
            })

            $(window).on('load', function() {

              setTimeout( function(){
                //scrollIsWatchingClass()
              var container = $('.sidebar-wrapper'),
                scrollTo = $('.isWatching');

              container.animate({
                  scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop()
              });
                    }, 2000 );


            let topicId = $('.topic.open .topic-info_title').data('topic-slug');


              $('*[data-folder-id='+topicId+']').removeClass('hidden')

         });

         $(document).ready(function() {

         //checkWidth();

        //  $('.getdropboxlink').click(function() {

        //     var dir = $(this).attr('data-dirname');
        //     var fname = $(this).attr('data-filename');

        //     $.ajax({ url: '/getdropbox', type: "post",
        //         headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         data: {dir: dir, fname:fname},

        //         success: function(data) {
        //         //console.log(data);
        //         window.location.href = data;
        //         }
        //     });

        // });


         });

         $(window).on("resize", function(){
         //checkWidth()
         });

         function checkWidth(){
         $vWidth = $(window).width();
         if($vWidth < 1285){
          $('.sidebar-wrapper').removeClass('open')
         }else{
          $('.sidebar-wrapper').addClass('open')
         }
         }

      </script>

<script>
$('#notes').on('focusin', function() {
        noteFocus = true;
    })
    .on('focusout', function(e) {
      noteFocus = false;
    });

</script>

<script>
     $(document).ready(function() {
        let topics = $('.topics-list').find('.topic')

        let hasNewLesson = []
        $.each(topics, function(index, value) {
            let lessons_list = $(value).find('.lessons-list')[0]

            hasNewLesson = $(lessons_list).find('.newLesson');

            if(hasNewLesson.length != 0){

                let a = $(value).find('.topic-info_duration')[0]

                $(a).after('<span class="white-separator"> | </span><span class="newLesson notification-topic"><img src="/theme/assets/images/notification-dot.png"></img></span>')
            }
            //return false;
            // $.each(lessons, function(index1, lesson) {
            //     let hasNewLesson = $(lesson).find('.newLesson')
            // })
        })
     })

</script>

   </body>
</html>

