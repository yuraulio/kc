@php
    $socials = [];
    foreach ($column->template->inputs as $input){
        $socials[$input->key] = $input->value ?? "";
    }
    $summary = $socials["social_summary"] ?? "";
    $title = $socials["social_title"] ?? "";

    if ($socials === []) {
        $facebookCheckbox = true;
        $facebookIcon = '/theme/assets/images/icons/social/events/Facebook-white.svg';

        $twitterCheckbox = true;
        $twitterIcon = '/theme/assets/images/icons/social/events/Twitter-white.svg';

        $linkedinCheckbox = true;
        $linkedinIcon = '/theme/assets/images/icons/social/events/Linkedin-white.svg';
    } else {
        $facebookCheckbox = $socials["social_facebook_checkbox"] ?? "";
        $facebookIcon = $socials["social_facebook_icon"]->full_path ?? '/theme/assets/images/icons/social/events/Facebook-white.svg';

        $twitterCheckbox = $socials["social_twitter_checkbox"] ?? "";
        $twitterIcon = $socials["social_twitter_icon"]->full_path ?? '/theme/assets/images/icons/social/events/Twitter-white.svg';

        $linkedinCheckbox = $socials["social_linkedin_checkbox"] ?? "";
        $linkedinIcon = $socials["social_linkedin_icon"]->full_path ?? '/theme/assets/images/icons/social/events/Linkedin-white.svg';
    }

@endphp

<div class="pb-4 pt-4">
    <div class="social-share">
        <ul class="clearfix">
            @if($facebookCheckbox)
                <a target="_blank" title="Share on facebook" href="http://www.facebook.com/sharer.php?u=@if(Session::get('thankyouData') && isset(Session::get('thankyouData')['event']['facebook'])) {{Session::get('thankyouData')['event']['facebook']}} @else {{ Request::url() }} @endif" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=300');return false;">
                    <img class="replace-with-svg" src="{{cdn($facebookIcon)}}" width="23" alt="Share on facebook">
                </a>
            @endif
            @if($twitterCheckbox)
                <a target="_blank" title="Share on Twitter" href="http://twitter.com/share?text={{ $title }}&amp;url=@if(Session::get('thankyouData') && isset(Session::get('thankyouData')['event']['twitter'])) {{Session::get('thankyouData')['event']['twitter']}} @else {{ Request::url() }} @endif&amp;via=Knowcrunch" onclick="javascript:window.open(this.href,
                    '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                    <img class="replace-with-svg" src="{{cdn($twitterIcon)}}" width="23" alt="Share on Twitter">
                </a>
            @endif
            @if($linkedinCheckbox)
                <a target="_blank" title="Share on LinkedIn" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=@if(Session::get('thankyouData') && isset(Session::get('thankyouData')['event']['linkedin'])) {{Session::get('thankyouData')['event']['linkedin']}} @else {{ Request::url() }} @endif&amp;title={{ $title }}
                    &amp;summary={{ $summary }}&amp;source=Knowcrunch" onclick="javascript:window.open(this.href,
                    '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                    <img class="replace-with-svg" src="{{cdn($linkedinIcon)}}" width="23" alt="Share on LinkedIn">
                </a>
            @endif
        </ul>
    </div>
</div>
