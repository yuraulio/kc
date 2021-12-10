<div class="social-share">
    <ul class="clearfix">
        <a target="_blank" title="Share on facebook" href="http://www.facebook.com/sharer.php?u={{ Request::url() }}" onclick="javascript:window.open(this.href,
            '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=300');return false;">
            <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/events/Facebook.svg')}}" width="23" alt="Share on facebook"></a></li>

        <a target="_blank" title="Share on Twitter" href="http://twitter.com/share?text={{ $title }}&amp;url={{ Request::url() }}&amp;via=KnowCrunch" onclick="javascript:window.open(this.href,
            '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
            <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/events/Twitter.svg')}}" width="23" alt="Share on Twitter"></a></li>

        <a target="_blank" title="Share on LinkedIn" href="https://www.linkedin.com/shareArticle?mini=true&amp;url={{ Request::url() }}&amp;title={{ $title }}
            &amp;summary={{ $summary }}&amp;source=KnowCrunch" onclick="javascript:window.open(this.href,
            '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
            <img class="replace-with-svg" src="{{cdn('/theme/assets/images/icons/social/events/Linkedin.svg')}}" width="23" alt="Share on LinkedIn"></a></li>
    </ul>
</div>
