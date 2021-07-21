<!DOCTYPE html>
<?php //dd($event); ?>
<html lang="en">
  <head>
    <base href="{!! URL::to('/') !!}/" target="_self" />
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    {{--<link href="{{ cdn(elixir('theme/assets/css/style_ver.css')) }}" rel="stylesheet" media="all" />--}}
    {{--<link rel="stylesheet" href="{{ cdn('theme/assets/css/old.css') }}" />--}}

    {{--<link rel="stylesheet" href="https://use.typekit.net/pfo3bjs.css">--}}
    <link rel="stylesheet" href="{{ cdn('theme/assets/css/new/pop_up.css') }}" />
    <link rel="stylesheet" href="{{ cdn('theme/assets/css/new/normalize.css') }}" />
    <link rel="stylesheet" href="{{ cdn('theme/assets/css/new/core.css') }}" />
    <title>{{$course}}</title>

    <script src="https://kit.fontawesome.com/84bbd74d3a.js" crossorigin="anonymous"></script>

  </head>

  <?php

  $notesss = json_decode($notes);
  $notesss = (array) $notesss;
  //dd($notes);

  ?>



  <?php $bonusFiles = ['_Bonus', 'Bonus', 'Bonus Files', 'Βonus', '_Βonus', 'Βonus', 'Βonus Files'] ?>

  <body onload="tabclick('<?= $videos; ?>', '<?= $details['id']; ?>', '{{$lastVideoSeen}}', '{{$course}}','{{$notes}}', '{{$videos_progress}}')">
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
            <a tabindex="0" href="/myaccount">
              <img
                id="logo"
                src="/theme/assets/img/new/logo-knowcrunch-seminars.svg"
                alt="knowcruch logo"
            /></a>
            <a tabindex="0" href="/myaccount" class="jsHideSidebar">
              <img
                id="sidebar-toggle"
                src="/theme/assets/img/new/arrows.svg"
                alt="toggle sidebar"
				class="jsGreenOnHover"
              />
            </a>
          </header>
          <!-- ./sidebar-header -->

          <div class="searchbar-wrapper">
            <form class="searchbar" action="/">
              <label for="search">Search: </label>
              <input
                class="jsSearchInput"
                type="text"
                id="search"
                placeholder="SEARCH"
                name="search"
              />
              <img id="jsSearchButton" src="theme/assets/img/new/search-icon.svg" alt="search" />
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

                @foreach($topics['topics'] as $keyTopic => $topic)
                  <?php
                  //dd($topic);
                    if($count == 1)
                       // $count = 2;
                  ?>

                <li class="topic" data-count="{{$count}}">
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


                          $m = floor(($topic['topic_duration'] / 60) % 60);
                          $h = $hours = floor($topic['topic_duration'] / 3600);

                          echo intval($h) . 'h ' . $m . 'm';
                       ?>
                      </span>
                    </div>
                    <!-- ./topic-info -->
                    <img
                      class="topic-open jsTopicOpen"
                      src="theme/assets/img/new/arrow-down.svg"
                      alt="open topic"
                    />
                    <img
                      class="topic-close jsTopicClose"
                      src="theme/assets/img/new/arrow-up.svg"
                      alt="close topic"
                    />
                  </a>
                  <?php
                     $frame = $course;
                     $frame = str_replace(' ','_',$frame);
                     $frame = str_replace('-','',$frame);
                     $frame = str_replace('&','',$frame);
                     $frame = str_replace('_','',$frame);

                     $frame2 = '{'.$frame.'}';
                     $frame = $frame;



                     ?>
                  <!-- ./topic-header -->
                  <ul class="lessons-list">
                  @foreach($topic as $keyLesso => $lesso)
                    @foreach($lesso as $keyLesson => $lesson)
                    <?php
                    $current_cat_id = $lesson['cat_id'];
                    $catId = $lesson['cat_id'];
                    $frame1 = '';
                      $path = $lesson['vimeo'];
                      $frame1 = $frame.''. $keyLesso;
                    ?>

                    <?php
                      //print_r($path);
                      //$vimeoId = str_replace($path, '?title=false');
                      $vimeoId = str_replace('?title=false','',$path);
                      $vimeoId = str_replace('https://player.vimeo.com/video/','',$vimeoId);
                     ?>



                    <li class="lesson {{$vimeoId}}" data-completed="{{$lesson['seen']}}" data-links="{{$links[$vimeoId]}}" data-note="{{$notesss[$vimeoId]}}" id="{{$frame1}}">
                      <a class="" href="javascript:void(0)" onclick="play_video('{{$path}}','{{$frame1}}','{video{{$id}}}', '{{$id}}', '{{$notes}}')" tabindex="0">
                        <img
                          class="lesson-progress"
                          src="
                          <?php
                          if($lesson['seen'] == 1){
                            echo 'theme/assets/img/new/completed_lesson_icon.svg';
                          }else{
                            echo 'theme/assets/img/new/lesson_icon.svg';
                          }
                          ?>
                          "
                          alt="lesson progress"
                        />

                        <div class="lesson-info">
                          <h3
                            class="lesson-info_title @if($lesson['bold'])bold-topic @endif"
                            data-title="{!! $keyLesson !!}"

                          >
                            {!! $keyLesson !!}
                          </h3>
                          <span class="lesson-info_duration">{{$lesson['duration']}}</span>
							<span class="white-separator"> | </span>
                          <span class="lesson-info_topic-type"
                            >Live Tutorial</span
                          >
                        </div>
                        <!-- ./lesson-info -->
						<div class="lesson-teacher-wrapper">
                        <img
                          class="lesson-teacher"
                          src="{{cdn($lesson['inst_photo'])}}"
                          alt="{{$lesson['inst']}}"
                          title="{{$lesson['inst']}}"
                          data-slug="{{$lesson['slug']}}"
                        />
              </div>
                        <!-- ./lesson-teacher-wrapper -->
                      </a>
                    </li>

                    @endforeach
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
          <img
              id="second-logo"
              src="/theme/assets/img/new/logo-knowcrunch-seminars.svg"
              alt="knowcruch logo"
            />
            <a tabindex="0" class="show-sidebar jsShowSidebar" href="#">
              <img
                class="jsGreenOnHover"
                src="/theme/assets/img/new/arrows.svg"
                alt="show sidebar"
              />
            </a>
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
          <div id='{{$id}}' class="video-wrapper">
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
              <h2 class="lesson-main-title">{!! $keyLesson !!}</h2>
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
                  <img src="theme/assets/img/new/resources-icon.svg" alt="resources" />
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
                  <img src="theme/assets/img/new/notes-icon.svg" alt="notes" />
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
                    <img src="theme/assets/img/new/previous_video_icon.svg" alt="previous video" />
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
                    <img src="theme/assets/img/new/next_video_icon.svg" alt="next video" />
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
                      <a href="https://www.facebook.com/sharer/sharer.php?u=<?= URL('/').'/'. $slug .'?utm_source=Facebook&utm_medium=User_Share&utm_campaign=DIGITAL_DIPLOMA_ELEARNING_GR'; ?>&quote=<?= 'Παρακολουθώ το '. urlencode($course);?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on Facebook">
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
                      <a href="https://twitter.com/share?url=<?= URL('/').'/'. $slug.'?utm_source=Twitter&utm_medium=User_Share&utm_campaign=DIGITAL_DIPLOMA_ELEARNING_GR'; ?>&text=<?= 'Παρακολουθώ το '. urlencode($course);?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on Twitter"><img tabindex="0" src="theme/assets/img/new/twitter1.svg" alt="twitter"
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
                @if(!$instructor_topics && isset($folders) && count($folders) > 0 && $showFiles)
                <?php //dd($folders); ?>
                  <div class="lesson-downloads">
                    <h4 class="resource-list-title">Downloads</h4>
                    <?php //dd($folders) ?>


                    @foreach($folders as $catid => $dbfolder)
                    <?php //dd($dbfolder)?>
                    <?php
                      $folder = false;
                      if(trim($catid) == trim($catId)){
                          $folder = true;
                      }
                      ?>
                      @if($folder)
                        @if($current_cat_id == $catid)
                          @if (isset($dbfolder[0]) && !empty($dbfolder[0]))

                            @foreach($dbfolder[0] as $key => $folder)
                            <?php //dd($folder);?>
                              <?php

                                //dd($folder);
                                $rf = strtolower($folder['dirname']);
                                $rf1 = $folder['dirname']; //newdropbox
                                //   print_r('asd');
                                //   print_r($rf1);
                                //   die();
                              ?>
                              <?php
                                $topic=1;
                                if($instructor_topics){
                                    $topic=0;

                                    if((trim($folder['foldername']) === '1 - Prelearning - Digital & Social Media Fundamentals')
                                            && in_array(trim('Pre-learning: Digital & Social Media Fundamentals'), $instructor_topics)){

                                      $topic = 1;
                                    }else{
                                      $topic_name = explode( '-', $folder['foldername'] );
                                      $topic=in_array(trim($topic_name[1]), $instructor_topics);
                                    }
                                }
                                ?>
                              @if($topic)
                              <?php

                                $topic_name = preg_replace('/[0-9]+/', '', $folder['foldername']);
                                $topic_name = \Illuminate\Support\Str::slug($topic_name);


                              ?>
                              <ul class="resource-list hidden" data-folder-id="<?= $topic_name; ?>">

                              <!-- <li class="resource "> -->

                              <h3>{{--$folder['foldername']--}}</h3>
                              <?php
                                $checkedF = [];
                                $fs = [];
                                $fk = 1;
                                $bonus = [];
                                $subfolder = false;
                              ?>
                              <?php //dd($files[$catid]);?>
                                @if (isset($files[$catid][1]) && !empty($files[$catid][1]))

                                  @foreach($files[$catid][1] as $fkey => $frow2)

                                    @if($frow2['fid'] == $folder['id'])
                                      <?php
                                      //dd($frow2);
                                          $fn = $folder['foldername'];

                                          //BONUS FILE
                                          if(isset($dbfolder[1]) && !empty($dbfolder[1])){
                                            foreach($dbfolder[1] as $nkey => $nfolder){
                                              $dirname = explode('/',$nfolder['dirname']);
                                              if($nfolder['parent'] == $folder['id'] && in_array($fn,$dirname) && !$subfolder  && !in_array($nfolder['foldername'],$bonusFiles) /*($nfolder['foldername'] !== '_Bonus' || $nfolder['foldername'] !== 'Bonus')*/){

                                                  $checkedF[] = $nfolder['id'] + 1 ;
                                                  $fs[$nfolder['id']+1]=[];
                                                  $fs[$nfolder['id']+1][] = $nfolder;
                                              }
                                            }
                                          }
                                          if(count($fs) > 0 ){
                                            $subfolder = true;
                                          }
                                      ?>
                                      <!-- SUBFOLDER -->

                                      @if($subfolder && in_array($fk,$checkedF))

                                        @while(in_array($fk,$checkedF))
                                              <?php

                                                $sfv = reset($checkedF);
                                                $sfk = array_search($sfv, $checkedF);
                                                unset($checkedF[$sfk]);
                                              ?>


                                              @if (isset($dbfolder[1]) && !empty($dbfolder[1]))
                                                @foreach($dbfolder[1] as $nkey => $nfolder)
                                                    @if($nfolder['id'] == $fs[$sfv][0]['id'] && $nfolder['parent'] ==  $fs[$sfv][0]['parent'] && !in_array($nfolder['foldername'],$bonusFiles) /*($nfolder['foldername'] !== '_Bonus' || $nfolder['foldername'] !== 'Bonus')*/) <!--//lioncode-->

                                                    <li id="{{$rf1}}" class="resource bonus-files">
                                                      {{-- $nfolder['foldername'] --}}
                                                      @if (isset($files[$catid][2]) && !empty($files[$catid][2]))
                                                            @foreach($files[$catid][2] as $fkey => $frow)
                                                                @if (strpos($frow['dirname'], $rf) !== false || strpos($frow['dirname'], $rf1) !== false && ( $frow['fid'] == ($sfv-1)  ))
                                                                  <?php $bonus[]= $frow['filename'] ?>
                                                                  <li id="{{$rf1}}" class="resource">
                                                                    <a class="download-file getdropboxlink"  data-dirname="{{ $frow['dirname'] }}" data-filename="{{ $frow['filename'] }}" href="javascript:void(0)" ><img
                                                                        src="theme/assets/img/new/download.svg"
                                                                        alt="download resource" />{{ $frow['filename'] }}</a
                                                                    >
                                                                    {{--<span class="last-modified">Last modified:  {{$frow['last_mod']}}</span>--}}
                                                                  </li>
                                                                @endif
                                                          @endforeach
                                                        @endif
                                                    </li>
                                                    @endif
                                                @endforeach
                                              @endif
                                              <!-- bonus of each lesson -->

                                          <?php $fk += 1;?>

                                        @endwhile
                                        <!-- <div class="files-wrapper">
                                          <div class="file-wrapper">
                                              <h4 class="file-title">{{ $frow2['filename'] }}</h4>
                                              <span class="last-modified">Last modified:  {{$frow2['last_mod']}}</span>
                                              <a class="download-file getdropboxlink"  data-dirname="{{ $frow2['dirname'] }}" data-filename="{{ $frow2['filename'] }}" href="javascript:void(0)" >
                                              <img src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}"  alt="Download File"/></a>
                                          </div>
                                        </div> -->


                                        <li id="{{$rf1}}" class="resource">
                                        <a class="download-file getdropboxlink"  data-dirname="{{ $frow2['dirname'] }}" data-filename="{{ $frow2['filename'] }}" href="javascript:void(0)" ><img
                                            src="theme/assets/img/new/download.svg"
                                            alt="download resource" />{{ $frow2['filename'] }}</a
                                        >
                                        {{--<span class="last-modified">Last modified:  {{$frow2['last_mod']}}</span>--}}
                                      </li>


                                      @else
                                      <li id="{{$rf1}}" class="resource">
                                        <a class="download-file getdropboxlink"  data-dirname="{{ $frow2['dirname'] }}" data-filename="{{ $frow2['filename'] }}" href="javascript:void(0)" ><img
                                            src="theme/assets/img/new/download.svg"
                                            alt="download resource" />{{ $frow2['filename'] }}</a
                                        >
                                        {{--<span class="last-modified">Last modified:  {{$frow2['last_mod']}}</span>--}}
                                      </li>
                                      @endif
                                      <!-- END END SUBFOLDER -->

                                      <?php
                                          $fk += 1;
                                      ?>
                                    @endif

                                  @endforeach
                                  <!-- bonus of each lesson -->
                                  @if (isset($dbfolder[1]) && !empty($dbfolder[1]))
                                    @foreach($dbfolder[1] as $nkey => $nfolder)
                                      @if($nfolder['parent'] == $folder['id'] && in_array($nfolder['foldername'],$bonusFiles) /*($nfolder['foldername'] == '_Bonus' || $nfolder['foldername'] == 'Bonus')*/) <!--//lioncode-->

                                      <li id="{{$rf1}}" class="resource bonus-files">
                                      {{-- $nfolder['foldername'] --}}
                                      @if (isset($files[$catid][2]) && !empty($files[$catid][2]))
                                              @foreach($files[$catid][2] as $fkey => $frow)
                                                @if (strpos($frow['dirname'], $rf) !== false || strpos($frow['dirname'], $rf1) !== false && !in_array($frow['filename'],$bonus))

                                                    <li id="{{$rf1}}" class="resource">
                                                      <a class="download-file getdropboxlink"  data-dirname="{{ $frow['dirname'] }}" data-filename="{{ $frow['filename'] }}" href="javascript:void(0)" ><img
                                                          src="theme/assets/img/new/download.svg"
                                                          alt="download resource" />{{ $frow['filename'] }}</a
                                                      >
                                                      {{--<span class="last-modified">Last modified:  {{$frow['last_mod']}}</span>--}}
                                                    </li>
                                                  @endif
                                            @endforeach
                                          @endif
                                      </li>
                                      @endif

                                    @endforeach
                                  @endif

                                    <!--END END bonus of each lesson -->
                                @endif
                              @endif
                                        </ul>
                            @endforeach
                          @endif
                        @endif
                      @endif
                    @endforeach

                    </ul>
                  </div>
                  @endif

                  <!-- ./lesson-downloads -->
                  <div class="lesson-links">
                    <h4 class="resource-list-title">Links</h4>
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
                    name="notes"
                    id="notes"
                    placeholder="My notes for this lesson"
                    rows="10"
                  ></textarea>
                  <div class="lesson-notes_status">
                  <a
                      tabindex="0"
                      href="javascript:void(0)"
                      class="tab-button save-button jsOpenNotesTab"
                    >
                    <div tabindex="-1" style="text-align: end;" class="inner-tab-button">
                      <img src="theme/assets/img/new/floppy-save-new.svg" width="30" height="20" alt="notes" />
                      <!-- <span>Notes</span> -->
                    </div>
                    </a>
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
            <span class="copyright">KnowCrunch Inc. © 2021</span>
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
    <script src="{{ cdn('theme/assets/js/new_js1/app1.js') }}"></script>
    <script src="https://player.vimeo.com/api/player.js"></script>

    <script>
      $( ".lesson-info_title" ).click(function() {
        let title = $(this).text();
        $('.lesson-main-title').text(title);
      });



    </script>

    <script>

    </script>


<script>

  var prev_topicId = [];

  var note;

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

   $(".save-button").click(function() {
        let note = $("#notes").val();
        let vimeoId = $('.isWatching').attr('class');

        vimeoId=vimeoId.replace("isWatching", "");
        vimeoId=vimeoId.replace("lesson", "");
        vimeoId=vimeoId.replace(" ", "");

        let event = $('.topics-list').attr('id');
        //console.log(event)

        $.ajax({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
          type: 'PUT',
          url: '/elearning/saveNote',
          data:{'text':note,'vimeoId':vimeoId, 'event':event},
          success: function(data) {
                if(data){
                    $('#notes').val(data['text']);
                    $('.isWatching').data("note", data['text'])
                    $('.saveDone').removeClass('saveDone');
                    checkForExam(data['exam_access'])
                }else{
                    console.log('Not save')
                }
                //playVi = true;

          }
        });

      })


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
        //console.log(prevTitle);
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

    //  console.log('CUR:'+ this.currentWatchingVideo);
    //  console.log('NEXT:'+next);

     if(typeof next === 'undefined'){
        let currTopic = $('#'+ this.currentWatchingVideo).parent().parent();
        let nextTopic = currTopic.next();
        //console.log($(nextTopic).next()['length']);

        if($(nextTopic).next()['length'] == 0){
          //console.log('The end');
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

   function tabclick(videos,event,seen,statisticId,frame,notes,progress){

     $('.progress-bar').css('width', progress + '%')


     notes = JSON.parse(notes)

      videos = JSON.parse(videos)

      frame = frame.replace(/\s/g, '')
      frame = frame.replace(/-/g, '')
      frame = frame.replace(/&/g, '')
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
        //console.log(videosSeen);
        //console.log('videos seen');
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
         $this = $('#'+videos[lastVideoSeen]['tab']).parent().parent().children('h2')

         $this.click();

      }



      if(seen != -1){

         if(tabWatching != false){
            document.getElementById(tabWatching).classList.remove('isWatching')
         }

         previousVideo = videos[seen]['tab'];
         tabWatching = videos[seen]['tab'];


         //console.log('pre vid'+previousVideo);

         //console.log(previousVideo);

         document.getElementById(previousVideo).classList.add('isWatching')
         var watchingTab = $( ".isWatching" ).parent().parent().addClass('open');
         this.currentWatchingVideo = $( ".isWatching" ).attr('id');
         this.nextWatchingVideo = $( ".isWatching" ).next().attr('id');

        //$(".next-video-button").attr("href",nextWatchingVideo);

         //console.log('next vid'+ nextWatchingLesson);
        //  let a = '"\\"' + this.frameVi[this.frame] + '\\';
        //  console.log('THIS FRAME:' + a)

        //console.log(this.frameVi[this.frame]);

         let vimeoID ='"{ video'+videos[seen]['lesson_id'] + frame + '}"';
         var cvl = document.getElementById(this.frameVi[this.frame]).cloneNode(true);;
         cvl = document.getElementById(this.frameVi[this.frame]).setAttribute('id',vimeoID);

         $('#courses-video').append(cvl)

         this.previousK = vimeoID;
         this.frameVi[this.frame] = this.previousK;

         //videoPlayers[frame] = new Vimeo.Player(vimeoID);

        // if(videosPlayed[frame].includes(seen) == false){

            //console.log('frame = ', frame)
           // console.log(videosPlayed)

            videoPlayers[frame] = new Vimeo.Player(vimeoID);
          //  videosPlayed[frame].push(parseInt(seen));

         videoPlayers[frame].loadVideo(seen).then(function(id) {
            videoId = id
            //when load video load NOTES
            let videoNote = notes[videoId];
            videoNote = videoNote.replace("||", "\n");
            //console.log('first load video'+videoId)
            array.push(id)
            prev_topicId.push($('.topic.open .topic-info_title').data('topic-slug'))
            $('.isWatching').find('a').addClass('current-lesson')
            $('.linkitem').remove();

            let video_link = $('.'+this.videoId).data('links')

         video_link.forEach(function(e) {
         let strArray = e.split("|")
          $('#links').append( `<li id="linkitem" class="resource linkitem">
                                  <a target="_blank" href="${strArray[1]}">
                                    <img
                                      src="theme/assets/img/new/link.svg"
                                      alt="external resource link" />${strArray[0]}</a>
                                </li>`
                              )

         });
            let prog = $('.isWatching').find('.lesson-progress').attr('src','theme/assets/img/new/current_lesson_icon.svg')


            $('#notes').val(videoNote);
            videoPlayers[frame].setCurrentTime(videos[id]['stop_time'])
            // videoPlayers[frame].setLoop(false)
            $('.status').addClass('saveDone');





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
               videos[videoId]['stop_time'] = e['seconds'];
               videos[videoId]['percentMinutes'] = e['percent'];

               if(e['percent'] >= 0.8){
                     videos[videoId]['seen'] = 1;
                     $('.isWatching.watched').attr("data-completed", '1')
               }
              //console.log('edww');
            //   this.videoPlayers[this.frame].off('play');;
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
                           $('.progress-bar').css('width', data['progress'] + '%')
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
        nextVideo();
        $('.isWatching').find('.lesson-progress').attr('src','/theme/assets/img/new/completed_lesson_icon.svg')
      }

    });


      this.videoPlayers[this.frame].on('progress', function(e) {

         @if(!$instructor_topics)

            if(e['percent'] >= 0.8){
               if(videos[videoId]['seen'] == 0){

                  videos[videoId]['stop_time'] = e['seconds'];
                  videos[videoId]['percentMinutes'] = e['percent'];
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

   function play_video(video,playingVideo,vk,lesson,notes){

    notes = JSON.parse(notes)
      if(previousVideo !==false){

         document.getElementById(previousVideo).classList.remove('isWatching')

      }

      document.getElementById(playingVideo).classList.add('isWatching')
      tabWatching = playingVideo;
      previousVideo = playingVideo;

      vk = vk.replace('{','');
      vk = vk.replace('}','');
      let vimeoID ='"{'+ vk + this.frame + '}"';


      var cvl = document.getElementById(previousK).cloneNode(true);;
      cvl = document.getElementById(previousK).setAttribute('id',vimeoID);

      $('#courses-video').append(cvl)
      this.previousK = vimeoID;
      this.frameVi[this.frame] = this.previousK;

      video = video.split('/')
      video = video[4].split('?')[0]

      this.videoPlayers[frame] = new Vimeo.Player(vimeoID);

      videoPlayers[frame].loadVideo(video).then(function(id) {
        //console.log($('.isWatching').data("completed"))
        $( ".isWatching" ).parent().parent().addClass('open');
        $( ".isWatching" ).parent().css('display', 'block')
         let videoNote = $('.isWatching').data('note')
         videoNote = videoNote.replace("||", "\n");
         $('#notes').val(videoNote)
         $('.status').addClass('saveDone');

        if($('.isWatching').data("completed") == 1){
          $('.isWatching').find('.lesson-progress').attr('src','theme/assets/img/new/completed_lesson_icon.svg')

        }else if($('.isWatching').data("completed") != 1 && !$('.isWatching').hasClass('watched')){
          $('.isWatching').find('.lesson-progress').attr('src','theme/assets/img/new/current_lesson_icon.svg')
        }
        // $('.sidebar-wrapper').scrollTo('.isWatching');
//         $('.sidebar-wrapper').animate({
//   scrollTop: $(".isWatching").offset().top
// });

var container = $('.sidebar-wrapper'),
    scrollTo = $('.isWatching');

container.animate({
    scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop()
});
         this.videoId = id
         //console.log('curr vid'+id)
         this.videoPlayers[this.frame].setCurrentTime(videos[id]['stop_time'])
         this.videoPlayers[this.frame].setLoop(false)

         //console.log('load two')
         array.push(id)
         //console.log(array[array.length - 1])
          //t/t

          let prev_vid_id = array[array.length - 2]
          //console.log('prevvideo'+prev_vid_id)
         $('.'+prev_vid_id).find('a').removeClass('current-lesson')

         $('.isWatching').find('a').addClass('current-lesson')




         let video_link = $('.'+this.videoId).data('links')
         $('.resource-link').remove();
         //console.log(video_link)
         $('.linkitem').remove();
         //console.log(video_link)
         video_link.forEach(function(e) {
         let strArray = e.split("|")
          $('#links').append( `<li id="linkitem" class="resource linkitem">
                                  <a target="_blank" href="${strArray[1]}">
                                    <img
                                      src="theme/assets/img/new/link.svg"
                                      alt="external resource link" />${strArray[0]}</a>
                                </li>`
                              )

         });

         //console.log('second load')

         let topicId = $('.isWatching').parent().parent().data('count')
         let topicTitle = $('.topic.open .topic-info_title').data('topic-slug');


        let last = prev_topicId[prev_topicId.length - 1]

          if(topicTitle != last){
            $('*[data-folder-id='+last+']').addClass('hidden')

          }

          $('*[data-folder-id='+topicTitle+']').removeClass('hidden')

        prev_topicId.push(topicTitle)
        //$('.open').children('.lessons-list').css('display','block')

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
  // console.log('olay = ', this.playVi)
  // if(playVi){
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


      if(e.keyCode == 32){

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
     //console.log(data['message'])
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
          //console.log(data);
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
        $('.open').children('.lessons-list').css('display','block')
          }, 1000 );


      $(window).resize(function(){
        $("span").text(x += 1);
      });

    });


      $('h3').click(function(e) {

          $('.topic.open').removeClass('.open')

          let a = $('.isWatching').parent().parent()
          $(a).children('.lessons-list').css('display','block')

          if($('.'+ array[array.length - 1]).attr("data-completed") != 1){
                $('.'+array[array.length - 1]).find('.lesson-progress').attr('src','theme/assets/img/new/lesson_icon.svg')
          }
          window.scrollTo(0, 0);

      });

      $('.change-lesson-button.next-video-button').click(function(e) {
        $('.isWatching').parent().css('display', 'block')
        if($('.'+ array[array.length - 1]).attr("data-completed") != 1){
          $('.'+array[array.length - 1]).find('.lesson-progress').attr('src','theme/assets/img/new/lesson_icon.svg')
         }
         //console.log(array[array.length - 1])
      })

      $('.lesson-teacher-wrapper').click(function(e) {
        window.open($(this).children().data('slug'))
      })

      $('.change-lesson-button.previous-video-button').click(function(e) {
        $('.isWatching').parent().css('display', 'block')
        if($('.'+ array[array.length - 1]).attr("data-completed") != 1){
          $('.'+array[array.length - 1]).find('.lesson-progress').attr('src','theme/assets/img/new/lesson_icon.svg')
         }
         //console.log(array[array.length - 1])
      })

      $(window).on('load', function() {
        setTimeout( function(){
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

  checkWidth();


});

$(window).on("resize", function(){
  checkWidth()
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

  </body>
</html>
