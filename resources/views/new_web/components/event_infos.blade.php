
@if(isset($type) && $type == 'elearning')

    @if(isset($info['hours']['visible']['list']) && $info['hours']['visible']['list'] && $info['hours']['hour'] != null)

        <div class="expire-date">@if(isset($info['hours']['icon']['path']) && $info['hours']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['hours']['icon']['path'])}}" onerror="this.onerror=null;this.src='/theme/assets/images/icons/Start-Finish.svg'" alt="{{$info['hours']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt=""> @endif{{ $info['hours']['hour'] }} {{ ($info['hours']['text'] != null) ? $info['hours']['text'] : '' }}</div>
    @endif

    @if(isset($info['language']['visible']['list']) && $info['language']['visible']['list'] && $info['language']['text'] != null)
        <div class="language">@if(isset($info['language']['icon']['path']) && $info['language']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['language']['icon']['path'])}}" onerror="this.onerror=null;this.src='theme/assets/images/icons/Language.svg'" alt="{{$info['language']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="theme/assets/images/icons/Language.svg" alt=""> @endif {{ $info['language']['text'] }}</div>
    @endif

    @if(isset($info['certificate']['visible']['list']) && $info['certificate']['visible']['list'] && $info['certificate'] != null && $info['certificate']['type'] != null)
    <div class="certification_type">@if(isset($info['certificate']['icon']['path']) && $info['certificate']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['certificate']['icon']['path'])}}" onerror="this.onerror=null;this.src='/theme/assets/images/icons/Level.svg'" alt="{{$$info['certificate']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Level.svg" alt=""> @endif {{ $info['certificate']['type'] }}</div>
    @endif

    <?php
        if(isset($row['category'][0])){
            $sumStudents = $sumStudentsCategories[$row['category'][0]['id']];
        }else{
            $sumStudents = 0;
        }

    ?>

    @if(isset($info['students']['visible']['list']) && $info['students']['visible']['list'] && isset($info['students']['number']) && isset($row['category'][0]) && $sumStudents > (int)$info['students']['number'])
        <div class="students">@if(isset($info['students']['icon']['path']) && $info['students']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['students']['icon']['path'])}}" onerror="this.onerror=null;this.src='/theme/assets/images/icons/Group_User.1.svg'" alt="{{$info['students']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Group_User.1.svg" alt=""> @endif {{ $sumStudents }} {{ $info['students']['text'] }}</div>
    @endif

    @if(isset($info['elearning']['visible']['list']) && $info['elearning']['visible']['list'] && isset($info['elearning']['expiration']) && $info['elearning']['expiration'] != null)
        <div class="elearning">@if(isset($info['elearning']['icon']['path']) && $info['elearning']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['elearning']['icon']['path'])}}" onerror="this.onerror=null;this.src='/theme/assets/img/summary_icons/Days-Week.svg'" alt="{{$info['elearning']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/Days-Week.svg" alt=""> @endif {{ $info['elearning']['expiration'] }} {{ (isset($info['elearning']['text']) && $info['elearning']['text'] != null) ? $info['elearning']['text'] : '' }} </div>
    @endif

@elseif(isset($type) && $type == 'inclass')


    @if(isset($row['city']))
        @foreach($row['city'] as $city)
            <a href="{{ env('NEW_PAGES_LINK') . '/' .  $city->slugable->slug }}" class="city " title="{{ $city->name }}">

            @if(isset($info['inclass']['city']['icon']['path']) && $info['inclass']['city']['icon']['path'] != null)
                <img width="20" class="replace-with-svg" src="{{ cdn($info['inclass']['city']['icon']['path'])}}" alt="{{(isset($info['inclass']['city']['icon']['alt_text']) && $info['inclass']['city']['icon']['alt_text'] != null) ? $info['inclass']['city']['icon']['alt_text'] : '' }}">{{ $city->name }}</a>
            @else
                <img width="20" class="replace-with-svg" src="/theme/assets/images/icons/marker.svg" alt="">{{ $city->name }}</a>
            @endif
        @endforeach
    @endif

    @if(isset($info['hours']['visible']['list']) && $info['hours']['visible']['list'] && $info['hours']['hour'] != null)

        <div class="expire-date">@if(isset($info['hours']['icon']['path']) && $info['hours']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['hours']['icon']['path'])}}" onerror="this.onerror=null;this.src='/theme/assets/images/icons/Start-Finish.svg'" alt="{{$info['hours']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt=""> @endif{{ $info['hours']['hour'] }} {{ ($info['hours']['text'] != null) ? $info['hours']['text'] : '' }}</div>
    @endif

    @if(isset($info['language']['visible']['list']) && $info['language']['visible']['list'] && $info['language']['text'] != null)
        <div class="language">@if(isset($info['language']['icon']['path']) && $info['language']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['language']['icon']['path'])}}" onerror="this.onerror=null;this.src='theme/assets/images/icons/Language.svg'" alt="{{$info['language']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="theme/assets/images/icons/Language.svg" alt=""> @endif {{ $info['language']['text'] }}</div>
    @endif

    @if(isset($info['certificate']['visible']['list']) && $info['certificate']['visible']['list'] && $info['certificate'] != null && isset($info['certificate']['type']) && $info['certificate']['type'] != null)

        <div class="certification_type">@if(isset($info['certificate']['icon']['path']) && $info['certificate']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['certificate']['icon']['path'])}}" onerror="this.onerror=null;this.src='/theme/assets/images/icons/Level.svg'" alt="{{$info['certificate']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Level.svg" alt=""> @endif {{ $info['certificate']['type'] }}</div>
    @endif
    <?php
        if(isset($row['category'][0])){
            $sumStudents = $sumStudentsCategories[$row['category'][0]['id']];
        }else{
            $sumStudents = 0;
        }

    ?>

    @if(isset($info['students']['visible']['list']) && $info['students']['visible']['list'] && isset($info['students']['number']) && $sumStudents > (int)$info['students']['number'])
        <div class="students">@if(isset($info['students']['icon']['path']) && $info['students']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['students']['icon']['path'])}}" onerror="this.onerror=null;this.src='/theme/assets/images/icons/Group_User.1.svg'" alt="{{$info['students']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Group_User.1.svg" alt=""> @endif {{ $sumStudents }} {{ $info['students']['text'] }}</div>
    @endif



    @if(isset($info['inclass']['dates']['visible']['list']) && $info['inclass']['dates']['visible']['list'] && $info['inclass']['dates']['text'] != null)

        <div class="dates">@if(isset($info['inclass']['dates']['icon']['path']) && $info['inclass']['dates']['icon']['path'] != null) <img class="replace-with-svg" width="20" onerror="this.onerror=null;this.src='/theme/assets/img/summary_icons/Duration_Hours.svg'" src="{{cdn($info['inclass']['dates']['icon']['path'])}}" alt="{{$info['inclass']['dates']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/Duration_Hours.svg" alt=""> @endif {{ $info['inclass']['dates']['text'] }}</div>

    @endif
    @if(isset($info['inclass']['days']['visible']['list']) && $info['inclass']['days']['visible']['list'] && $info['inclass']['days']['text'] != null)
        <div class="days">@if(isset($info['inclass']['days']['icon']['path']) && $info['inclass']['days']['icon']['path'] != null) <img class="replace-with-svg" width="20" onerror="this.onerror=null;this.src='/theme/assets/img/summary_icons/Days-Week.svg'"  src="{{cdn($info['inclass']['days']['icon']['path'])}}" alt="{{$info['inclass']['days']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/Days-Week.svg" alt=""> @endif {{ $info['inclass']['days']['text'] }}</div>
    @endif

    @if(isset($info['inclass']['times']['visible']['list']) && $info['inclass']['times']['visible']['list'] && $info['inclass']['times']['text'] != null)

        <div class="times">@if(isset($info['inclass']['times']['icon']['path']) && $info['inclass']['times']['icon']['path'] != null) <img class="replace-with-svg" width="20" src="{{cdn($info['inclass']['times']['icon']['path'])}}" onerror="this.onerror=null;this.src='/theme/assets/img/summary_icons/time.svg'" alt="{{$info['inclass']['times']['icon']['alt_text']}}"> @else<img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/time.svg" alt=""> @endif {{ $info['inclass']['times']['text'] }}</div>

    @endif



@endif
