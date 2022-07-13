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
                @if(isset($info['hours']['icon']) && $info['hours']['icon']['path'] != null) <img class="info-icon" class="replace-with-svg" src="{{cdn($info['hours']['icon']['path'])}}" width="30" alt="{{$info['hours']['icon']['alt_text']}}" /> @else <img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt=""> @endif
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
                @if(isset($info['language']['icon']['path']) && isset($info['language']['icon']) && $info['language']['icon']['path'] != null) <img class="info-icon" class="replace-with-svg" src="{{cdn($info['language']['icon']['path'])}}" width="30" alt="{{$info['language']['icon']['alt_text']}}"/> @else <img class="info-icon" class="replace-with-svg" src="/theme/assets/images/icons/Language.svg" width="30" alt=""/>  @endif
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
                @if(isset($info['certificate']['icon']['path']) && $info['certificate']['icon']['path'] != null) <img class="info-icon" class="replace-with-svg" src="{{cdn($info['certificate']['icon']['path'])}}" width="30" alt="{{$info['certificate']['icon']['alt_text']}}" /> @else <img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Level.svg" alt=""> @endif
                <div class="info-text">
                    <p>
                        {{  $info['certificate']['type'] }}
                        </br>

                    </p>
                </div>
            </li>
            @endif

            @if(isset($info['students']['visible']['landing']) && $info['students']['visible']['landing'] && $sumStudents > $info['students']['number'])
            <li>
                @if(isset($info['students']['icon']['path']) && $info['students']['icon']['path'] != null)<img class="info-icon" class="replace-with-svg" src="{{cdn($info['students']['icon']['path'])}}" width="30" alt="{{$info['students']['icon']['alt_text']}}" /> @else <img class="info-icon" class="replace-with-svg" src="/theme/assets/images/icons/Group_User.1.svg" width="30" alt="" /> @endif
                <div class="info-text">
                    <p>
                        {{$sumStudents}} {{  $info['students']['text'] }}
                        </br>
                        {{--{!!  $sum['description'] !!}--}}
                    </p>
                </div>
            </li>
            @endif

            @if(isset($info['inclass']['dates']['visible']['landing']) && $info['inclass']['dates']['visible']['landing'] && isset($info['inclass']['dates']['text']) && $info['inclass']['dates']['text'] != null)

            <li>
                @if(isset($info['inclass']['dates']['icon']['path']) && $info['inclass']['dates']['icon']['path'] != null)<img class="info-icon" class="replace-with-svg" src="{{cdn($info['inclass']['dates']['icon']['path'])}}" width="30" alt="{{$info['inclass']['dates']['icon']['alt_text']}}" />@endif
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
                @if(isset($info['inclass']['days']['icon']['path']) && $info['inclass']['days']['icon']['path'] != null)<img class="info-icon" class="replace-with-svg" src="{{cdn($info['inclass']['days']['icon']['path'])}}" width="30" alt="{{$info['inclass']['days']['icon']['alt_text']}}" />@endif
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
                @if(isset($info['inclass']['times']['icon']['path']) && $info['inclass']['times']['icon']['path'] != null)<img class="info-icon" class="replace-with-svg" src="{{cdn($info['inclass']['times']['icon']['path'])}}" width="30" alt="{{$info['inclass']['times']['icon']['alt_text']}}" />@endif
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
