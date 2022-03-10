@php
    $sections = $dynamic_page_data["sections"] ?? null;
    $section_fullvideo = $dynamic_page_data["section_fullvideo"] ?? null;
    $summary = $dynamic_page_data["summary"] ?? null;
    $sumStudents = $dynamic_page_data["sumStudents"] ?? null;
@endphp

<div class="course-tab-sidebar mt-5 mb-5" style="width: 100%;">
    <div class="course-details @if(!isset($section_fullvideo)) non-video-height @endif">
        <ul class="two-column-list">
            @foreach($summary as $sum)
                @if($sum['title'] && $sum['section'] != 'students')
                <li>
                    @if($sum['mediable'])<img class="info-icon" class="replace-with-svg" src="{{cdn(get_image($sum['mediable']))}}" width="30" />@endif
                    <div class="info-text">
                        <p>
                            {{  $sum['title'] }}
                            </br>
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
    </div>
</div>