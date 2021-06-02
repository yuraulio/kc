<?php $social_media =  \Config::get('dpoptions.social_media.settings');?>

<!-- FOOTER -->
<footer id="footer" class="wrap">

    <div id="footer-primary">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 foot-logo">
                    <div class="footer-logo animatable fadeInDown">
                        <a href="/" title="Knowcrunch">
                            <img src="theme/assets/img/logo_footer.svg" />
                        </a>
                    </div>
                    <div id="footer_social">
                        @if(isset($social_media[1]))

                            @if($social_media[1]['facebook']['url'] != '')
                            <a target="_blank" title="{{ $social_media[1]['facebook']['title'] }}" href="{{ $social_media[1]['facebook']['url'] }}"><img class="social_ic_bot" src="theme/assets/img/facebook.svg" /></a>
                            @endif

                            @if($social_media[1]['twitter']['url'] != '')
                            <a target="_blank" title="{{ $social_media[1]['twitter']['title'] }}" href="{{ $social_media[1]['twitter']['url'] }}"><img class="social_ic_bot" src="theme/assets/img/twitter.svg" /></a>
                            @endif

                            @if($social_media[1]['instagram']['url'] != '')
                            <a target="_blank" title="{{ $social_media[1]['instagram']['title'] }}" href="{{ $social_media[1]['instagram']['url'] }}"><img class="social_ic_bot" src="theme/assets/img/instagram.svg" /></a>
                            @endif

                            @if($social_media[1]['linkedin']['url'] != '')
                            <a target="_blank" title="{{ $social_media[1]['linkedin']['title'] }}" href="{{ $social_media[1]['linkedin']['url'] }}"><img class="social_ic_bot" src="theme/assets/img/linkedin.svg" /></a>
                            @endif

                            @if($social_media[1]['googleplus']['url'] != '')
                            <a target="_blank" title="{{ $social_media[1]['googleplus']['title'] }}" href="{{ $social_media[1]['googleplus']['url'] }}"><img class="social_ic_bot" src="theme/assets/img/google.svg" /></a>
                            @endif

                            @if($social_media[1]['youtube']['url'] != '')
                            <a target="_blank" title="{{ $social_media[1]['youtube']['title'] }}" href="{{ $social_media[1]['youtube']['url'] }}"><img class="social_ic_bot" src="theme/assets/img/youtube.svg" /></a>
                            @endif

                        @endif
                    </div>
                </div>
                <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 animatable fadeInUp"> -->
                <div class="col-lg-3 col-md-3 col-sm-3 hidden-xs animatable fadeInUp">
                    <ul class="footer-links">
                    	<li><a href="/about">About Us</a></li>
                    	@if (!empty($footerLinks))
	                        @foreach ($footerLinks as $key => $row)
	                        <li>
	                            <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
	                                    {{ $frontHelp->pField($row, 'title') }}
	                            </a>
	                        </li>
	                        @endforeach
	                    @endif
                        <li><a href="/contact">Contact</a></li>

                    </ul>
                </div>
                
                <div class="col-lg-6 col-md-6 col-sm-6 hidden-xs animatable fadeInUp footer-newsletter">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h2 class="newsletter-footer-title">Join our newsletter today</h2>
                        <div class="newsletterReponse"></div>
                        </div>
                    </div>
                    <form method="POST" action="" name="newsletter_form" class="form-horizontal newsletter_form">
                    {{ csrf_field() }}
                    <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input type="text" class="form-control" id="surname" name="surname" placeholder="Surname">
                    </div>
                    </div>
                    <div class="row">

                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input type="text" class="form-control" id="email" name="email" placeholder="Email">
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input type="hidden" id="current_url_input" name="current_url" value="{{ Request::url() }}" />
                        <input type="hidden" name="lang" value="en">
                        <input type="hidden" name="please_fill" value="">
                        <!-- <label for="email" class="control-label"></label> -->
                        <button class="btn btn-newsletter-in" id="applyToNewsletter">SUBSCRIBE
                        </button>
                        <!-- <i class="fa fa-envelope-o" aria-hidden="true"></i> -->
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-xs-12 text-center">
                            <span id="loadingDiv"><img class="img-responsive center-block" src="theme/assets/img/ajax-loader-blue.gif" /></span>

                        </div>
                    </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div id="footer-secondary">
        <div class="container">

            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <p class="mobileL">Copyright © <?php echo date('Y'); ?> KnowCrunch</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <p class="mobileR"><a class="footer-link" target="_blank" href="http://www.darkpony.com">Design &amp; Development by Darkpony</a></p>
                </div><!-- /.row -->


                <!--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <ul class="footer-links">
                                @if (isset($footerPages) && !empty($footerPages))
                                    @foreach ($footerPages as $key => $row)
                                    <li>
                                        <a target="{{ $frontHelp->pTarget($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" href="{{ $frontHelp->pSlug($row) }}">
                                            {{ $frontHelp->pField($row, 'title') }}
                                        </a>
                                    </li>
                                    @endforeach
                                @endif
                                </ul>
                            </div> -->
            </div>
        </div>
    </div>


    <!-- <a href="/contact" class="cd-top-mail"><i class="fa fa-envelope"></i></a> -->
    <!-- <a href="/contact" class="cd-chat">Talk to us <img align="right" src="theme/assets/img/talk_icon.png" /></a> -->
</footer>

</body>
</html>

