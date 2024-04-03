@php
    $sections = $dynamic_page_data["sections"] ?? null;
    $topics = $dynamic_page_data["topics"] ?? null;
    $instructors = $dynamic_page_data["instructors"] ?? null;
    $title = '';
    $body = '';
    if(isset($sections['topics'])){
        $title = $sections['topics']->first()->title ?? null;
        $body = $sections['topics']->first()->description ?? null;
    }

    $isElearning = $event->is_elearning_course();

@endphp

<div class="course-full-text mt-5 mb-5">
    <h2 class="text-align-center text-xs-left tab-title"> {!! $title !!}</h2>
    <div class="topic-text-area">
        {!! $body !!}
    </div>
    @if(isset($topics))
        <div class="tab-faq-wrapper topic-content">
            <div class="accordion-wrapper accordion-big">

                @foreach($topics as $key => $topic)
                    <div class="accordion-item topic-header">
                      <div class="accordion-title topic-info title-blue-gradient scroll-to-top">
                        <h3 class="topic-info_title"> {!! $key !!}</h3>
                        <div class="topic-info_duration">
                           <?php
                           $d = 0;
                           $showMintues = true;
                           if (!empty($topic['topic_duration'])) {
                             $d = intval($topic['topic_duration']);
                           } elseif (!empty($topic['lessons'])) {
                             $showMintues = false;
                             foreach ($topic['lessons'] as $lesson) {
                               $text = strtolower($lesson['pivot']['duration']);
                               preg_match('/([0-9]+)h/', $text, $m);
                               if ($m && !empty($m[1])) {
                                 $d += $m[1] * 60 * 60;
                               }
                               preg_match('/([0-9]+)m/', $text, $m);
                               if ($m && !empty($m[1])) {
                                 $d += $m[1]* 60;
                               }
                             }
                           }
                           if ($d > 0) {
                             $m = isset($d) ? floor(($d / 60) % 60) : 0;
                             $h = isset($d) ? $hours = floor($d / 3600) : 0;
                             echo intval($h) . 'h ' . ($showMintues ? $m . 'm' : '');
                           }
                           ?>
                         </div>
                      </div>

                        <div class="accordion-content">

                            @foreach($topic['lessons'] as $lkey => $lesson)
                                <div class="topic-wrapper-big">
                                    <div class="topic-title-meta">
                                        <h4 @if(isset($lesson['bold']) && $isElearning && $lesson['bold']) class="bold-topic" @endif>{!! $lesson['title'] !!}</h4>
                                        <div class="topic-meta">
                                            @if(isset($lesson['type'][0]['name']))<div class="category">{{$lesson['type'][0]['name']}}</div>@endif
                                            <?php
                                                $date = $lesson['pivot']['time_starts'] ? date('l d F Y',strtotime($lesson['pivot']['time_starts'])) : null;
                                                $time =  $lesson['pivot']['time_starts'] ? date('H:i',strtotime($lesson['pivot']['time_starts'])) : null;
                                            ?>
                                            @if($date)<span class="meta-item duration"><img loading="lazy" src="{{cdn('/theme/assets/images/icons/icon-calendar.svg')}}" width="12" height="12" alt="calendar-icon" title="calendar-icon" />{{$date}}</span>@endif
                                            @if($time)<span class="meta-item duration"><img loading="lazy" src="{{cdn('/theme/assets/images/icons/Times.svg')}}" width="12" height="12" alt="times-icon" title="times-icon"/>{{$time}} ({{$lesson['pivot']['duration']}})</span>@endif
                                            @if($lesson['pivot']['room'])<span class="meta-item duration"><img loading="lazy" src="{{cdn('/theme/assets/images/icons/icon-marker.svg')}}" width="12" height="12" alt="marker-icon" title="marker-icon"/>{{$lesson['pivot']['room']}}</span>@endif
                                            @if($lesson['vimeo_duration'])<span class="meta-item duration"><img loading="lazy" src="{{cdn('/theme/assets/images/icons/Times.svg')}}" width="12" height="12" alt="times-icons" title="times-icons" />{{$lesson['vimeo_duration']}}</span>@endif

                                        </div>
                                    </div>
                                    @if(isset($instructors[$lesson['instructor_id']]) )
                                    <div class="author-img">
                                        <?php
                                            $instructor = reset($instructors[$lesson['instructor_id']]);

                                            $imageDetails = get_image_version_details('users');
                                            $width = $imageDetails['small']['w'];
                                            $height = $imageDetails['small']['h'];
                                        ?>

                                            @if($instructor['status'])
                                                <a href="{{config('app.NEW_PAGES_LINK') . '/' .  $instructor['slugable']['slug']}}">
                                                    <span class="custom-tooltip">{{ $instructor['title'] }} {{$instructor['subtitle']}}</span>
                                                    <img loading="lazy" alt="{{ $instructor['title']}} {{$instructor['subtitle']}}" title="{{ $instructor['title']}} {{$instructor['subtitle']}}" src="{{ cdn(get_image($instructor['mediable'],'users')) }}" width="{{ $width }}" height="{{ $height }}"/>
                                                </a>
                                            @else
                                                <a class="non-pointer" href="javascript:void(0)">
                                                    <span class="custom-tooltip">{{ $instructor['title'] }} {{$instructor['subtitle']}}</span>
                                                    <img loading="lazy" alt="{{ $instructor['title']}} {{$instructor['subtitle']}}" title="{{ $instructor['title']}} {{$instructor['subtitle']}}" src="{{ cdn('') }}" width="{{ $width }}" height="{{ $height }}"/>
                                                </a>
                                            @endif
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
