@if($event['delivery'] != 143)
<div class="col12 dynamic-courses-wrapper dynamic-courses-wrapper--style2 @if((isset($event['paid']) && $event['paid'] == 0 && isset($event['transactionPending']) && $event['transactionPending'] == 2) || (isset($event['transactionPendingSepa']) && $event['transactionPendingSepa'] == 1)){{'pendingSepa'}}@elseif(isset($event['paid']) && $event['paid'] == 0 ){{'unpaid'}}@endif">
    <div class="item">
      <h2>{{ $event['title'] }}</h2>
      <div class="inside-tabs">
        <div class="tabs-ctrl">
          <ul>
            @if(isset($event['topics']) && count($event['topics']) > 0)<li class="active"><a href="#c-shedule-inner{{$tab}}">Schedule </a></li>@endif
              <?php  $fa = !$releaseDateIsSet || strtotime(date('Y-m-d',strtotime($event['release_date_files']))) >= strtotime(date('Y-m-d')) ?>
            @if(isset($event['dropbox']) && count($event['dropbox']) != 0 &&
            $event['status'] == App\Model\Event::STATUS_COMPLETED && $fa)
              <li><a href="#c-files-inner{{$tab}}">Files</a></li>
            @endif
            @if(isset($event['exams']) && count($event['exams']) >0 )
              <li><a href="#c-exams-inner{{$tab}}">Exams</a></li>
            @endif

            @if(count($event['certs']) > 0)
              <li><a href="#c-cert-inner{{$tab}}">Certificate</a></li>
            @endif
          </ul>
        </div>
        <div class="inside-tabs-wrapper">
          <div id="c-shedule-inner{{$tab}}" class="in-tab-wrapper" style="display: block;">

            <div class="bottom">
              @if(isset($event['summaryDate']))<div class="duration"><img loading="lazy" class="replace-with-svg resp-img" onerror="this.src='{{cdn('/theme/assets/images/icons/Duration_Hours.svg')}}'" width="20" height="20" src="{{cdn($event['summaryDate_icon'])}}" title="summary_icon" alt="summary_icon"><span class="inline-myaccount-text">{!! $event['summaryDate'] !!}<span></div>@endif
              @if($event['hours'])
                <div class="expire-date"><img loading="lazy" class="replace-with-svg resp-img" onerror="this.src='{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}'"  src="{{cdn($event['hours_icon'])}}" width="20" height="20" title="summary_icon" alt="summary_icon">{{$event['hours']}}h</div>
              @endif
              @if($event['status'] == App\Model\Event::STATUS_WAITING)
                <div>
                  You are on the waiting list.
                </div>
              @endif
            </div>

            @if(isset($event['topics']) && count($event['topics']) > 0)
              <div class="bottom  @if((isset($event['paid']) && $event['paid']) || !isset($event['paid'])) {{ 'tabs-bottom'}} @endif">

                <div class="expire-date exp-date"><img loading="lazy" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" width="20" height="20" title="Access-Files-icon" alt="Access-Files-icon">Schedule available in PDF</div>
                <div class="right">
                  <a target="_blank" href="/print/syllabus/{{$event['slugable']['slug']}}" class="btn btn--secondary btn--md"> DOWNLOAD SCHEDULE </a>
                </div>
              </div>
              <div class="acc-topic-accordion">
                @if((isset($event['paid']) && $event['paid']) || !isset($event['paid']))
                  <div class="accordion-wrapper accordion-big">
                      <?php $catId = -1?>
                      <?php //dd($event['topics']); ?>
                    @foreach($event['topics'] as $keyTopic => $topic)
                        <?php //dd($keyTopic); ?>
                      @if(isset($topic) && count($topic) != 0 )

                        <div class="accordion-item">
                          <h3 class="accordion-title title-blue-gradient scroll-to-top">{{$keyTopic}}</h3>
                          <div class="accordion-content no-padding">
                              <?php //dd($topic[0]['lessons']); ?>
                            @foreach($topic['lessons'] as $keyLesso => $lesso)
                              <div class="topic-wrapper-big">
                                <div class="topic-title-meta">
                                  <h4>{{$lesso['title']}}</h4>
                                  <!-- Feedback 18-11 changed -->
                                  <div class="topic-meta">
                                    @if(count($lesso['type']) >0)
                                      <div class="category">{{$lesso['type'][0]['name']}}</div>
                                    @endif

                                    <!-- Feedback 18-11 changed -->
                                    <span class="meta-item duration"><img loading="lazy" class="resp-img" src="{{cdn('/theme/assets/images/icons/Duration_Hours.svg')}}" alt="Duration_Hours_icon" title="Duration_Hours_icon" /><?= date( "l d M Y", strtotime($lesso['pivot']['time_starts']) ) ?></span> <!-- Feedback 18-11 changed -->
                                    <span class="meta-item duration"><img loading="lazy" class="resp-img" src="{{cdn('/theme/assets/images/icons/Times.svg')}}" alt="Times_icon" title="Times_icon" /><?= date( "H:i", strtotime($lesso['pivot']['time_starts']) ) ?> ({{$lesso['pivot']['duration']}})</span> <!-- Feedback 18-11 changed -->
                                    <span class="meta-item duration"><img loading="lazy" class="resp-img" src="{{cdn('/theme/assets/images/icons/icon-marker.svg')}}" alt="icon-marker" title="icon-marker"/>@if(isset($lesso['pivot']['location_url']) && $lesso['pivot']['location_url']) <a href="{{$lesso['pivot']['location_url']}}" target="_blank"> {{$lesso['pivot']['room']}} </a> @else {{$lesso['pivot']['room']}} @endif</span> <!-- Feedback 18-11 changed -->
                                  </div>
                                  <!-- /.topic-title-meta -->
                                </div>
                                <div class="author-img">
                                  <!-- Feedback 18-11 changed -->
                                  <a href="{{$instructors[$lesso['instructor_id']][0]['slugable']['slug']}}">
                                    <span class="custom-tooltip"><?= $instructors[$lesso['instructor_id']][0]['title'].' '.$instructors[$lesso['instructor_id']][0]['subtitle']; ?></span>
                                      <?php
                                      $imageDetails = get_image_version_details('users');
                                      ?>
                                    <img loading="lazy" class="resp-img" src="{{cdn( get_image($instructors[$lesso['instructor_id']][0]['mediable'],'users') )}}" width="{{ $imageDetails['w'] }}" height="{{ $imageDetails['h'] }}" title="<?= $instructors[$lesso['instructor_id']][0]['title']; $instructors[$lesso['instructor_id']][0]['subtitle']; ?> alt="<?= $instructors[$lesso['instructor_id']][0]['title']; $instructors[$lesso['instructor_id']][0]['subtitle']; ?>"/>
                                  </a>
                                </div>
                                <!-- /.topic-wrapper-big -->
                              </div>
                            @endforeach
                            <!-- /.accordion-content -->
                          </div>
                          <!-- /.accordion-item -->
                        </div>
                      @endif
                    @endforeach
                    <!-- /.accordion-wrapper -->
                  </div>
                @endif
                <!-- /.acc-topic-accordion -->
              </div>
            @endif

          </div>

            <?php

            $now1 = strtotime(date("Y-m-d"));
            $display = false;
            if(!isset($event['release_date_files'])){
              $display = false;
            }
            else if(!$event['release_date_files'] && $event['status'] == App\Model\Event::STATUS_COMPLETED){
              $display = true;

            }else if((!$releaseDateIsSet || strtotime(date('Y-m-d',strtotime($event['release_date_files']))) >= $now1) && $event['status'] == App\Model\Event::STATUS_COMPLETED){

              $display = true;
            }

            ?>


          @if(isset($event['dropbox']))
            <div id="c-files-inner{{$tab}}" class="in-tab-wrapper">
                <?php
              foreach($event['dropbox'] as $dropbox){
                $folders = isset($dropbox['folders'][0]) ? $dropbox['folders'][0] : [];
                $selectedFiles = [];
                if(isset($dropbox['pivot']['selectedFolders'])){
                  $selectedFiles = $dropbox['pivot']['selectedFolders'];
                  $selectedFiles = json_decode($selectedFiles, true);
                }


                $folders_bonus = isset($dropbox['folders'][1]) ? $dropbox['folders'][1] : [];
                //dd($folders_bonus);
                $files = isset($dropbox['files'][1]) ? $dropbox['files'][1] : [];
                $files_bonus = isset($dropbox['files'][2]) ? $dropbox['files'][2] : [];





                ?>
              @if($display)
                <div class="acc-topic-accordion">
                  <div class="accordion-wrapper accordion-big">
                    @if(isset($folders) && count($folders) > 0)
                      <div class="accordion-item">
                        @foreach($folders as $folder)
                            <?php
                            $folderIsSelected = false;
                            if(isset($selectedFiles['selectedAllFolders']))
                            {
                              if($selectedFiles['selectedAllFolders']){
                                $folderIsSelected = true;
                              }else{
                                foreach($selectedFiles['selectedFolders'] as $key10 => $selectedFile){
                                  if($folder['dirname'] == $selectedFile){
                                    $folderIsSelected = true;
                                  }
                                }
                              }
                            }


                            $checkedF = [];
                            $fs = [];
                            $fk = 1;
                            $bonus = [];
                            $subfolder = [];
                            $subfiles = [];
                            ?>
                          <div class="accordion-item d-none">
                            <h3 class="accordion-title title-blue-gradient scroll-to-top"> {{ $folder['foldername'] }}</h3>
                            <div class="accordion-content no-padding">
                              @if(isset($files) && count($files) > 0)
                                @foreach($folders_bonus as $folder_bonus)
                                  @if(isset($bonusFiles) && isset($folder_bonus['parent']) && $folder_bonus['parent'] == $folder['id']  && !in_array($folder_bonus['foldername'],$bonusFiles))
                                      <?php
                                      $checkedF[] = $folder_bonus['id'] + 1 ;
                                      $fs[$folder_bonus['id']+1]=[];
                                      $fs[$folder_bonus['id']+1] = $folder_bonus;

                                      ?>
                                  @endif
                                @endforeach
                                @if(count($fs) > 0)

                                  @foreach($fs as $subf)
                                    @foreach($files_bonus as $folder_bonus)
                                        <?php
                                        if(in_array($subf['foldername'],$subfolder)){
                                          continue;
                                        }
                                        ?>
                                      @if(isset($folder_bonus['parent']) && $folder_bonus['parent'] == $folder['id'])

                                          <?php $folderIsSelected = false; ?>
                                        @if(isset($selectedFiles['selectedAllFolders']))
                                          @if($selectedFiles['selectedAllFolders'])
                                              <?php $folderIsSelected = true; ?>
                                          @else
                                            @foreach($selectedFiles['selectedFolders'] as $key10 => $selectedFile)
                                              @if($folder_bonus['dirname'] == $selectedFile)
                                                  <?php $folderIsSelected = true; ?>
                                              @endif
                                            @endforeach
                                          @endif
                                        @endif

                                          <?php $subfolder[] =  $subf['foldername']; ?>
                                        <div class="files-wrapper bonus-files d-none">
                                          <h4 class="bonus-title">{{ $subf['foldername'] }}</h4>
                                          <span><i class="icon-folder-open"></i>   </span>
                                          @foreach($files_bonus as $file_bonus)
                                            @if(isset($file_bonus['parent']) && $file_bonus['fid'] == $subf['id'] && $file_bonus['parent'] == $subf['parent'] )

                                              @if($folderIsSelected)
                                                  <?php $subfiles[]= $file_bonus['filename'] ?>
                                                <div class="file-wrapper">
                                                  <h4 class="file-title">{{ $file_bonus['filename'] }}</h4>
                                                  <span class="last-modified">Last modified:  {{$file_bonus['last_mod']}}</span>
                                                  <a  class="download-file getdropboxlink"  data-dirname="{{ $file_bonus['dirname'] }}" data-filename="{{ $file_bonus['filename'] }}" href="javascript:void(0)" >
                                                    <img loading="lazy" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" class="resp-img" width="34" height="34" alt="Download File" title="Download File"/></a>
                                                </div>
                                              @else
                                                @if(isset($selectedFiles['selectedFolders']))
                                                  @foreach($selectedFiles['selectedFolders'] as $key10 => $selectedFile)

                                                    @if($file_bonus['dirname'] == $selectedFile)
                                                        <?php $subfiles[]= $file_bonus['filename'] ?>
                                                      <div class="file-wrapper">
                                                        <h4 class="file-title">{{ $file_bonus['filename'] }}</h4>
                                                        <span class="last-modified">Last modified:  {{$file_bonus['last_mod']}}</span>
                                                        <a  class="download-file getdropboxlink"  data-dirname="{{ $file_bonus['dirname'] }}" data-filename="{{ $file_bonus['filename'] }}" href="javascript:void(0)" >
                                                          <img loading="lazy" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" class="resp-img" width="34" height="34" alt="Download File" title="Download File"/></a>
                                                      </div>
                                                    @endif
                                                  @endforeach
                                                @endif
                                              @endif


                                            @endif
                                          @endforeach
                                        </div>
                                      @endif
                                    @endforeach
                                  @endforeach
                                @endif
                                @foreach($files as $file)
                                  @if($folder['id'] == $file['fid'])

                                    @if($folderIsSelected)
                                      <div class="files-wrapper no-bonus">
                                        <div class="file-wrapper">
                                          <h4 class="file-title">{{ $file['filename'] }}</h4>
                                          <span class="last-modified">Last modified:  {{ $file['last_mod'] }}</span>
                                          <a  class="download-file getdropboxlink"  data-dirname="{{ $file['dirname'] }}" data-filename="{{ $file['filename'] }}" href="javascript:void(0)" >
                                            <img loading="lazy" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" class="resp-img" width="34" height="34" alt="Download File" title="Download File"/></a>
                                        </div>
                                      </div>
                                    @else
                                      @if(isset($selectedFiles['selectedFolders']))
                                        @foreach($selectedFiles['selectedFolders'] as $key10 => $selectedFile)
                                          @if($file['dirname'] == $selectedFile)
                                            <div class="files-wrapper no-bonus">
                                              <div class="file-wrapper">
                                                <h4 class="file-title">{{ $file['filename'] }}</h4>
                                                <span class="last-modified">Last modified:  {{ $file['last_mod'] }}</span>
                                                <a  class="download-file getdropboxlink"  data-dirname="{{ $file['dirname'] }}" data-filename="{{ $file['filename'] }}" href="javascript:void(0)" >
                                                  <img loading="lazy" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" class="resp-img" width="34" height="34" alt="Download File" title="Download File"/></a>
                                              </div>
                                            </div>
                                          @endif
                                        @endforeach
                                      @endif
                                    @endif
                                  @endif
                                @endforeach
                              @endif
                              @if(isset($folders_bonus) && count($folders_bonus) > 0)
                                <div class="files-wrapper bonus-files d-none">
                                  @foreach($folders_bonus as $folder_bonus)
                                      <?php
                                      if(in_array($folder_bonus['foldername'],$subfolder)){
                                        continue;
                                      }
                                      ?>
                                    @if(isset($folder_bonus['parent']) && $folder_bonus['parent'] == $folder['id'])

                                        <?php $folderIsSelected = false; ?>
                                      @if(isset($selectedFiles['selectedFolders']))
                                        @if($selectedFiles['selectedAllFolders'])
                                            <?php $folderIsSelected = true; ?>
                                        @else
                                          @foreach($selectedFiles['selectedFolders'] as $key10 => $selectedFile)
                                            @if($folder_bonus['dirname'] == $selectedFile)
                                                <?php $folderIsSelected = true; ?>
                                            @endif
                                          @endforeach
                                        @endif
                                      @endif

                                      <h4 class="bonus-title">{{ $folder_bonus['foldername'] }}</h4>
                                      <span><i class="icon-folder-open"></i>   </span>
                                      @if(isset($files_bonus) && count($files_bonus) > 0)
                                        @foreach($files_bonus as $file_bonus)
                                          @if(isset($file_bonus['parent']) && $file_bonus['parent'] == $folder_bonus['parent'] && !in_array($file_bonus['filename'],$subfiles))

                                            @if($folderIsSelected)
                                              <div class="file-wrapper">
                                                <h4 class="file-title">{{ $file_bonus['filename'] }}</h4>
                                                <span class="last-modified">Last modified:  {{$file_bonus['last_mod']}}</span>
                                                <a  class="download-file getdropboxlink"  data-dirname="{{ $file_bonus['dirname'] }}" data-filename="{{ $file_bonus['filename'] }}" href="javascript:void(0)" >
                                                  <img loading="lazy" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" class="resp-img" width="34" height="34" alt="Download File" title="Download File"/></a>
                                              </div>
                                            @else
                                              @if(isset($selectedFiles['selectedFolders']))
                                                @foreach($selectedFiles['selectedFolders'] as $key10 => $selectedFile)
                                                  @if($file_bonus['dirname'] == $selectedFile)
                                                    <div class="file-wrapper">
                                                      <h4 class="file-title">{{ $file_bonus['filename'] }}</h4>
                                                      <span class="last-modified">Last modified:  {{$file_bonus['last_mod']}}</span>
                                                      <a  class="download-file getdropboxlink"  data-dirname="{{ $file_bonus['dirname'] }}" data-filename="{{ $file_bonus['filename'] }}" href="javascript:void(0)" >
                                                        <img loading="lazy" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" class="resp-img" width="34" height="34" alt="Download File" title="Download File"/></a>
                                                    </div>
                                                  @endif
                                                @endforeach
                                              @endif
                                            @endif

                                          @endif
                                        @endforeach
                                      @endif
                                    @endif
                                  @endforeach
                                </div>
                              @endif
                            </div>
                          </div>
                        @endforeach
                        {{--</div>--}}
                      </div>
                    @endif
                  </div>
                </div>
              @endif
              <?php } ?>
            </div>
          @endif
          @if(isset($event['exams']) && count($event['exams']) >0 )
              <?php $nowTime = \Carbon\Carbon::now(); ?>
            <div id="c-exams-inner{{$tab}}" class="in-tab-wrapper">
              <div class="dynamic-courses-wrapper dynamic-courses-wrapper--style2">
                <div class="bottom">
                  <div class="location">
                    @php
                      $isExamStart = false;
                      foreach($event['exams'] as $p) {
                        $userExam = isset($user['hasExamResults'][$p->id][0]) ? $user['hasExamResults'][$p->id][0] : null;
                        if ($userExam) {
                          $isExamStart = true;
                        } elseif($p->islive == 1) {
                          $isExamStart = true;
                        }
                      }
                    @endphp
                    @if(!$isExamStart)
                      <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Customer_Access.svg')}}" title="customer_Access_icon" alt="customer_Access_icon">
                      Exams will activate in the end of your course.
                    @endif
                    @foreach($event['exams'] as $p)
                        <?php $userExam = isset($user['hasExamResults'][$p->id][0]) ? $user['hasExamResults'][$p->id][0] : null ?>
                      @if($userExam)
                        <div>
                          <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Level.svg')}}" alt="Customer_Access_icon" title="Customer_Access_icon">
                          Your latest exam result: <b>{{ round($userExam->score * 100 / $userExam->total_score, 2) }} %</b>
                        </div>
                      @endif
                    @endforeach
                  </div>

                  @if(!$allInstallmentsPayed)
                    <div style="color: red;">
                      <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/icon-remove.svg')}}" alt="Pay all the installments" title="Pay all the installments">
                      {{ 'You need to pay all installments before taking the exam' }}
                    </div>
                  @endif

                  @foreach($event['exams'] as $p)
                    <div class="right">
                      <!-- Feedback 8-12 changed -->

                        <?php $userExam = isset($user['hasExamResults'][$p->id][0]) ? $user['hasExamResults'][$p->id][0] : null ?>

                      @if( $userExam  && $nowTime->diffInHours($userExam->end_time) < 48)
                        <a target="_blank" href="{{ route('exam-results', [$p->id,'s'=>1]) }}" title="{{$p['title']}}" class="btn btn--secondary btn--md">VIEW RESULT</a>
                      @elseif($userExam )
                        <a target="_blank" href="{{ url('exam-results/' . $p->id) }}?s=1" title="{{$p['title']}}" class="btn btn--secondary btn--md btn--completed">VIEW RESULT</a>
                      @elseif($p->islive == 1)
                        @if($allInstallmentsPayed)
                          <a target="_blank" onclick="window.open('{{ route('attempt-exam', [$p->id]) }}', 'newwindow', 'width=1400,height=650'); return false;" title="{{$p['title']}}" class="btn btn--secondary btn--md">TAKE EXAM</a>
                        @else
                          <a target="_blank" title="{{$p['title']}}" class="btn btn--secondary btn--md btn--completed">TAKE EXAM</a>
                        @endif
                      @elseif($p->isupcom == 1)
                        <a  title="{{$p['title'] }}" class="btn btn--secondary btn--md">{{ date('F j, Y', strtotime($p->publish_time)) }}</a>
                      @endif
                    </div>
                    <!-- ./item -->
                  @endforeach
                </div>
              </div>
              <!-- ./dynamic-courses-wrapper -->
            </div>
          @endif

          @if(count($event['certs']) > 0)
            <div id="c-cert-inner{{$tab}}" class="in-tab-wrapper">
              <div class="bottom">
                @if($allInstallmentsPayed)
                  <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" title="customer_Access_icon" alt="Access-Files-icon">@if(isset($newlayoutExamsEvent[$keyType]) && count($newlayoutExamsEvent[$keyType])>0)Certificate download after completing your exams. @else Your certification is ready @endif</div>
                  @foreach($event['certs']->sortByDesc('id') as $certificate)
                      <?php
                      $expirationMonth = '';
                      $expirationYear = '';
                      $certUrl = trim(url('/') . '/mycertificate/' . base64_encode(Auth::user()->email."--".$certificate->id));
                      if($certificate->expiration_date){
                        $expirationMonth = date('m',$certificate->expiration_date);
                        $expirationYear = date('Y',$certificate->expiration_date);
                      }

                      $certiTitle = preg_replace( "/\r|\n/", " ", $certificate->certificate_title );

                      if(strpos($certificate->certificate_title, '</p><p>')){
                        $certiTitle = substr_replace($certificate->certificate_title, ' ', strpos($certificate->certificate_title, '</p>'), 0);
                      }else{
                        $certiTitle = $certificate->certificate_title;
                      }
                      $certiTitle = str_replace('&nbsp;',' ',$certiTitle);
                      $certiTitle = urlencode(htmlspecialchars_decode(strip_tags($certiTitle),ENT_QUOTES));
                      ?>
                    <div class="right">
                      <a  class="btn btn--secondary btn--md" target="_blank" href="/mycertificate/{{base64_encode(Auth::user()->email.'--'.$certificate->id)}}" >DOWNLOAD </a>
                      <a class="linkedin-post cert-post 11"  target="_blank" href="https://www.linkedin.com/profile/add?startTask={{$certiTitle}}&name={{$certiTitle}}&organizationId=3152129&issueYear={{date('Y',$certificate->create_date)}}
                                                          &issueMonth={{date('m',$certificate->create_date)}}&expirationYear={{$expirationYear}}&expirationMonth={{$expirationMonth}}&certUrl={{$certUrl}}&certId={{$certificate->credential}}">
                        <img loading="lazy" class="linkdein-image-add resp-img" width="36" height="36" src="{{cdn('theme/assets/images/icons/social/events/Linkedin.svg')}}"  title="LinkedIn Add to Profile button" alt="LinkedIn Add to Profile button">
                      </a>
                      <a class="facebook-post-cert" data-certTitle="{{$certiTitle}}" data-certid="{{base64_encode(Auth::user()->email.'--'.$certificate->id)}}" title="Add this certification to your Facebook profile" href="javascript:void(0)">
                        <img loading="lazy" class="linkdein-image-add resp-img" width="36" height="36" src="{{cdn('theme/assets/images/icons/social/events/Facebook.svg')}}" title="Facebook Add to Profile button" alt="Facebook Add to Profile button">
                      </a>
                    </div>
                    @break
                  @endforeach
                @else
                  <div style="color: red;">
                    <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/icon-remove.svg')}}" alt="Pay all the installments" title="Pay all the installments">
                    {{ 'You need to pay all installments before getting the certificate' }}
                  </div>
                @endif
              </div>
            </div>
          @endif
        </div>
      </div>
      @if((isset($event['paid']) && $event['paid'] == 0 && isset($event['transactionPending']) && $event['transactionPending'] == 2) || (isset($event['transactionPendingSepa']) && $event['transactionPendingSepa'] == 1))
        <div class="pendingSepaMessage d-none">
          <h3>Your course will be available once the SEPA payment has cleared. This usually takes a few days.</h3>
        </div>
      @elseif(isset($event['paid']) && $event['paid'] == 0)
        <div class="unpaidMessage d-none">
          <h3>You have an unpaid amount for this course. Please contact us to arrange payment and retrieve your access.</h3>
        </div>

      @endif
    </div>
  </div>
@else
<div class="col12 dynamic-courses-wrapper @if((isset($event['paid']) && $event['paid'] == 0 && isset($event['transactionPending']) && $event['transactionPending'] == 2) || (isset($event['transactionPendingSepa']) && $event['transactionPendingSepa'] == 1)){{'pendingSepa'}}@elseif(isset($event['paid']) && $event['paid'] == 0){{'unpaid'}}@endif">
    <div class="item">
      <div class="title-and-sub">
        <h2>{{ $event['title'] }} </h2>
        @if(isset($event['mySubscription']) && !empty($event['mySubscription']))
            <?php
            $a = '';
            $status = '';
            $row_status = '';
            $showToggle = isset($event['mySubscription']['stripe_status']) && $event['mySubscription']['stripe_status'] == 'active' ? true : false;
            if($event['mySubscription']['status']) {
              $a = 'checked';
              $status = 'Active';
            } else {
              $status = 'Cancel';
            }
            ?>
          <div class="subscription-toggle {{ $event['mySubscription']['stripe_status'] == 'unpaid' ? 'unpaid' : '' }}">
            <div class="onoffswitch" data-status="{{$status}}" data-id="{{$event['mySubscription']['id']}}" id="onoffswitch">
              <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" tabindex="0" <?php echo $a; ?>>
              <label class="onoffswitch-label" for="myonoffswitch">
                <span class="onoffswitch-inner"></span>
                <span class="onoffswitch-switch"></span>
              </label>
            </div>
          </div>
        @endif
      </div>
      <div class="inside-tabs">
        <div class="tabs-ctrl">
          <ul>
            <li class="active"><a href="#c-watch-inner{{$tab}}">Watch</a></li>


            @if(isset($event['exams']) && count($event['exams']) >0 )
              <li><a href="#c-exams-inner{{$tab}}">Exams</a></li>
            @endif

            @if(count($event['certs']) > 0)
              <li><a href="#c-cert-inner{{$tab}}">Certificate</a></li>
            @endif

            @if(isset($event['mySubscription']) && $event['mySubscription'])
              <li><a href="#c-subs-inner{{$tab}}">Subscription</a></li>
            @endif
          </ul>
        </div>
        <div class="inside-tabs-wrapper">
          @if(isset($event['mySubscription']) && $event['mySubscription'])
            <div id="c-subs-inner{{$tab}}" class="in-tab-wrapper">
              <div class="bottom">
                @if($event['mySubscription'])
                  <div class="left">
                    <div class="bottom">
                      @if($event['mySubscription']['trial_ends_at'])
                          <?php
                          $date_timestamp = strtotime($event['mySubscription']['trial_ends_at']);
                          $now_date = strtotime(date('d/m/Y'));
                          $date = date('d-m-Y',$date_timestamp);
                          ?>
                        @if($date_timestamp > $now_date )
                          <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/clock-coins.svg')}}" alt="clock-coins-icon" title="clock-coins-icon"><?php echo 'Your trial expiration: '.$date; ?></div>
                          @if($event['mySubscription']['status'])
                            <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt="credit-icon" title="credit-icon">Your subscription is active. Expiration date: {{ $event['expiration'] ?? date('d M Y',$event['mySubscription']['must_be_updated']) }}. On that date you will be automatically billed €{{ intval($event['mySubscription']['price']) }} unless you turn it off anytime during this period.</div>
                          @endif
                        @else
                          @if($event['mySubscription'])
                            @if ($event['mySubscription']['stripe_status'] === 'unpaid')
                              <div class="location mb-1"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt="credit-icon" title="credit-icon">We were unable to update your subscription due to non-payment. Please <span class="font-weight-bold">re-subscribe</span> and complete the payment process.</div>
                            @else
                              <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt="credit-icon" title="credit-icon">Your subscription is inactive. Access available until: {{ $event['expiration'] ?? date('d M Y',$event['mySubscription']['must_be_updated']) }}. You will not be charged again. Turn it on to continue having access and to be charged on expiration date.</div>
                            @endif
                          @endif
                        @endif
                      @else
                          <?php
                          $date_timestamp = strtotime($event['mySubscription']['trial_ends_at']);
                          $now_date = strtotime(date('d/m/Y'));
                          $date = date('d-m-Y',$date_timestamp);
                          ?>
                        @if($date_timestamp > $now_date )
                          @if($event['mySubscription']['status'])
                            <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt="credit-icon" title="credit-icon">Your subscription is active. Expiration date: {{ $event['expiration'] ?? date('d M Y',$event['mySubscription']['must_be_updated']) }}. On that date you will be automatically billed €{{ intval($event['mySubscription']['price']) }} unless you turn it off anytime during this period.</div>
                          @endif
                        @else
                          @if(isset($event['mySubscription']))
                            @if($event['mySubscription']['status'])
                              <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt="credit-icon" title="credit-icon">Your subscription is active. Expiration date: {{ $event['expiration'] ?? date('d M Y',$event['mySubscription']['must_be_updated']) }}. On that date you will be automatically billed €{{ intval($event['mySubscription']['price']) }} unless you turn it off anytime during this period.</div>
                            @else
                              @if ($event['mySubscription']['stripe_status'] === 'unpaid')
                                <div class="location mb-1"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt="credit-icon" title="credit-icon">Your subscription is inactive due to non-payment on time. Access available until: {{ $event['expiration'] ?? date('d M Y',$event['mySubscription']['must_be_updated']) }}. You need to buy it again if you wish to continue having access.</div>
                              @else
                                <div class="location" style="display: block;"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Credit card, Check, Done.svg')}}" alt="credit-icon" title="credit-icon">Your subscription is inactive. Access available until: {{ $event['expiration'] ?? date('d M Y',$event['mySubscription']['must_be_updated']) }}. You will not be charged again. Turn it on to continue having access and to be charged on expiration date.</div>
                              @endif
                            @endif
                          @endif
                        @endif
                      @endif
                    </div>
                  </div>
                @endif
                @if(!$event['mySubscription'])
                  <div class="left">
                    <div  class="duration"><img class="replace-with-svg" width="20" src="{{ cdn('/theme/assets/images/icons/checklist-graduate-hat.svg') }}" alt="">Get annual access to updated videos & files. 15 days free trial.</div>
                  </div>
                @endif
                <div class="right">
                  @if(!$event['mySubscription'])
                    @foreach($event['plans'] as $key => $plan)
                      <a href="/myaccount/subscription/{{$event['title']}}/{{ $plan->name }}" class="btn btn--secondary btn--md">SUBSCRIBE NOW</a>
                    @endforeach
                  @endif
                </div>
              </div>
            </div>
          @endif
          <div id="c-watch-inner{{$tab}}" class="in-tab-wrapper" style="display: block;">

            @if(!$requiredInstalmentIsPaidAndEvenIsAvailable)
              <div style="color: red; margin-bottom: 5px;">
                {{ 'You need to pay the installment to resume access to the course.' }}
              </div>
            @endif

            <div class="bottom">
              @if (isset($event['hours']))
                <div  class="duration"><img loading="lazy" class="replace-with-svg resp-img" height="20" width="20" onerror="this.src='{{cdn('/theme/assets/images/icons/Start-Finish.svg')}}'"  src="{{cdn($event['hours_icon'])}}" title="hours" alt="hours"> {{$event['hours']}}h </div>
              @endif
              @if (isset($event['videos_progress']))
                <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/E-Learning.svg')}}" alt="elearning" title="elearning"> {{$event['videos_progress']}}% </div>
              @endif
              @if (isset($event['videos_seen']))
                <div  class="duration"><img class="replace-with-svg" width="20" src="{{cdn('/theme/assets/images/icons/Recap-Events.svg')}}" alt="recap-events" title="recap-events"> {{str_replace('/','of',$event['videos_seen'])}} </div>
              @endif
              @if(isset($event['expiration']) && $event['expiration'])
                <div class="expire-date exp-date"><img src="{{cdn('/theme/assets/images/icons/Days-Week.svg')}}" alt="days-week">Expiration date: {{$event['expiration']}}</div>
              @endif
              <div class="right">
                  <?php $expire = false; ?>
                @foreach($mySubscriptions as $key => $sub)
                  @foreach($plans as $key1 => $plan)
                      <?php //dd($plan['stipe_plan']); ?>
                    @if($sub['stripe_plan'] == $plan['stripe_plan'])
                      @if($event['id'] == $plan['event_id'])
                          <?php
                          if(date("Y-m-d h:i:s") < $sub['must_be_updated']){
                            $expire = false;
                          }else{
                            $expire = true;
                          }
                          ?>
                      @endif
                    @endif
                  @endforeach
                @endforeach

                @if($event['view_tpl'] == 'elearning_free' && $event['status'] == App\Model\Event::STATUS_OPEN && isset($event['expiration']) && strtotime($event['expiration']) < strtotime(now()))
                  <a href="{{ $event['slugable']['slug'] }}" class="btn btn--secondary btn--md">RENROLL FOR FREE</a>
                @elseif($event['view_tpl'] != 'elearning_free' && $event['status'] == App\Model\Event::STATUS_OPEN && isset($event['expiration'])  &&strtotime($event['expiration']) < strtotime(now()) && (isset($event['video_access']) && !$event['video_access']))
                  @if(!isset($event['mySubscription']))
                    <a href="{{ $event['slugable']['slug'] }}" class="btn btn--primary btn--md">{{ $eventTicketPrice ? 'REENROLL FOR €' . $eventTicketPrice : 'REENROLL' }}</a>
                  @else
                    <a href="{{ $subscriptionReenrolLink }}" class="btn btn--primary btn--md">REENROLL FOR € {{ intval($event['mySubscription']['price']) }}</a>
                  @endif
                @endif

                @if(isset($event['video_access']) && !$event['video_access'])
                @elseif (!$requiredInstalmentIsPaidAndEvenIsAvailable)

                @else
                  <a href="/myaccount/elearning/{{ $event['title'] }}" <?= isset($event['status']) && $event['status'] == App\Model\Event::STATUS_WAITING ? 'disabled' : ''; ?> class="btn btn--secondary btn--md">

                    @if(isset($event['status']) && $event['status'] == App\Model\Event::STATUS_WAITING)
                      WAITING LIST
                    @elseif( (isset($event['videos_progress']) && $event['videos_progress'] == 100) )
                      WATCH AGAIN
                    @else
                      WATCH NOW
                    @endif

                  </a>
                @endif
              </div>
            </div>
          </div>

          @if(isset($event['exams']))
              <?php $nowTime = \Carbon\Carbon::now(); ?>
            <div id="c-exams-inner{{$tab}}" class="in-tab-wrapper">
              <div class="bottom">
                @foreach($event['exams'] as $p)
                    <?php $userExam = isset($user['hasExamResults'][$p->id][0]) ? $user['hasExamResults'][$p->id][0] : null ?>
                  <div class="location">
                    @if($userExam)
                      <div>
                        <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Level.svg')}}" alt="Customer_Access_icon" title="Customer_Access_icon">
                        Your latest exam result: <b>{{ round($userExam->score * 100 / $userExam->total_score, 2) }} %</b>
                      </div>
                    @endif
                    <div>
                      <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Customer_Access.svg')}}" alt="Customer_Access_icon" title="Customer_Access_icon">
                      @if(isset($event['exam_activate_months']) && $event['exam_activate_months'] != null)
                        {{ 'Exams activate automatically after' }} {{ $event['exam_activate_months'] }} {{'months after your enrollment' }}
                      @else
                        {{ 'Exams activate automatically after 80% progress' }}
                      @endif
                    </div>
                    @if(!$allInstallmentsPayed)
                      <div style="color: red;">
                        <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/icon-remove.svg')}}" alt="Pay all the installments" title="Pay all the installments">
                        {{ 'You need to pay all installments before taking the exam' }}
                      </div>
                    @endif
                  </div>
                  <div class="right">
                    <!-- Feedback 8-12 changed -->
                    @if($event['exam_access'] && !$userExam)
                      @if($p->islive == 1)
                        @if($allInstallmentsPayed)
                          <a target="_blank" onclick="window.open('{{ route('attempt-exam', [$p->id]) }}', 'newwindow', 'width=1400,height=650'); return false;" title="{{$p['exam_name']}}" class="btn btn--secondary btn--md">TAKE EXAM</a>
                        @else
                          <a href="javascript:void(0)" title="{{$p['exam_name']}}" class="btn btn--secondary btn--md btn--completed">TAKE EXAM</a>
                        @endif
                      @endif
                    @elseif($userExam)
                      @if($nowTime->diffInHours($userExam->end_time) < 48)
                        <a target="_blank" href="{{ url('exam-results/' . $p->id) }}?s=1" title="{{$p['exam_name']}}" class="btn btn--secondary btn--md">VIEW RESULT</a>
                      @else
                        <a target="_blank" href="{{ url('exam-results/' . $p->id) }}?s=1" title="{{$p['exam_name']}}" class="btn btn--secondary btn--md btn--completed">VIEW RESULT</a>
                      @endif
                    @else
                      <div class="right">
                        <a href="javascript:void(0)" title="{{$p['exam_name']}}" class="btn btn--secondary btn--md btn--completed">TAKE EXAM</a>
                      </div>
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          @endif

          @if(count($event['certs']) > 0)
            <div id="c-cert-inner{{$tab}}" class="in-tab-wrapper">
              <div class="bottom">
                @if($allInstallmentsPayed)
                  <div class="location"><img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/Access-Files.svg')}}" alt="Access-Files-icon" title="Access-Files-icon">@if(isset($newlayoutExamsEvent[$keyType]) && count($newlayoutExamsEvent[$keyType])>0)Certificate download after completing your exams. @else Your certification is ready @endif</div>
                  @foreach($event['certs'] as $certificate)
                      <?php
                      $expirationMonth = '';
                      $expirationYear = '';
                      $certUrl = trim(url('/') . '/mycertificate/' . base64_encode(Auth::user()->email."--".$certificate->id));
                      if($certificate->expiration_date){
                        $expirationMonth = date('m',$certificate->expiration_date);
                        $expirationYear = date('Y',$certificate->expiration_date);
                      }

                      if(strpos($certificate->certificate_title, '</p><p>')){
                        $certiTitle = substr_replace($certificate->certificate_title, ' ', strpos($certificate->certificate_title, '</p>'), 0);
                      }else{
                        $certiTitle = $certificate->certificate_title;
                      }
                      $certiTitle = str_replace('&nbsp;',' ',$certiTitle);
                      $certiTitle = urlencode(htmlspecialchars_decode(strip_tags($certiTitle),ENT_QUOTES));

                      ?>
                    <div class="right">

                      <a  class="btn btn--secondary btn--md asd" target="_blank" href="/mycertificate/{{base64_encode(Auth::user()->email.'--'.$certificate->id)}}" >DOWNLOAD </a>
                      <a class="linkedin-post cert-post 22"  target="_blank" href="https://www.linkedin.com/profile/add?startTask={{$certiTitle}}&name={{$certiTitle}}&organizationId=3152129&issueYear={{date('Y',$certificate->create_date)}}
                                                    &issueMonth={{date('m',$certificate->create_date)}}&expirationYear={{$expirationYear}}&expirationMonth={{$expirationMonth}}&certUrl={{$certUrl}}&certId={{$certificate->credential}}">
                        <img loading="lazy" class="linkdein-image-add resp-img" width="36" height="36" src="{{cdn('theme/assets/images/icons/social/events/Linkedin.svg')}}" alt="LinkedIn Add to Profile button" title="LinkedIn Add to Profile button">
                      </a>
                      <a class="facebook-post-cert cert-post" data-certTitle="{{$certiTitle}}" data-certid="{{base64_encode(Auth::user()->email.'--'.$certificate->id)}}" title="Add this certification to your Facebook profile" href="javascript:void(0)">
                        <img loading="lazy" class="linkdein-image-add resp-img" width="36" height="36" src="{{cdn('theme/assets/images/icons/social/events/Facebook.svg')}}" alt="Facebook Add to Profile button" title="Facebook Add to Profile button">
                      </a>
                    </div>

                  @endforeach
                @else
                  <div style="color: red;">
                    <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/icon-remove.svg')}}" alt="Pay all the installments" title="Pay all the installments">
                    {{ 'You need to pay all installments before getting the certificate' }}
                  </div>
                @endif
              </div>
            </div>
          @endif

        </div>
      </div>
      @if((isset($event['paid']) && $event['paid'] == 0 && isset($event['transactionPending']) && $event['transactionPending'] == 2) || (isset($event['transactionPendingSepa']) && $event['transactionPendingSepa'] == 1))
        <div class="pendingSepaMessage d-none">
          <h3>Your course will be available once the SEPA payment has cleared. This usually takes a few days.</h3>
        </div>
      @elseif(isset($event['paid']) && $event['paid'] == 0)
        <div class="unpaidMessage d-none">
          <h3>You have an unpaid amount for this course. Please contact us to arrange payment and retrieve your access.</h3>
        </div>

      @endif
      <!-- ./item -->
    </div>
  </div>
@endif

@section('css')
  <style>
    .title-and-sub {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .subscription-toggle .onoffswitch {
      width: 175px !important;
    }

    .subscription-toggle .onoffswitch .onoffswitch-inner::before {
      content: "SUBSCRIPTION ON";
      padding-left: 10px;
      background-color: #92cc16;
      color: #FFFFFF;
    }

    .subscription-toggle .onoffswitch .onoffswitch-inner::after {
      content: "SUBSCRIPTION OFF";
      padding-right: 10px;
      background-color: #EEEEEE;
      color: #999999;
      text-align: right;
    }

    .unpaid .onoffswitch {
      width: 190px !important;
    }

    .unpaid .onoffswitch .onoffswitch-inner::after {
      content: "SUBSCRIPTION UNPAID";
      padding-right: unset !important;
      background-color: #cc1616;
      color: #FFFFFF;
      text-align: right;
    }
  </style>

@endsection
