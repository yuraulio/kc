
 <!-- SOCIAL SHARE -->

	    <div class="col-lg-12 col-md-12 col-sm-12 hidden-xs">
	    	<div class="post-social-line clearfix">
	            <div id="post-social" class="animatable fadeInDown">
	                <div class="social_icon social_icon_fb">
	                	<a target="_blank" title="Share on facebook" href="http://www.facebook.com/sharer.php?u={{ Request::url() }}" onclick="javascript:window.open(this.href,
	'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=300');return false;"><img class="social_ic_bot" src="theme/assets/img/new_icons/facebook_overview.svg"/> facebook
	                    </a>
	                </div>
	                <div class="social_icon social_icon_tw">
	                	<a target="_blank" title="Share on Twitter" href="http://twitter.com/share?text={{ $content->title }}&amp;url={{ Request::url() }}&amp;via=Knowcrunch" onclick="javascript:window.open(this.href,
	'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><img class="social_ic_bot" src="theme/assets/img/new_icons/twitter_overview.svg"/>  twitter
	                    </a>
	                </div>
	                <!-- <div class="social_icon social_icon_gp">
	                    <a target="_blank" href="https://plus.google.com/share?url={{ Request::url() }}" onclick="javascript:window.open(this.href,
	'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus"> </i> google+
	                    </a>
	                </div> -->
	                <div class="social_icon social_icon_li">
	                    <a target="_blank" title="Share on LinkedIn" href="https://www.linkedin.com/shareArticle?mini=true&amp;url={{ Request::url() }}&amp;title={{ $content->title }}
&amp;summary={{ $content->summary }}&amp;source=Knowcrunch" onclick="javascript:window.open(this.href,
	'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><img class="social_ic_bot" src="theme/assets/img/new_icons/linkedin_overview.svg"/> linkedin
	                    </a>
	                </div>
	            </div>
	        </div>
		</div>
	
 <!-- SOCIAL SHARE END -->
