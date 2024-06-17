@php
    $instructor = [];
    foreach ($column->template->inputs as $input){
        $instructor[$input->key] = $input->value ?? "";
    }

    $data = $dynamic_page_data;

    $title = $data["title"];
    $content = $data["content"];
    $instructor = $data["instructor"];
@endphp

<div class="instructor-area instructor-profile mt-5 mb-5 ">
    <div class="avatar-wrapper">
        <img class="avatar2" src="{{cdn(get_image($instructor['medias'],'instructors-testimonials'))}}" alt="{{ $title }}" title="{{ $title }}">
        <div class="social-links">
            @if(isset($content['social_media']['facebook']) && $content['social_media']['facebook'] != '')
                <a target="_blank" href="{{ $content['social_media']['facebook'] }}">
                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Facebook.svg')}}" width="23" alt="Visit"></a>
            @endif
            @if(isset($content['social_media']['instagram']) && $content['social_media']['instagram'] != '')
                <a target="_blank" href="{{ $content['social_media']['instagram'] }}">
                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Instagram.svg')}}" width="23" alt="Visit"></a>
            @endif
            @if(isset($content['social_media']['linkedin']) && $content['social_media']['linkedin'] != '')
                <a target="_blank" href="{{ $content['social_media']['linkedin'] }}">
                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Linkedin.svg')}}" width="23" alt="Visit"></a>
            @endif
            @if(isset($content['social_media']['twitter']) && $content['social_media']['twitter'] != '')
                <a target="_blank" href="{{ $content['social_media']['twitter'] }}">
                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Twitter.svg')}}" width="23" alt="Visit"></a>
            @endif
            @if(isset($content['social_media']['youtube']) && $content['social_media']['youtube'] != '')
                <a target="_blank" href="{{ $content['social_media']['youtube'] }}">
                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Youtube.svg')}}" width="23" alt="Visit"></a>
            @endif
        </div>
    </div>
</div>

