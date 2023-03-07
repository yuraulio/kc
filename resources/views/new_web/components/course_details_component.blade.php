@php
    $sections = $dynamic_page_data["sections"] ?? null;
    $partners = $dynamic_page_data["partners"] ?? null;
    $section_fullvideo = $dynamic_page_data["section_fullvideo"] ?? null;
    $summary = $dynamic_page_data["summary"] ?? null;
    $sumStudents = $dynamic_page_data["sumStudents"] ?? null;
    $info = $dynamic_page_data['info'] ?? null;
    //dd($info);
    $has_info = false;
@endphp


<div class="row mt-5 mb-5 course_details_infos">


@if(isset($info['hours']['visible']['landing']) && $info['hours']['visible']['landing'] && isset($info['hours']['hour']) && $info['hours']['hour'] )
    <?php $has_info = true; ?>
    <div class="col-10 col-sm-5 col-md-auto col-lg-auto details">
        @if(isset($info['hours']['icon']) && $info['hours']['icon']['path'] )

            @if(isset($info['hours']['icon']['link_status']) && $info['hours']['icon']['link'] != null) <a href="{{$info['hours']['icon']['link']}}" target="_blank"> @endif
            <img loading="lazy" class="info-icon" onerror="this.src='/theme/assets/images/icons/Start-Finish.svg'" src="{{cdn($info['hours']['icon']['path'])}}" width="60" height="60" alt="{{$info['hours']['icon']['alt_text']}}" title="{{$info['hours']['icon']['alt_text']}}" />
            @if(isset($info['hours']['icon']['link_status']) && $info['hours']['icon']['link'] != null) </a> @endif
        @else
            @if(isset($info['hours']['icon']['link_status']) && $info['hours']['icon']['link'] != null) <a href="{{$info['hours']['icon']['link']}}" target="_blank"> @endif
            <img class="info-icon" width="60" height="60" src="/theme/assets/images/icons/Start-Finish.svg" alt="start-finish-icon" title="start-finish-icon">
            @if(isset($info['hours']['icon']['link_status']) && $info['hours']['icon']['link'] != null) </a> @endif
        @endif
        <div class="info-text text-center">
                <p>
                    {{  $info['hours']['hour'] }} {!!  $info['hours']['text'] !!}
                </p>
            </div>
    </div>
@endif

@if(isset($info['inclass']['dates']['visible']['landing']) && $info['inclass']['dates']['visible']['landing'] && isset($info['inclass']['dates']['text']) && $info['inclass']['dates']['text'] )
    <?php $has_info = true; ?>
    <div class="col-10 col-sm-5 col-md-auto col-lg-auto details">
        @if(isset($info['inclass']['dates']['icon']['path']) && $info['inclass']['dates']['icon']['path'] )

            @if(isset($info['inclass']['dates']['icon']['link_status']) && $info['inclass']['dates']['icon']['link'] != null) <a href="{{$info['inclass']['dates']['icon']['link']}}" target="_blank"> @endif
            <img loading="lazy" class="info-icon" src="{{cdn($info['inclass']['dates']['icon']['path'])}}" onerror="this.onerror=null;this.src='/theme/assets/img/summary_icons/Duration_Hours.svg'" width="60" height="60" alt="{{$info['inclass']['dates']['icon']['alt_text']}}" title="{{$info['inclass']['dates']['icon']['alt_text']}}"/>
            @if(isset($info['inclass']['dates']['icon']['link_status']) && $info['inclass']['dates']['icon']['link'] != null) </a> @endif
        @else
            @if(isset($info['inclass']['dates']['icon']['link_status']) && $info['inclass']['dates']['icon']['link'] != null) <a href="{{$info['inclass']['dates']['icon']['link']}}" target="_blank"> @endif
            <img class="info-icon" width="60" height="60" src="/theme/assets/img/summary_icons/Duration_Hours.svg" alt="Duration_Hours_icon" title="Duration_Hours_icon">
            @if(isset($info['inclass']['dates']['icon']['link_status']) && $info['inclass']['dates']['icon']['link'] != null) </a> @endif
        @endif
        <div class="info-text text-center">
            <p>
                {!!  $info['inclass']['dates']['text'] !!}
                </br>

            </p>
        </div>
    </div>
@endif
@if(isset($info['elearning']['visible']['landing']) && $info['elearning']['visible']['landing'] && isset($info['elearning']['expiration']) && $info['elearning']['expiration'] )
    <?php $has_info = true; ?>
    <div class="col-10 col-sm-5 col-md-auto col-lg-auto details">
        @if(isset($info['elearning']['icon']['path']) && $info['elearning']['icon']['path'] )
            @if(isset($info['elearning']['icon']['link_status']) && $info['elearning']['icon']['link'] != null) <a href="{{$info['elearning']['icon']['link']}}" target="_blank"> @endif
            <img loading="lazy" class="info-icon" src="{{cdn($info['elearning']['icon']['path'])}}" width="60" height="60" onerror="this.onerror=null;this.src='/theme/assets/img/summary_icons/Days-Week.svg'" alt="{{$info['elearning']['icon']['alt_text']}}" title="{{$info['elearning']['icon']['alt_text']}}" />
            @if(isset($info['elearning']['icon']['link_status']) && $info['elearning']['icon']['link'] != null) </a> @endif
        @else
            @if(isset($info['elearning']['icon']['link_status']) && $info['elearning']['icon']['link'] != null) <a href="{{$info['elearning']['icon']['link']}}" target="_blank"> @endif
            <img class="info-icon"  src="/theme/assets/img/summary_icons/Days-Week.svg" width="60" height="60" alt="Days-Week-icon" title="Days-Week-icon"/>
            @if(isset($info['elearning']['icon']['link_status']) && $info['elearning']['icon']['link'] != null) </a> @endif
        @endif
        <div class="info-text text-center">
            <p>
                {{  $info['elearning']['expiration'] }} {!! (isset($info['elearning']['text']) && $info['elearning']['text'] ) ? $info['elearning']['text'] : '' !!}
                </br>

            </p>
        </div>
    </div>
@endif

@if(isset($info['inclass']['days']['visible']['landing']) && $info['inclass']['days']['visible']['landing'] && isset($info['inclass']['days']['text']) && $info['inclass']['days']['text'] )
    <?php $has_info = true; ?>
    <div class="col-10 col-sm-5 col-md-auto col-lg-auto details">
        @if(isset($info['inclass']['days']['icon']['path']) && $info['inclass']['days']['icon']['path'] )
            @if(isset($info['inclass']['days']['icon']['link_status']) && $info['inclass']['days']['icon']['link'] != null) <a href="{{$info['inclass']['days']['icon']['link']}}" target="_blank"> @endif
            <img loading="lazy" class="info-icon" onerror="this.onerror=null;this.src='/theme/assets/img/summary_icons/Days-Week.svg'"  src="{{cdn($info['inclass']['days']['icon']['path'])}}" alt="{{$info['inclass']['days']['icon']['alt_text']}}">
            @if(isset($info['inclass']['days']['icon']['link_status']) && $info['inclass']['days']['icon']['link'] != null) </a> @endif
        @else
            @if(isset($info['inclass']['days']['icon']['link_status']) && $info['inclass']['days']['icon']['link'] != null) <a href="{{$info['inclass']['days']['icon']['link']}}" target="_blank"> @endif
            <img class="info-icon" width="60" height="60" src="/theme/assets/img/summary_icons/Days-Week.svg" alt="/theme/assets/img/summary_icons/Days-Week.svg">
            @if(isset($info['inclass']['days']['icon']['link_status']) && $info['inclass']['days']['icon']['link'] != null) </a> @endif
        @endif
        <div class="info-text text-center">
            <p>
                {!!  $info['inclass']['days']['text'] !!}
                </br>

            </p>
        </div>
    </div>
@endif

@if(isset($info['language']['visible']['landing']) && $info['language']['visible']['landing'] && isset($info['language']['text']) && $info['language']['text'] )
    <?php $has_info = true; ?>
    <div class="col-10 col-sm-5 col-md-auto col-lg-auto details">
        @if(isset($info['language']['icon']['path']) && isset($info['language']['icon']) && $info['language']['icon']['path'] )
            @if(isset($info['language']['icon']['link_status']) && $info['language']['icon']['link'] != null) <a href="{{$info['language']['icon']['link']}}" target="_blank"> @endif
            <img loading="lazy" class="info-icon" src="{{cdn($info['language']['icon']['path'])}}" onerror="this.onerror=null;this.src='theme/assets/images/icons/Language.svg'" width="60" height="60" alt="{{$info['language']['icon']['alt_text']}}"/>
            @if(isset($info['language']['icon']['link_status']) && $info['language']['icon']['link'] != null) </a> @endif

        @else
            @if(isset($info['language']['icon']['link_status']) && $info['language']['icon']['link'] != null) <a href="{{$info['language']['icon']['link']}}" target="_blank"> @endif
            <img class="info-icon" src="/theme/assets/images/icons/Language.svg" width="60" height="60" alt="Language_icon"/>
            @if(isset($info['language']['icon']['link_status']) && $info['language']['icon']['link'] != null) </a> @endif

        @endif
        <div class="info-text text-center">
            <p>
                {!!  $info['language']['text'] !!}
                </br>

            </p>
        </div>
    </div>
@endif
@if(isset($info['elearning']['exam']['visible']) && $info['elearning']['exam']['visible']['landing'] && isset($info['elearning']['exam']['text']) && $info['elearning']['exam']['text'] )
    <?php $has_info = true; ?>
    <div class="col-10 col-sm-5 col-md-auto col-lg-auto details">

        @if(isset($info['elearning']['exam']['icon']['link_status']) && $info['elearning']['exam']['icon']['link'] != null) <a href="{{$info['elearning']['exam']['icon']['link']}}" target="_blank"> @endif

        <img loading="lazy" class="info-icon" src="{{cdn($info['elearning']['exam']['icon']['path'])}}" onerror="this.src='{{cdn('/theme/assets/img/summary_icons/messages-warning-information.svg')}}'" width="60" height="60" alt="{{$info['elearning']['exam']['icon']['alt_text']}}"/>
        @if(isset($info['elearning']['exam']['icon']['link_status']) && $info['elearning']['exam']['icon']['link'] != null) </a> @endif

        <div class="info-text text-center">
            <p>
                {!!  $info['elearning']['exam']['text'] !!}
                </br>

            </p>
        </div>
    </div>
@endif

@if(isset($info['certificate']['visible']['landing']) && $info['certificate']['visible']['landing'] && isset($info['certificate']['type']) && $info['certificate']['type'] )
    <?php $has_info = true; ?>
    <div class="col-10 col-sm-5 col-md-auto col-lg-auto details">
        @if(isset($info['certificate']['icon']['path']) && $info['certificate']['icon']['path'] )

            @if(isset($info['certificate']['icon']['link_status']) && $info['certificate']['icon']['link'] != null) <a href="{{$info['certificate']['icon']['link']}}" target="_blank"> @endif

            <img loading="lazy" class="info-icon" src="{{$info['certificate']['icon']['path']}}" onerror="this.onerror=null;this.src='/theme/assets/images/icons/Level.svg'" width="60" height="60" alt="{{$info['certificate']['icon']['alt_text']}}" />
            @if(isset($info['certificate']['icon']['link_status']) && $info['certificate']['icon']['link'] != null) </a> @endif

        @else
            @if(isset($info['certificate']['icon']['link_status']) && $info['certificate']['icon']['link'] != null) <a href="{{$info['certificate']['icon']['link']}}" target="_blank"> @endif
            <img class="info-icon" width="60" height="60" src="/theme/assets/images/icons/Level.svg" alt="level_icon">
            @if(isset($info['certificate']['icon']['link_status']) && $info['certificate']['icon']['link'] != null) </a> @endif

        @endif
        <div class="info-text text-center">
            <p>
                {!!  $info['certificate']['type'] !!}
                </br>

            </p>
        </div>
    </div>
@endif

@if( isset($info['students']['visible']['landing']) && $info['students']['visible']['landing'] && isset($info['students']['text']) && $info['students']['text'])
    <?php $has_info = true; ?>
    <div class="col-10 col-sm-5 col-md-auto col-lg-auto details">
        @if(isset($info['students']['icon']['path']) && $info['students']['icon']['path'] )
            @if(isset($info['students']['icon']['link_status']) && $info['students']['icon']['link'] != null) <a href="{{$info['students']['icon']['link']}}" target="_blank"> @endif

            <img loading="lazy" class="info-icon" src="{{$info['students']['icon']['path']}}" width="60" height="60" onerror="this.onerror=null;this.src='/theme/assets/images/icons/Group_User.1.svg'" alt="{{$info['students']['icon']['alt_text']}}" />
            @if(isset($info['students']['icon']['link_status']) && $info['students']['icon']['link'] != null) </a> @endif

        @else
            @if(isset($info['students']['icon']['link_status']) && $info['students']['icon']['link'] != null) <a href="{{$info['students']['icon']['link']}}" target="_blank"> @endif

            <img class="info-icon"  src="/theme/assets/images/icons/Group_User.1.svg" width="60" height="60" alt="user_icon" />
            @if(isset($info['students']['icon']['link_status']) && $info['students']['icon']['link'] != null) </a> @endif

        @endif
        <div class="info-text text-center">
            <p>
                {{$sumStudents + (int)$info['students']['number']}} {!!  $info['students']['text'] !!}
                </br>
                {{--{!!  $sum['description'] !!}--}}
            </p>
        </div>
    </div>
@endif

@if(isset($info['inclass']['times']['visible']['landing']) && $info['inclass']['times']['visible']['landing'] && isset($info['inclass']['times']['text']) && $info['inclass']['times']['text'] )
    <?php $has_info = true; ?>
    <div class="col-10 col-sm-5 col-md-auto col-lg-auto details">
        @if(isset($info['inclass']['times']['icon']['path']) && $info['inclass']['times']['icon']['path'] )
            @if(isset($info['inclass']['times']['icon']['link_status']) && $info['inclass']['times']['icon']['link'] != null) <a href="{{$info['inclass']['times']['icon']['link']}}" target="_blank"> @endif

            <img loading="lazy" class="info-icon" onerror="this.onerror=null;this.src='/theme/assets/img/summary_icons/Days-Week.svg'" src="{{$info['inclass']['times']['icon']['path']}}" width="60" height="60" alt="{{$info['inclass']['times']['icon']['alt_text']}}" />
            @if(isset($info['inclass']['times']['icon']['link_status']) && $info['inclass']['times']['icon']['link'] != null) </a> @endif

        @else
            @if(isset($info['inclass']['times']['icon']['link_status']) && $info['inclass']['times']['icon']['link'] != null) <a href="{{$info['inclass']['times']['icon']['link']}}" target="_blank"> @endif

            <img class="info-icon" width="60" height="60" src="/theme/assets/img/summary_icons/Days-Week.svg" alt="days-week-icon">
            @if(isset($info['inclass']['times']['icon']['link_status']) && $info['inclass']['times']['icon']['link'] != null) </a> @endif
        @endif
        <div class="info-text text-center">
            <p>
                {!!  $info['inclass']['times']['text'] !!}
                </br>

            </p>
        </div>
    </div>
@endif


@if($partners != null && count($partners) != 0 && $info['partner']['visible']['landing']==1)
    <?php $has_info = true; ?>
    <div class="col-10 col-sm-5 col-md-auto col-lg-auto details">

        @if($partners[0]['mediable'] && isset($partners[0]['mediable']) && $partners[0]['mediable']['name'] != null)

            @if(isset($info['partner']['icon']) && $info['partner']['icon']['link_status'] == 'on' && $info['partner']['icon']['link'] != '') <a href="{{$info['partner']['icon']['link']}}" target="_blank"> @endif
            <img loading="lazy" class="info-icon" onerror="this.onerror=null;this.src='/theme/assets/img/summary_icons/Days-Week.svg'" src="{{$partners[0]['mediable']['path']}}/{{$partners[0]['mediable']['original_name']}}" width="60" height="60" alt="" />
            @if(isset($info['partner']['icon']) && $info['partner']['icon']['link_status'] == 'on' && $info['partner']['icon']['link'] != '') </a> @endif

        @else

            @if(isset($info['partner']['icon']) && $info['partner']['icon']['link_status'] == 'on' && $info['partner']['icon']['link'] != '') <a href="{{$info['inclass']['times']['icon']['link']}}" target="_blank"> @endif
            <img loading="lazy" class="info-icon" onerror="this.onerror=null;this.src='/theme/assets/img/summary_icons/Days-Week.svg'" src="/theme/assets/img/summary_icons/Days-Week.svg" width="60" height="60" alt="" />
            @if(isset($info['partner']['icon']) && $info['partner']['icon']['link_status'] == 'on' && $info['partner']['icon']['link'] != '') </a> @endif

        @endif


        <div class="info-text text-center">

            @if(isset($info['partner']['text']) && $info['partner']['text'] != '')
                {!!  $info['partner']['text'] !!}

            @elseif($partners != null && count($partners) != 0)

                @if($partners[0]['url'] != null && $partners[0]['url'] != '')
                    <a href="{{ $partners[0]['url'] }}">{{ $partners[0]['name'] }}</a>
                @endif
            @endif



        </div>
    </div>
@endif


</div>
<script type="application/javascript">
        $( document ).ready(function() {
            let has_info = @json($has_info);
            if(!has_info){
                $('.course_details_infos').hide();
            }
        });
</script>

