@if(isset($type) && $type == 'elearning')


    @if(isset($info['elearning']['visible']['list']) && $info['elearning']['visible']['list'] && isset($info['elearning']['expiration']) && $info['elearning']['expiration'] )
        <div class="elearning">@if(isset($info['elearning']['icon']['path']) && $info['elearning']['icon']['path'] ) <img loading="lazy" class="replace-with-svg resp-img" width="20" height="20" src="{{cdn($info['elearning']['icon']['path'])}}" onerror="this.onerror=null;this.src='/theme/assets/img/summary_icons/Days-Week.svg'" alt="{{$info['elearning']['icon']['alt_text']}}" title="{{$info['elearning']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/Days-Week.svg" alt="Days-Week-icon" title="Days-Week-icon"> @endif {{ $info['elearning']['expiration'] }} {{ (isset($info['elearning']['text']) && $info['elearning']['text'] ) ? $info['elearning']['text'] : '' }} </div>
    @endif

    @if(isset($info['hours']['visible']['list']) && $info['hours']['visible']['list'] && $info['hours']['hour'] )

        <div class="expire-date">@if(isset($info['hours']['icon']['path']) && $info['hours']['icon']['path'] ) <img loading="lazy" class="replace-with-svg resp-img" width="20" height="20" src="{{cdn($info['hours']['icon']['path'])}}" onerror="this.onerror=null;this.src='/theme/assets/images/icons/Start-Finish.svg'" alt="{{$info['hours']['icon']['alt_text']}}" title="{{$info['hours']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" height="20" src="/theme/assets/images/icons/Start-Finish.svg" alt="Start-Finish-icon" title="Start-Finish-icon"> @endif{{ $info['hours']['hour'] }}h</div>
    @endif

    @if(isset($info['elearning']['exam']['visible']['list']) && $info['elearning']['exam']['visible']['list'] && isset($info['elearning']['exam']['text']) && $info['elearning']['exam']['text'] )
        <div class="exam">@if(isset($info['elearning']['exam']['icon']['path']) && $info['elearning']['exam']['icon']['path'] ) <img loading="lazy" class="replace-with-svg resp-img" width="20" height="20" src="{{cdn($info['elearning']['exam']['icon']['path'])}}" onerror="this.onerror=null;this.src='/theme/assets/img/summary_icons/messages-warning-information.svg'" alt="{{$info['elearning']['exam']['icon']['alt_text']}}" title="{{$info['elearning']['exam']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" height="20" src="/theme/assets/img/summary_icons/messages-warning-information.svg" alt="messages-warning-information" title="messages-warning-information"> @endif <div class="exam_text">{!! $info['elearning']['exam']['text'] !!}</div> </div>
    @endif

@elseif(isset($type) && $type == 'inclass')


    @if(isset($row['city']))
        @foreach($row['city'] as $city)
            <a href="{{ env('NEW_PAGES_LINK') . '/' .  $city->slugable->slug }}" class="city " title="{{ $city->name }}">

            @if(isset($info['inclass']['city']['icon']['path']) && $info['inclass']['city']['icon']['path'])
                <img loading="lazy" width="20" height="20" class="replace-with-svg resp-img" src="{{ cdn($info['inclass']['city']['icon']['path'])}}" alt="{{(isset($info['inclass']['city']['icon']['alt_text']) && $info['inclass']['city']['icon']['alt_text'] ) ? $info['inclass']['city']['icon']['alt_text'] : '' }}" title="{{(isset($info['inclass']['city']['icon']['alt_text']) && $info['inclass']['city']['icon']['alt_text'] ) ? $info['inclass']['city']['icon']['alt_text'] : '' }}">{{ $city->name }}</a>
            @else
                <img loading="lazy" width="20" height="20" class="replace-with-svg resp-img" src="/theme/assets/images/icons/marker.svg" alt="marker-icon" title="marker-icon">{{ $city->name }}</a>
            @endif
        @endforeach
    @endif




    @if(isset($info['inclass']['dates']['visible']['list']) && $info['inclass']['dates']['visible']['list'] && $info['inclass']['dates']['text'])

        <div class="dates">@if(isset($info['inclass']['dates']['icon']['path']) && $info['inclass']['dates']['icon']['path']) <img loading="lazy" class="replace-with-svg resp-img" width="20" height="20" onerror="this.onerror=null;this.src='/theme/assets/img/summary_icons/Duration_Hours.svg'" src="{{cdn($info['inclass']['dates']['icon']['path'])}}" alt="{{$info['inclass']['dates']['icon']['alt_text']}}" title="{{$info['inclass']['dates']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" height="20" src="/theme/assets/img/summary_icons/Duration_Hours.svg" alt="Duration_Hours_icon" title="Duration_Hours_icon"> @endif <div class="exam_text">{!! $info['inclass']['dates']['text'] !!}</div></div>

    @endif

    @if(isset($info['hours']['visible']['list']) && $info['hours']['visible']['list'] && $info['hours']['hour'])

        <div class="expire-date">@if(isset($info['hours']['icon']['path']) && $info['hours']['icon']['path'] ) <img loading="lazy" class="replace-with-svg resp-img" width="20" height="20" src="{{cdn($info['hours']['icon']['path'])}}" onerror="this.onerror=null;this.src='/theme/assets/images/icons/Start-Finish.svg'" alt="{{$info['hours']['icon']['alt_text']}}" title="{{$info['hours']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt="start_finish_icon" title="start_finish_icon"> @endif{{ $info['hours']['hour'] }}h </div>
    @endif





@endif
