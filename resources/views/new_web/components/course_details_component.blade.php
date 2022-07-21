@php
    $sections = $dynamic_page_data["sections"] ?? null;
    $section_fullvideo = $dynamic_page_data["section_fullvideo"] ?? null;
    $summary = $dynamic_page_data["summary"] ?? null;
    $sumStudents = $dynamic_page_data["sumStudents"] ?? null;
    $info = $dynamic_page_data['info'] ?? null;
    //dd($info);
@endphp

<div class="course-tab-sidebar mt-5 mb-5" style="width: 100%;">
    <div class="course-details @if(!isset($section_fullvideo)) non-video-height @endif">
        <ul class="two-column-list">


            @if(isset($info['hours']['visible']['landing']) && $info['hours']['visible']['landing'] && isset($info['hours']['hour']) && $info['hours']['hour'] != null)
            <li>
                @if(isset($info['hours']['icon']) && $info['hours']['icon']['path'] != null) <img class="info-icon" onerror="this.onerror=null;this.src='/theme/assets/images/icons/Start-Finish.svg'" src="{{cdn($info['hours']['icon']['path'])}}" width="30" alt="{{$info['hours']['icon']['alt_text']}}" /> @else <img class="info-icon" width="30" src="/theme/assets/images/icons/Start-Finish.svg" alt=""> @endif
                <div class="info-text">
                        <p>
                            {{  $info['hours']['hour'] }}
                            </br>
                            {!!  $info['hours']['text'] !!}
                        </p>
                    </div>
            </li>
            @endif

            @if(isset($info['language']['visible']['landing']) && $info['language']['visible']['landing'] && isset($info['language']['text']) && $info['language']['text'] != null)
            <li>
                @if(isset($info['language']['icon']['path']) && isset($info['language']['icon']) && $info['language']['icon']['path'] != null) <img class="info-icon" src="{{cdn($info['language']['icon']['path'])}}" onerror="this.onerror=null;this.src='theme/assets/images/icons/Language.svg'" width="30" alt="{{$info['language']['icon']['alt_text']}}"/> @else <img class="info-icon" src="/theme/assets/images/icons/Language.svg" width="30" alt=""/>  @endif
                <div class="info-text">
                    <p>
                        {{  $info['language']['text'] }}
                        </br>

                    </p>
                </div>
            </li>
            @endif

            @if(isset($info['certificate']['visible']['landing']) && $info['certificate']['visible']['landing'] && isset($info['certificate']['type']) && $info['certificate']['type'] != null)
            <li>
                @if(isset($info['certificate']['icon']['path']) && $info['certificate']['icon']['path'] != null) <img class="info-icon" src="{{cdn($info['certificate']['icon']['path'])}}" onerror="this.onerror=null;this.src='/theme/assets/images/icons/Level.svg'" width="30" alt="{{$info['certificate']['icon']['alt_text']}}" /> @else <img class="info-icon" width="30" src="/theme/assets/images/icons/Level.svg" alt=""> @endif
                <div class="info-text">
                    <p>
                        {{  $info['certificate']['type'] }}
                        </br>

                    </p>
                </div>
            </li>
            @endif

            @if(isset($info['students']['visible']['landing']) && $info['students']['visible']['landing'] && isset($info['students']['number']) && $sumStudents > (int)$info['students']['number'])
            <li>
                @if(isset($info['students']['icon']['path']) && $info['students']['icon']['path'] != null)<img class="info-icon" src="{{cdn($info['students']['icon']['path'])}}" width="30" onerror="this.onerror=null;this.src='/theme/assets/images/icons/Group_User.1.svg'" alt="{{$info['students']['icon']['alt_text']}}" /> @else <img class="info-icon"  src="/theme/assets/images/icons/Group_User.1.svg" width="30" alt="" /> @endif
                <div class="info-text">
                    <p>
                        {{$sumStudents}} {{  $info['students']['text'] }}
                        </br>
                        {{--{!!  $sum['description'] !!}--}}
                    </p>
                </div>
            </li>
            @endif

            @if(isset($info['elearning']['visible']['landing']) && $info['elearning']['visible']['landing'] && isset($info['elearning']['expiration']) && $info['elearning']['expiration'] != null)
            <li>
                @if(isset($info['elearning']['icon']['path']) && $info['elearning']['icon']['path'] != null)<img class="info-icon" src="{{cdn($info['elearning']['icon']['path'])}}" width="30" onerror="this.onerror=null;this.src='/theme/assets/img/summary_icons/Days-Week.svg'" alt="{{$info['elearning']['icon']['alt_text']}}" /> @else <img class="info-icon"  src="/theme/assets/img/summary_icons/Days-Week.svg" width="30" alt="" /> @endif
                <div class="info-text">
                    <p>
                        {{  $info['elearning']['expiration'] }} {{ (isset($info['elearning']['text']) && $info['elearning']['text'] != null) ? $info['elearning']['text'] : ''}}
                        </br>

                    </p>
                </div>
            </li>
            @endif

            @if(isset($info['elearning']['exam']['visible']['landing']) && $info['elearning']['exam']['visible']['landing'] && isset($info['elearning']['exam']['text']) && $info['elearning']['exam']['text'] != null)
            <li>
                @if(isset($info['elearning']['exam']['icon']['path']) && $info['elearning']['exam']['icon']['path'] != null)<img class="info-icon" src="{{cdn($info['elearning']['exam']['icon']['path'])}}" width="30" onerror="this.onerror=null;this.src='/theme/assets/img/summary_icons/messages-warning-information.svg'" alt="{{$info['elearning']['exam']['icon']['alt_text']}}" /> @else <img class="info-icon"  src="/theme/assets/img/summary_icons/messages-warning-information.svg" width="30" alt="" /> @endif
                <div class="info-text">
                    <p>
                        {{  $info['elearning']['exam']['text'] }}
                        </br>

                    </p>
                </div>
            </li>
            @endif

            @if(isset($info['inclass']['dates']['visible']['landing']) && $info['inclass']['dates']['visible']['landing'] && isset($info['inclass']['dates']['text']) && $info['inclass']['dates']['text'] != null)

            <li>
                @if(isset($info['inclass']['dates']['icon']['path']) && $info['inclass']['dates']['icon']['path'] != null)<img class="info-icon"  src="{{cdn($info['inclass']['dates']['icon']['path'])}}" onerror="this.onerror=null;this.src='/theme/assets/img/summary_icons/Duration_Hours.svg'" width="30" alt="{{$info['inclass']['dates']['icon']['alt_text']}}" /> @else <img class="info-icon" width="30" src="/theme/assets/img/summary_icons/Duration_Hours.svg" alt="">@endif
                <div class="info-text">
                    <p>
                        {{  $info['inclass']['dates']['text'] }}
                        </br>

                    </p>
                </div>
            </li>
            @endif

            @if(isset($info['inclass']['days']['visible']['landing']) && $info['inclass']['days']['visible']['landing'] && isset($info['inclass']['days']['text']) && $info['inclass']['days']['text'] != null)

            <li>
                @if(isset($info['inclass']['days']['icon']['path']) && $info['inclass']['days']['icon']['path'] != null)<img class="info-icon" onerror="this.onerror=null;this.src='/theme/assets/img/summary_icons/Days-Week.svg'"  src="{{cdn($info['inclass']['days']['icon']['path'])}}" alt="{{$info['inclass']['days']['icon']['alt_text']}}"> @else<img class="info-icon" width="30" src="/theme/assets/img/summary_icons/Days-Week.svg" src="{{cdn($info['inclass']['days']['icon']['path'])}}"  width="30" alt="{{$info['inclass']['days']['icon']['alt_text']}}" />@endif
                <div class="info-text">
                    <p>
                        {{  $info['inclass']['days']['text'] }}
                        </br>

                    </p>
                </div>
            </li>
            @endif

            @if(isset($info['inclass']['times']['visible']['landing']) && $info['inclass']['times']['visible']['landing'] && isset($info['inclass']['times']['text']) && $info['inclass']['times']['text'] != null)

            <li>
                @if(isset($info['inclass']['times']['icon']['path']) && $info['inclass']['times']['icon']['path'] != null)<img class="info-icon" onerror="this.onerror=null;this.src='/theme/assets/img/summary_icons/time.svg'" src="{{cdn($info['inclass']['times']['icon']['path'])}}" width="30" alt="{{$info['inclass']['times']['icon']['alt_text']}}" />@else<img class="info-icon" width="30" src="/theme/assets/img/summary_icons/time.svg" alt="">@endif
                <div class="info-text">
                    <p>
                        {{  $info['inclass']['times']['text'] }}
                        </br>

                    </p>
                </div>
            </li>
            @endif

        </ul>
    </div>
</div>
