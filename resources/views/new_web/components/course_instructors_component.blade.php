@php
    $sections = $dynamic_page_data["sections"] ?? null;
    $topics = $dynamic_page_data["topics"] ?? null;
    $instructors = $dynamic_page_data["instructors"] ?? null;
    $title = '';
    $body = '';
    if(isset($sections['instructors'])){
        $title = $sections['instructors']->first()->title ?? null;
        $body = $sections['instructors']->first()->description ?? null;
    }
@endphp

<div class="course-full-text mt-5 mb-5">
@if(isset($instructors))
<?php
            $title = '';
            $body = '';
    if(isset($sections['instructors'])){
        $title = $sections['instructors']->first()->title;
        $body = $sections['instructors']->first()->description;
    }

    ?>
<h2 class="text-align-center text-xs-left tab-title" style="color: #fff;">{!!$title!!}</h2>
<h3>{!!$body!!}</h3>
<div class=" row row-flex row-flex-23">
    @foreach($instructors as $instructor)
    @foreach($instructor as $inst)
    <?php
        $socialMedia = json_decode($inst['social_media'],true);
        $fb = isset($socialMedia['facebook']) ? $socialMedia['facebook'] : '';
        $twitter = isset($socialMedia['twitter']) ? $socialMedia['twitter'] : '';
        $instagram = isset($socialMedia['instagram']) ? $socialMedia['instagram'] : '';
        $linkedIn = isset($socialMedia['linkedin']) ? $socialMedia['linkedin']: '';
        $yt = isset($socialMedia['youtube']) ? $socialMedia['youtube'] : '';
        $field2 = $inst['company'];

        $imageDetails = get_image_version_details('instructors-testimonials');

        ?>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <div class="instructor-box">
            <div class="instructor-inner">

                <div class="profile-img">
                    @if($inst['status'])
                        <a href="{{ env('NEW_PAGES_LINK') . '/' . $inst['slugable']['slug']}}"><img loading="lazy" src="{{cdn(get_image($inst['mediable'],'instructors-testimonials'))}}"  title="{{$inst['title']}}" alt="{{$inst['title']}}" width="{{ $imageDetails['w'] }}" height="{{ $imageDetails['h'] }}"></a>
                    @else
                        <img loading="lazy" src="{{cdn(get_image($inst['mediable'],'instructors-testimonials'))}}"  title="{{$inst['title']}}" alt="{{$inst['title']}}" width="{{ $imageDetails['w'] }}" height="{{ $imageDetails['h'] }}">
                    @endif
                </div>
                @if($inst['status'])
                    <h3><a style="color:#81be00;" href="{{env('NEW_PAGES_LINK') . '/' . $inst['slugable']['slug']}}">{{$inst['title']}} {{$inst['subtitle']}}</a></h3>
                @else
                    <h3 style="color:#81be00;">{{$inst['title']}} {{$inst['subtitle']}}</h3>
                @endif
                <p>{{$inst['header']}}, @if($inst['ext_url'] != '')<a style="color:#81be00;" target="_blank" title="{{$inst['header']}}"  href="{{$inst['ext_url']}}"  > {{$field2}}</a>.@endif</p>
                <ul class="social-wrapper">
                    @if($fb != '')
                    <li><a target="_blank" href="{{$fb}}"><img class="replace-with-svg"  src="/theme/assets/images/icons/social/Facebook.svg" width="16" alt="Visit"></a></li>
                    @endif
                    @if($instagram !='')
                    <li><a target="_blank" href="{{$instagram}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Instagram.svg')}}" width="16" alt="Visit"></a></li>
                    @endif
                    @if($linkedIn !='')
                    <li><a target="_blank" href="{{$linkedIn}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Linkedin.svg')}}" width="16" alt="Visit"></a></li>
                    @endif
                    @if($twitter !='')
                    <li><a target="_blank" href="{{$twitter}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Twitter.svg')}}" width="16" alt="Visit"></a></li>
                    @endif
                    @if($yt !='')
                    <li><a target="_blank" href="{{$yt}}"><img class="replace-with-svg"  src="{{cdn('/theme/assets/images/icons/social/Youtube.svg')}}" width="16" alt="Visit"></a></li>
                    @endif
                </ul>
                <!-- /.instructor-inner -->
            </div>
            <!-- /.instructor-box -->
            </div>
            <!-- /.col-3.col-sm-12 -->
        </div>
    @endforeach
    @endforeach
    @endif
    <!-- /.row.row-flex -->
</div>
<!-- /.course-full-text -->
</div>
