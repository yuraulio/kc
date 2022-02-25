@php
    $instructor = [];
    foreach ($column->template->inputs as $input){
        $instructor[$input->key] = $input->value ?? "";
    }

    $data = $dynamic_page_data;
    
    $title = $data["title"];
    $content = $data["content"];
@endphp

<div class="instructor-area instructor-profile pb-4 pt-4">
    <div class="avatar-wrapper">
        <div class="avatar" alt="{{ $title }}" title="{{ $title }}"  style="background-image:url({{cdn(get_image($content['medias'],'instructors-testimonials'))}});"></div>
        <div class="social-links">
            <?php $social_media = json_decode($content['social_media'], true); ?>
            @if(isset($social_media['facebook']) && $social_media['facebook'] != '')
                <a target="_blank" href="{{ $social_media['facebook'] }}">
                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Facebook.svg')}}" width="23" alt="Visit"></a>
            @endif
            @if(isset($social_media['instagram']) && $social_media['instagram'] != '')
                <a target="_blank" href="{{ $social_media['instagram'] }}">
                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Instagram.svg')}}" width="23" alt="Visit"></a>
            @endif
            @if(isset($social_media['linkedin']) && $social_media['linkedin'] != '')
                <a target="_blank" href="{{ $social_media['linkedin'] }}">
                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Linkedin.svg')}}" width="23" alt="Visit"></a>
            @endif
            @if(isset($social_media['twitter']) && $social_media['twitter'] != '')
                <a target="_blank" href="{{ $social_media['twitter'] }}">
                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Twitter.svg')}}" width="23" alt="Visit"></a>
            @endif
            @if(isset($social_media['youtube']) && $social_media['youtube'] != '')
                <a target="_blank" href="{{ $social_media['youtube'] }}">
                <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/Youtube.svg')}}" width="23" alt="Visit"></a>
            @endif
        </div>
    </div>
</div>

